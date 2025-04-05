<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): Factory|Application|View|JsonResponse
    {
        $this->authorize('View Purchase Orders');

        if ($request->ajax()) {
            $data = PurchaseOrder::withTrashed()->with(['supervisor', 'project', 'details'])->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('order_date', fn($data): string => Carbon::parse($data->order_date)->format('d-m-Y'))
                ->editColumn('product_count', static fn($data) => $data->details->count())
                ->editColumn('status', static function ($data) {
                    $deliveredCount = $data->details->where('status', 'Delivered')->count();
                    $total = $data->details->count();
                    $badgeClass = $deliveredCount === $total ? 'success' : 'warning';
                    return '<span class="badge bg-' . $badgeClass . '">' . $deliveredCount . '/' . $total . ' Delivered</span>';
                })
                ->addColumn('action', static function ($data) {
                    return '<div class="d-flex justify-content-center">
                        <a href="' . route('purchase-orders.show', $data->id) . '" class="btn btn-outline-success btn-sm m-1">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.purchase_orders.index');
    }

    public function show($id)
    {
        $order = PurchaseOrder::with(['details.product', 'details.category'])->findOrFail($id);
        return view('admin.purchase_orders.show', compact('order'));
    }

    public function convertRequestForm(Request $request)
    {
        logger("ksdjdcbids");
        logger($request->all());

        $purchaseRequest = PurchaseRequest::with('details.product.category')->findOrFail($request->request_id);
        $products = $purchaseRequest->details; // Pass the details as products

        return view('admin.purchase_orders.create', compact('purchaseRequest', 'products'));
    }


    public function create()
    {
        $projects = Project::all();

        $supervisors = User::whereHas('roles', static function ($query) {
            $query->where('name', 'Supervisor');
        })->get();

        $categories = ProductCategory::all();
        $products = Product::all();
        $purchaseRequests = PurchaseRequest::all();

        return view('admin.purchase_orders.create', compact(
            'projects', 'supervisors', 'categories', 'products', 'purchaseRequests'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_request_id' => 'required|exists:purchase_requests,id',
            'project_id' => 'required',
            'supervisor_id' => 'required',
            'order_date' => 'required|date',
            'products' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $order = PurchaseOrder::create([
                'purchase_request_id' => $request->purchase_request_id,
                'project_id' => $request->project_id,
                'supervisor_id' => $request->supervisor_id,
                'order_id' => 'PO-' . strtoupper(Str::random(6)),
                'order_date' => $request->order_date,
                'remarks' => $request->remarks,
            ]);

            foreach ($request->products as $product) {
                $rate = $product['rate'];
                $qty = $product['quantity'];
                $total = $rate * $qty;

                $gst = $product['gst_applicable'] ? ($total * $product['gst_percentage']) / 100 : 0;

                PurchaseOrderDetail::create([
                    'purchase_order_id' => $order->id,
                    'category_id' => $product['category_id'],
                    'product_id' => $product['product_id'],
                    'quantity' => $qty,
                    'rate' => $rate,
                    'gst_applicable' => $product['gst_applicable'],
                    'gst_percentage' => $product['gst_applicable'] ? $product['gst_percentage'] : null,
                    'gst_value' => $gst,
                    'total_amount' => $total,
                    'total_amount_with_gst' => $total + $gst,
                ]);
            }

            DB::commit();
            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order Created Successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function markAsDelivered(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:purchase_order_details,id',
        ]);

        $detail = PurchaseOrderDetail::findOrFail($request->id);

        $detail->status = 'Delivered';
        $detail->remarks = $request->remarks;
        $detail->delivery_date = Carbon::now();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment')->store('po_deliveries', 'public');
            $detail->attachment = $file;
        }

        $detail->save();

        return response()->json(['success' => true, 'message' => 'Marked as Delivered!']);
    }
}
