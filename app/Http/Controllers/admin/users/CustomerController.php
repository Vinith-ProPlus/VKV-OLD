<?php

namespace App\Http\Controllers\Admin\Users;

use App\Models\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('View Customers');
        if ($request->ajax()) {
        $data = Customer::whereLoginType('Customer')->withTrashed()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($data) {
                return $data->is_active ? 'Active' : 'Inactive';
            })
            ->addColumn('city_name', fn($data) => $data->city ? $data->city->name : 'N/A')
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex justify-content-center">';
                if ($data->deleted_at) {
                    $button = '<a onclick="commonRestore(\'' . route('customers.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                } else {
                    $button .= '<a href="' . route('customers.edit', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                    $button .= '<a onclick="commonDelete(\'' . route('customers.destroy', $data->id) . '\')"  class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></i></a>';
                }
                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.customers.index');
    }

    public function create()
    {
        $this->authorize('Create Customers');
        return view('admin.customers.data', ['customer' => '']);
    }

    public function store(CustomerRequest $request)
    {
        $this->authorize('Create Customers');
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $data['login_type'] = 'Customer';
            Customer::create($data);

            return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
        } catch (\Exception $exception) {
            $ErrMsg = $exception->getMessage();
            info('Error::Place@CustomerController@store - ' . $ErrMsg);
            return redirect()->back()->with("warning", "Something went wrong: " . $ErrMsg);
        }
    }

    public function edit(Customer $customer)
    {
        $this->authorize('Edit Customers');
        return view('admin.customers.data', compact('customer'));
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $this->authorize('Edit Customers');
        try {
            $data = $request->validated();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $customer->update($data);

            return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
        } catch (\Exception $exception) {
            info('Error::Place@CustomerController@update - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong: " . $exception->getMessage());
        }
    }
    public function destroy($id)
    {
        $this->authorize('Delete Customers');
        try {
            $category = Customer::findOrFail($id);
            $category->delete();
            return response(['status' => 'success', 'message' => 'Customer deleted Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@CustomerController@destroy - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
    public function restore($id)
    {
        $this->authorize('Restore Customers');
        try {
            Customer::withTrashed()->findOrFail($id)?->restore();
            return response(['status' => 'success', 'message' => 'Customer restored Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@CustomerController@restore - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
}
