<?php

namespace App\Http\Controllers\Admin\Master;


use App\Http\Controllers\Controller;
use App\Http\Requests\PincodeRequest;
use App\Models\Admin\Master\District;
use App\Models\Admin\Master\Pincode;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PincodeController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('View Pincodes');
        if ($request->ajax()) {
        $data = Pincode::withTrashed()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($data) {
                return $data->is_active ? 'Active' : 'Inactive';
            })
            ->addColumn('district_name', fn($data) => $data->district ? $data->district->name : 'N/A')
            ->addColumn('state_name', fn($data) => $data->state ? $data->state->name : 'N/A')
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex justify-content-center">';
                if ($data->deleted_at) {
                    $button = '<a onclick="commonRestore(\'' . route('pincodes.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                } else {
                    $button .= '<a href="' . route('pincodes.edit', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                    $button .= '<a onclick="commonDelete(\'' . route('pincodes.destroy', $data->id) . '\')"  class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></i></a>';
                }
                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.master.pincodes.index');
    }

    public function create()
    {
        $this->authorize('Create Pincodes');
        return view('admin.master.pincodes.data', ['pincode' => '']);
    }

    public function store(PincodeRequest $request)
    {
        $this->authorize('Create Pincodes');
        try {
            Pincode::create($request->all());
            return redirect()->route('pincodes.index')->with('success', 'Pincode created successfully.');
        } catch (\Exception $exception) {
            $ErrMsg = $exception->getMessage();
            info('Error::Place@PincodeController@store - ' . $ErrMsg);
            return redirect()->back()->with("warning", "Something went wrong" . $ErrMsg);
        }
    }

    public function edit(Pincode $pincode)
    {
        $this->authorize('Edit Pincodes');
        return view('admin.master.pincodes.data', compact('pincode'));
    }

    public function update(PincodeRequest $request, Pincode $pincode)
    {
        $this->authorize('Edit Pincodes');
        try {
            $pincode->update($request->validated());
            return redirect()->route('pincodes.index')->with('success', 'Pincode updated successfully.');
        } catch (\Exception $exception) {
            info('Error::Place@PincodeController@update - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        $this->authorize('Delete Pincodes');
        try {
            $category = Pincode::findOrFail($id);
            $category->delete();
            return response(['status' => 'success', 'message' => 'Pincode deleted Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@PincodeController@destroy - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function restore($id)
    {
        $this->authorize('Restore Pincodes');
        try {
            Pincode::withTrashed()->findOrFail($id)?->restore();
            return response(['status' => 'success', 'message' => 'Pincode restored Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@PincodeController@restore - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
}
