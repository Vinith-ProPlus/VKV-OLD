<?php

namespace App\Http\Controllers\Admin\ManageProjects;

use App\Http\Controllers\Controller;
use App\Http\Requests\SitesRequest;
use App\Models\Admin\ManageProjects\ProjectSpecifications;
use App\Models\Admin\ManageProjects\Sites;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SitesController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('View Sites');
        if ($request->ajax()) {
            $data = Sites::withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('is_active', function ($data) {
                    return $data->is_active ? 'Active' : 'Inactive';
                })->addColumn('action', function ($data) {
                    $button = '<div class="d-flex justify-content-center">';
                    if ($data->deleted_at) {
                        $button = '<a onclick="commonRestore(\'' . route('sites.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                    } else {
                        $button .= '<a href="' . route('sites.edit', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                        $button .= '<a onclick="commonDelete(\'' . route('sites.destroy', $data->id) . '\')"  class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></i></a>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.manage_projects.sites.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('Create Sites');
        $projectSpecs = ProjectSpecifications::where('is_active',1)->get();
        $projects = Project::where('is_active', 1)->get();
        return view('admin.manage_projects.sites.data', ['site' => '', 'projects' => $projects, 'projectSpecs' => $projectSpecs]);
    }

    public function store(SitesRequest $request)
    {
        $this->authorize('Create Sites');
        try {
            $request->validate([
                'spec_name' => 'required|unique:sites,spec_name',
                'spec_values' => 'required',
                'is_active' => 'required|boolean',
            ]);

            Sites::create($request->all());
            // return redirect()->route('sites.index')->with('success', 'Site created successfully.');
            return ['status'=>true,'message'=>"Site Created Successfully"];
        } catch (\Exception $exception) {
            $ErrMsg = $exception->getMessage();
            info('Error::Place@SitesController@store - ' . $ErrMsg);
            return redirect()->back()->with("warning", "Something went wrong" . $ErrMsg);
        }
    }

    public function edit(Sites $site)
    {
        $this->authorize('Edit Sites');
        return view('admin.manage_projects.sites.data', compact('site'));
    }

    public function update(SitesRequest $request, Sites $site)
    {
        $this->authorize('Edit Sites');
        try {
            $site->update($request->validated());
            // return redirect()->route('sites.index')->with('success', 'Site updated successfully.');
            return ['status'=>true,'message'=>"Site Updated Successfully"];
        } catch (\Exception $exception) {
            info('Error::Place@SitesController@update - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        $this->authorize('Delete Sites');
        try {
            $site = Sites::findOrFail($id);
            $site->delete();
            return response(['status' => 'warning', 'message' => 'Site deleted Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@SitesController@destroy - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function restore($id)
    {
        $this->authorize('Restore Sites');
        try {
            Sites::withTrashed()->findOrFail($id)->restore();
            return response(['status' => 'success', 'message' => 'Site restored Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@SitesController@restore - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
}
