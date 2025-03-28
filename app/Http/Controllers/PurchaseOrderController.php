<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequest;
use App\Models\Tax;
use App\Models\UnitOfMeasurement;
use App\Models\Warehouse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestStatus\Warning;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('View Purchase Orders');

        if ($request->ajax()) {
            $data = PurchaseOrder::withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('is_active', function ($data) {
                    return $data->is_active ? 'Active' : 'Inactive';
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="d-flex justify-content-center">';
                    if ($data->deleted_at) {
                        $button .= '<a onclick="commonRestore(\'' . route('purchase_orders.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                    } else {
                        $button .= '<a href="' . route('purchase_orders.edit', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil"></i></a>';
                        $button .= '<a onclick="commonDelete(\'' . route('purchase_orders.destroy', $data->id) . '\')" class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></a>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.transactions.purchase_orders.index');
    }

    public function create()
    {
        $this->authorize('Create Purchase Orders');
        $projects = Project::all();
        $productCategories = ProductCategory::where('is_active',1)->get();
        $warehouses = Warehouse::where('is_active',1)->get();
        $taxes = Tax::where('is_active',1)->get();
        $uom = UnitOfMeasurement::where('is_active',1)->get();
        $purchaseRequests = PurchaseRequest::where('status','pending')->get();
        $purchaseOrder = '';
        $orderNo = $this->getPurchaseOrderNo();
        return view('admin.transactions.purchase_orders.data', compact('projects','productCategories','purchaseOrder','warehouses','taxes','uom','purchaseRequests','orderNo'));
    }

    public function store(Request $request)
    {
        $this->authorize('Create Purchase Orders');

        try {
            // Validate the request
            $request->validate([
                'order_no' => 'required|unique:purchase_orders,order_no',
                'order_date' => 'required|date',
                // 'project_id' => 'required|exists:projects,id',
                // 'taxable_amount' => 'required|numeric',
                // 'tax_amount' => 'required|numeric',
                // 'total_amount' => 'required|numeric',
                // 'additional_amount' => 'nullable|numeric',
                // 'net_amount' => 'required|numeric',
                // 'is_secondary' => 'required|boolean',
                'item_data' => 'required|json',
            ]);

            // Create Purchase Order
            $purchaseOrder = PurchaseOrder::create([
                'order_no' => $request->order_no,
                'order_by' => 1,
                'order_date' => $request->order_date,
                'project_id' => $request->project_id,
                'req_id' => $request->req_id,
                'taxable_amount' => $request->taxable_amount,
                'tax_amount' => $request->tax_amount,
                'total_amount' => $request->total_amount,
                'additional_amount' => $request->additional_amount,
                'net_amount' => $request->net_amount,
                'is_secondary' => $request->is_secondary,
                'is_active' => $request->is_active,
            ]);

            // Decode item data JSON
            $items = json_decode($request->item_data, true);

            if (!empty($items)) {
                foreach ($items as $item) {
                    PurchaseOrderItem::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'total_amt' => $item['amount'],
                        'tax_id' => $item['tax_id'],
                        'tax_type' => $item['tax_type'],
                        'taxable' => $item['taxable'],
                        'tax_amt' => $item['tax_amt'],
                        'net_amt' => $item['net_amt'],
                    ]);
                }
            }

            return response()->json([
                'status' => true,
                'message' => "Purchase Order Created Successfully"
            ]);
        } catch (\Exception $exception) {
            $ErrMsg = $exception->getMessage();
            info('Error::PurchaseOrdersController@store - ' . $ErrMsg);

            return response()->json([
                'status' => false,
                'message' => "Something went wrong: " . $ErrMsg
            ], 500);
        }
    }


    public function getPurchaseOrderNo()
    {
        $prefix = 'VKV';
        $year = date('Y');
        
        // Get the last inserted ID from the purchase_orders table
        $lastOrder = PurchaseOrder::latest('id')->first();
        $lastId = $lastOrder ? $lastOrder->id + 1 : 1;

        // Format the order number
        return "{$prefix}-{$year}-PO-{$lastId}";
    }

}
