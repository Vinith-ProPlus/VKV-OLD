<?php

namespace App\Http\Controllers\Admin\ManageProjects;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Admin\ManageProjects\ProjectStage;
use App\Models\Document;
use App\Models\Project;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProjectController extends Controller{
    use AuthorizesRequests;
    /**
     * @throws AuthorizationException
     */
    public function index(Request $request): Factory|Application|View|JsonResponse
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
    public function create(): View|Factory|Application
    {
        $this->authorize('Create Projects');
        return view('admin.manage_projects.projects.data', ['project' => '']);
    }
    /**
     * @throws AuthorizationException
     */
    public function store(ProjectRequest $request): RedirectResponse
    {
        $this->authorize('Create Projects');
        try {
            $project = Project::create($request->all());

            // Save Stages
            foreach ($request->stages as $stage) {
                ProjectStage::create([
                    'project_id' => $project->id,
                    'name' => $stage['name'],
                    'order_no' => $stage['order_no'],
                ]);
            }

            return redirect()->route('projects.index')->with('success', 'Project created successfully.');
        } catch (Exception $exception) {
            $ErrMsg = $exception->getMessage();
            info('Error::Place@ProjectController@store - ' . $ErrMsg);
            return redirect()->back()->withInput()->with("warning", "Something went wrong" . $ErrMsg);
        }
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Project $project): View|Factory|Application
    {
        $this->authorize('Edit Projects');
        return view('admin.manage_projects.projects.data', compact('project'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $this->authorize('Edit Projects');
        try {
            $project_id = $project->id;
            $project->update($request->validated());

            $existingStages = ProjectStage::where('project_id', $project_id)->withTrashed()->get();

            $newStages = collect($request->stages);
            $existingStageIds = $existingStages->pluck('id')->toArray();

            foreach ($newStages as $stageData) {
                $stageId = $stageData['id'] ?? null;

                if ($stageId && in_array($stageId, $existingStageIds)) {
                    $stage = $existingStages->find($stageId);
                    if ($stage->trashed()) {
                        $stage->restore();
                    }
                    $stage->update([
                        'name' => $stageData['name'],
                        'order_no' => $stageData['order_no'],
                    ]);
                    $stageData['deleted'] ? $stage->delete() : $stage->restore();
                } else {
                    ProjectStage::create([
                        'project_id' => $project_id,
                        'name' => $stageData['name'],
                        'order_no' => $stageData['order_no'],
                    ]);
                }
            }

            // Soft delete missing stages
            foreach ($existingStages as $stage) {
                if (!$newStages->pluck('id')->contains($stage->id)) {
                    $stage->delete();
                }
            }

            return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
        } catch (Exception $exception) {
            info('Error::Place@ProjectController@update - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
    /**
     * @throws AuthorizationException
     */
    public function destroy($id): Application|Response|RedirectResponse|ResponseFactory
    {
        $this->authorize('Delete Projects');
        try {
            $category = Project::findOrFail($id);
            $category->delete();
            return response(['status' => 'warning', 'message' => 'Project deleted Successfully!']);
        } catch (Exception $exception) {
            info('Error::Place@ProjectController@destroy - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
    /**
     * @throws AuthorizationException
     */
    public function restore($id): Application|Response|RedirectResponse|ResponseFactory
    {
        $this->authorize('Restore Projects');
        try {
            Project::withTrashed()->findOrFail($id)?->restore();
            return response(['status' => 'success', 'message' => 'Project restored Successfully!']);
        } catch (Exception $exception) {
            info('Error::Place@ProjectController@restore - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function docxHandler(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'images.*' => 'required|file|max:10240|mimes:png,jpg,jpeg,pdf,docx,xls,webp'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 400);
            }

            $uploadedFiles = [];
            $title = $request->input('title', 'Untitled Document');
            $description = $request->input('description', '');
            $moduleName = $request->input('module_name', 'Project');

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    // Generate a unique filename
                    $filename = uniqid('', true) . '_' . $file->getClientOriginalName();

                    // Store file in public disk
                    $path = $file->storeAs('project_documents', $filename, 'public');

                    // Create document record
                    $document = Document::create([
                        'title' => $title,
                        'description' => $description,
                        'module_name' => $moduleName,
                        'module_id' => auth()->id(),
                        'file_path' => $path,
                        'file_name' => $filename,
                        'uploaded_by' => auth()->id()
                    ]);

                    $uploadedFiles[] = [
                        'id' => $document->id,
                        'name' => $filename,
                        'path' => Storage::url($path),
                        'extension' => $file->getClientOriginalExtension()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles
            ]);
        } catch (\Exception $e) {

            \Log::error('Document upload error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during upload',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteDocx(Request $request)
    {
        $ids = $request->input('id');

        if (!$ids || !is_array($ids)) {
            return response()->json(['message' => 'Invalid request data'], 400);
        }

        // Fetch all documents that match the given IDs
        $documents = Document::whereIn('id', $ids)->get();

        if ($documents->isEmpty()) {
            return response()->json(['message' => 'Documents not found'], 404);
        }

        $deletedFiles = [];
        foreach ($documents as $document) {
            $filePath = 'project_documents/' . $document->file_name;

            // Soft delete document
            $document->delete();

            // Delete file from storage if it exists
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
                $deletedFiles[] = $document->file_name;
            }
        }

        return response()->json([
            'message' => 'Documents deleted successfully',
            'deleted_files' => $deletedFiles
        ]);
    }

}
