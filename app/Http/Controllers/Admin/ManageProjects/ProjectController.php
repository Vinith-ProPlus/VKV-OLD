<?php

namespace App\Http\Controllers\Admin\ManageProjects;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller{
    use AuthorizesRequests;
    /**
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('View Projects');
        if ($request->ajax()) {
            $data = Project::withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('is_active', function ($data) {
                    return $data->is_active ? 'Active' : 'Inactive';
                })->addColumn('action', function ($data) {
                    $button = '<div class="d-flex justify-content-center">';
                    if ($data->deleted_at) {
                        $button = '<a onclick="commonRestore(\'' . route('projects.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                    } else {
                        $button .= '<a href="' . route('projects.edit', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                        $button .= '<a onclick="commonDelete(\'' . route('projects.destroy', $data->id) . '\')"  class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></i></a>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.manage_projects.projects.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('Create Projects');
        return view('admin.manage_projects.projects.data', ['project' => '']);
    }
    /**
     * @throws AuthorizationException
     */
    public function store(ProjectRequest $request)
    {
        $this->authorize('Create Projects');
        try {
            Project::create($request->all());
            return redirect()->route('projects.index')->with('success', 'Project created successfully.');
        } catch (\Exception $exception) {
            $ErrMsg = $exception->getMessage();
            info('Error::Place@ProjectController@store - ' . $ErrMsg);
            return redirect()->back()->with("warning", "Something went wrong" . $ErrMsg);
        }
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Project $project)
    {
        $this->authorize('Edit Projects');
        return view('admin.manage_projects.projects.data', compact('project'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $this->authorize('Edit Projects');
        try {
            $project->update($request->validated());
            return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
        } catch (\Exception $exception) {
            info('Error::Place@ProjectController@update - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
    /**
     * @throws AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('Delete Projects');
        try {
            $category = Project::findOrFail($id);
            $category->delete();
            return response(['status' => 'warning', 'message' => 'Project deleted Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@ProjectController@destroy - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
    /**
     * @throws AuthorizationException
     */
    public function restore($id)
    {
        $this->authorize('Restore Projects');
        try {
            Project::withTrashed()->findOrFail($id)?->restore();
            return response(['status' => 'success', 'message' => 'Project restored Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@ProjectController@restore - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
}
