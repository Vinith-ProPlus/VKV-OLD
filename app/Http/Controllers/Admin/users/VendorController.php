<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('View Vendors');
        if ($request->ajax()) {
        $data = Vendor::whereLoginType('Vendor')->withTrashed()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($data) {
                return $data->is_active ? 'Active' : 'Inactive';
            })
            ->addColumn('city_name', fn($data) => $data->city ? $data->city->name : 'N/A')
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex justify-content-center">';
                if ($data->deleted_at) {
                    $button = '<a onclick="commonRestore(\'' . route('vendors.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                } else {
                    $button .= '<a href="' . route('vendors.edit', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                    $button .= '<a onclick="commonDelete(\'' . route('vendors.destroy', $data->id) . '\')"  class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></i></a>';
                }
                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.vendors.index');
    }

    public function create()
    {
        $this->authorize('Create Vendors');
        return view('admin.vendors.data', ['vendor' => '']);
    }

    public function store(VendorRequest $request)
    {
        $this->authorize('Create Vendors');
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $data['login_type'] = 'Vendor';
            Vendor::create($data);

            return redirect()->route('vendors.index')->with('success', 'Vendor created successfully.');
        } catch (\Exception $exception) {
            $ErrMsg = $exception->getMessage();
            info('Error::Place@VendorController@store - ' . $ErrMsg);
            return redirect()->back()->with("warning", "Something went wrong: " . $ErrMsg);
        }
    }

    public function edit(Vendor $vendor)
    {
        $this->authorize('Edit Vendors');
        return view('admin.vendors.data', compact('vendor'));
    }

    public function update(VendorRequest $request, Vendor $vendor)
    {
        $this->authorize('Edit Vendors');
        try {
            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $vendor->update($data);
            return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully.');
        } catch (\Exception $exception) {
            $ErrMsg = $exception->getMessage();
            info('Error::Place@VendorController@update - ' . $ErrMsg);
            return redirect()->back()->with("warning", "Something went wrong: " . $ErrMsg);
        }
    }
    public function destroy($id)
    {
        $this->authorize('Delete Vendors');
        try {
            $category = Vendor::findOrFail($id);
            $category->delete();
            return response(['status' => 'success', 'message' => 'Vendor deleted Successfully!']);
        } catch (\Exception $exception) {
            $ErrMsg = $exception->getMessage();
            info('Error::Place@VendorController@destroy - ' . $ErrMsg);
            return redirect()->back()->with("warning", "Something went wrong" . $ErrMsg);
        }
    }
    public function restore($id)
    {
        $this->authorize('Restore Vendors');
        try {
            Vendor::withTrashed()->findOrFail($id)?->restore();
            return response(['status' => 'success', 'message' => 'Vendor restored Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@VendorController@restore - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
}
