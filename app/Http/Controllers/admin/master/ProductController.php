<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('View Product');
        if ($request->ajax()) {
            $data = Product::with(['category', 'tax', 'unit'])->withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('is_active', fn($data) => $data->is_active ? 'Active' : 'Inactive')
                ->editColumn('category_id', fn($data) => optional($data->category)->name ?? '-')
                ->editColumn('tax_id', fn($data) => optional($data->tax)->name ?? '-')
                ->editColumn('uom_id', fn($data) => optional($data->unit)->name ?? '-')
                ->addColumn('action', function ($data) {
                    $button = '<div class="d-flex justify-content-center">';
                    if ($data->deleted_at) {
                        $button .= '<a onclick="commonRestore(\'' . route('products.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                    } else {
                        $button .= '<a href="' . route('products.edit', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil"></i></a>';
                        $button .= '<a onclick="commonDelete(\'' . route('products.destroy', $data->id) . '\')" class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></a>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('admin.master.products.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('Create Product');
        return view('admin.master.products.data', ['product' => '']);
    }

    public function store(ProductRequest $request)
    {
        $this->authorize('Create Product');
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }
            Product::create($data);
            return redirect()->route('products.index')->with('success', 'Product added successfully!');
        } catch (\Exception $exception) {
            info('Error::Place@ProductController@store - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $this->authorize('Edit Product');
        return view('admin.master.products.data', compact('product'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->authorize('Edit Product');
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
                logger($data['image']);
            }
            $product->update($data);
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $exception) {
            info('Error::Place@ProductController@update - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        $this->authorize('Delete Product');
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response(['status' => 'warning', 'message' => 'Product deleted Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@ProductController@destroy - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function restore($id)
    {
        $this->authorize('Restore Product');
        try {
            Product::withTrashed()->findOrFail($id)->restore();
            return response(['status' => 'success', 'message' => 'Product restored Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@ProductController@restore - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
}
