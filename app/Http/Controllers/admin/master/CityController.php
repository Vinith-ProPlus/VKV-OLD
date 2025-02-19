<?php

namespace App\Http\Controllers\Admin\Master;


use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\Admin\Master\City;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class CityController extends Controller
{
    use AuthorizesRequests; 
    public function index(Request $request)
    {
        $this->authorize('View Cities');
        if ($request->ajax()) {
        $data = City::withTrashed()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('is_active', function ($data) {
                return $data->is_active ? 'Active' : 'Inactive';
            })
            ->addColumn('district_name', fn($data) => $data->district ? $data->district->name : 'N/A')
            ->addColumn('action', function ($data) {
                $button = '<div class="d-flex justify-content-center">';
                if ($data->deleted_at) {
                    $button = '<a onclick="commonRestore(\'' . route('cities.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                } else {
                    $button .= '<a href="' . route('cities.edit', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                    $button .= '<a onclick="commonDelete(\'' . route('cities.destroy', $data->id) . '\')"  class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></i></a>';
                }
                $button .= '</div>';
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.master.cities.index');
    }
 
    public function create()
    {
        $this->authorize('Create Cities');
        return view('admin.master.cities.data', ['city' => '']);
    }
 
    public function store(CityRequest $request)
    {
        $this->authorize('Create Cities');
        try {
            City::create($request->all());
            return redirect()->route('cities.index')->with('success', 'City created successfully.');
        } catch (\Exception $exception) {
            $ErrMsg = $exception->getMessage();
            info('Error::Place@CityController@store - ' . $ErrMsg);
            return redirect()->back()->with("warning", "Something went wrong" . $ErrMsg);
        }
    } 
 
    public function edit(City $city)
    {
        $this->authorize('Edit Cities');
        return view('admin.master.cities.data', compact('city'));
    }
 
    public function update(CityRequest $request, City $city)
    {
        $this->authorize('Edit Cities');
        try {
            $city->update($request->validated());
            return redirect()->route('cities.index')->with('success', 'City updated successfully.');
        } catch (\Exception $exception) {
            info('Error::Place@CityController@update - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }


    public function destroy($id)
    {
        $this->authorize('Delete Cities');
        try {
            $category = City::findOrFail($id);
            $category->delete();
            return response(['status' => 'success', 'message' => 'City deleted Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@CityController@destroy - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function restore($id)
    {
        $this->authorize('Restore Cities');
        try {
            City::withTrashed()->findOrFail($id)->restore();
            return response(['status' => 'success', 'message' => 'City restored Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@CityController@restore - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
}
