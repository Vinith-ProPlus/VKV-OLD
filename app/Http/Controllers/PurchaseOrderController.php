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
        $purchaseRequest = null;
        $products = collect();
        $project = null;

        if ($request->has('request_id')) {
            $purchaseRequest = PurchaseRequest::with(['project', 'details.product.category'])->findOrFail($request->request_id);
            $products = $purchaseRequest->details;
            $project = $purchaseRequest->project;
        }

        $projects = Project::all(); // For dropdown if manual create
        $categories = ProductCategory::with('products')->get();

        return view('admin.purchase_orders.create', compact(
            'purchaseRequest', 'products', 'project', 'projects', 'categories'
        ));
    }

    public function create(Request $request)
    {
        $purchaseRequest = null;
        $products = collect();
        $project = null;

        if ($request->has('request_id')) {
            $purchaseRequest = PurchaseRequest::with(['project', 'details.product.category'])->findOrFail($request->request_id);
            $products = $purchaseRequest->details;
            $project = $purchaseRequest->project;
        }

        $projects = Project::all(); // For dropdown if manual create
        $categories = ProductCategory::with('products')->get();

        return view('admin.purchase_orders.create', compact(
            'purchaseRequest', 'products', 'project', 'projects', 'categories'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'supervisor_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.category_id' => 'required|exists:product_categories,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.rate' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            // Create purchase request first if not provided
            $purchaseRequestId = $request->purchase_request_id;

            if (empty($purchaseRequestId)) {
                // Create a new purchase request since this is a direct PO creation
                $purchaseRequest = PurchaseRequest::create([
                    'supervisor_id' => $request->supervisor_id,
                    'project_id' => $request->project_id,
                    'product_count' => count($request->products),
                    'remarks' => $request->remarks,
                    'status' => 'Approved', // Auto-approve since we're creating a PO directly
                ]);

                // Create purchase request details
                foreach ($request->products as $product) {
                    PurchaseRequestDetail::create([
                        'purchase_request_id' => $purchaseRequest->id,
                        'category_id' => $product['category_id'],
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity']
                    ]);
                }

                $purchaseRequestId = $purchaseRequest->id;
            }

            // Create purchase order
            $order = PurchaseOrder::create([
                'purchase_request_id' => $purchaseRequestId,
                'project_id' => $request->project_id,
                'supervisor_id' => $request->supervisor_id,
                'order_id' => 'PO-' . strtoupper(Str::random(6)),
                'order_date' => $request->order_date,
                'remarks' => $request->remarks,
                'status' => 'Pending' // Default status for new POs
            ]);

            // Create purchase order details
            $totalAmount = 0;
            $totalGst = 0;
            $totalWithGst = 0;

            foreach ($request->products as $product) {
                $quantity = floatval($product['quantity']);
                $rate = floatval($product['rate']);
                $total = $quantity * $rate;

                $gstApplicable = isset($product['gst_applicable']) && $product['gst_applicable'] == 1;
                $gstPercentage = $gstApplicable ? floatval($product['gst_percentage']) : 0;
                $gstValue = $gstApplicable ? ($total * $gstPercentage / 100) : 0;
                $totalWithGstValue = $total + $gstValue;

                // Update running totals
                $totalAmount += $total;
                $totalGst += $gstValue;
                $totalWithGst += $totalWithGstValue;

                PurchaseOrderDetail::create([
                    'purchase_order_id' => $order->id,
                    'category_id' => $product['category_id'],
                    'product_id' => $product['product_id'],
                    'quantity' => $quantity,
                    'rate' => $rate,
                    'gst_applicable' => $gstApplicable,
                    'gst_percentage' => $gstApplicable ? $gstPercentage : null,
                    'gst_value' => $gstValue,
                    'total_amount' => $total,
                    'total_amount_with_gst' => $totalWithGstValue,
                    'status' => 'Pending', // Default status for new items
                ]);
            }

            DB::commit();
            return redirect()->route('purchase-orders.index')->with('success', 'Purchase Order Created Successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage())->withInput();
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
