<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseRequestController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('View Purchase Requests');

        if ($request->ajax()) {
            $data = PurchaseRequest::withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('is_active', function ($data) {
                    return $data->is_active ? 'Active' : 'Inactive';
                })
                ->addColumn('action', function ($data) {
                    $button = '<div class="d-flex justify-content-center">';
                    if ($data->deleted_at) {
                        $button .= '<a onclick="commonRestore(\'' . route('purchase_requests.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                    } else {
                        $button .= '<a href="' . route('purchase_requests.edit', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil"></i></a>';
                        $button .= '<a onclick="commonDelete(\'' . route('purchase_requests.destroy', $data->id) . '\')" class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></a>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.transactions.purchase_requests.index');
    }

    public function create()
    {
        $products = Product::all();
        $categories = ProductCategory::whereNull('deleted_at')->where('is_active',1)->get();
        return view('admin.transactions.purchase_requests.data', compact('products','categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'request_number' => 'required|unique:purchase_requests',
            'request_date' => 'required|date',
            'requested_by' => 'required|string',
            'products.*.name' => 'required|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        $purchaseRequest = PurchaseRequest::create($request->only('request_number', 'request_date', 'requested_by', 'status'));

        foreach ($request->products as $product) {
            PurchaseRequestItem::create([
                'purchase_request_id' => $purchaseRequest->id,
                'product_name' => $product['name'],
                'quantity' => $product['quantity'],
                'unit_price' => $product['unit_price'],
                'total_price' => $product['quantity'] * $product['unit_price'],
            ]);
        }

        return redirect()->route('purchase_requests.index')->with('success', 'Purchase Request created successfully.');
    }

    public function show(PurchaseRequest $purchaseRequest)
    {
        return view('purchase_requests.show', compact('purchaseRequest'));
    }

    public function edit(PurchaseRequest $purchaseRequest)
    {
        return view('purchase_requests.edit', compact('purchaseRequest'));
    }

    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        $request->validate([
            'request_date' => 'required|date',
            'requested_by' => 'required|string',
        ]);

        $purchaseRequest->update($request->only('request_date', 'requested_by', 'status'));

        return redirect()->route('purchase_requests.index')->with('success', 'Purchase Request updated successfully.');
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->delete();
        return redirect()->route('purchase_requests.index')->with('success', 'Purchase Request deleted successfully.');
    }
}
