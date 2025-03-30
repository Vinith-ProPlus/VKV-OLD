<?php

namespace App\Http\Controllers;

use App\Models\Admin\ManageProjects\ProjectStage;
use App\Models\Admin\ManageProjects\Site;
use App\Models\Admin\Master\City;
use App\Models\Admin\Master\District;
use App\Models\Admin\Master\Pincode;
use App\Models\Admin\Master\State;
use App\Models\Document;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class GeneralController extends Controller
{
    public function getCities(Request $req)
    {
        $cities = City::where('is_active','1');

        if($req->filled('district_id')){
            $cities->where('district_id', $req->district_id);
        }

        return response()->json($cities->get());
    }

    public function getStates(): JsonResponse
    {
        $state = State::where('is_active','1')->get();
        return response()->json($state);
    }

    public function getPinCodes(Request $request): JsonResponse
    {
        $pincode = Pincode::where('is_active','1');

        if($request->filled('city_id')){
            $pincode->where('city_id', $request->city_id);
        }

        return response()->json($pincode->get());
    }

    public function getLeadSource(): JsonResponse
    {
        return response()->json(LeadSource::where('is_active','1')->get());
    }

    public function getLeadStatus(): JsonResponse
    {
        return response()->json(LeadStatus::where('is_active','1')->get());
    }

    public function getUsers(): JsonResponse
    {
        return response()->json(User::where('active_status','Active')->get());
    }
    public function getSiteSupervisors(): JsonResponse
    {
        $supervisor_role_id = Role::where('name', SITE_SUPERVISOR_ROLE_NAME)->pluck('id')->first();

        if ($supervisor_role_id) {
            $supervisors = User::where('active_status', 'Active')
                ->whereHas('roles', function ($query) use ($supervisor_role_id) {
                    $query->where('role_id', $supervisor_role_id);
                })
                ->get();
            return response()->json($supervisors);
        }

        return response()->json([]);
    }
    public function getEngineers(): JsonResponse
    {
        $engineer_role_id = Role::where('name', ENGINEER_ROLE_NAME)->pluck('id')->first();

        if ($engineer_role_id) {
            $engineers = User::where('active_status', 'Active')
                ->whereHas('roles', static function ($query) use ($engineer_role_id) {
                    $query->where('role_id', $engineer_role_id);
                })
                ->get();
            return response()->json($engineers);
        }

        return response()->json([]);
    }

    public function getRoles(): JsonResponse
    {
        return response()->json(Role::all());
    }

    public function getProjects(): JsonResponse
    {
        return response()->json(Project::all());
    }
    public function getStages(Request $request)
    {
        if ($request->filled('ProjectID')) {
            return response()->json(ProjectStage::where('project_id', $request->ProjectID)->get());
        }

        return response()->json([]);
    }

    public function getSites()
    {
            return response()->json(Site::where('is_active', 1)->get());
    }
    public function getProducts(Request $req)
    {
        $products = Product::where('products.is_active', '1')
        ->leftJoin('unit_of_measurements as uom', 'products.uom_id', 'uom.id')
        ->select('products.*','uom.name as uom_name','uom.code as uom_code');

        if ($req->filled('category_id')) {
            $products->where('category_id', $req->category_id);
        }

        return response()->json($products->get());
    }

    public function getDistricts(Request $req)
    {
        $districts = District::where('is_active', '1');

        if ($req->filled('state_id')) {
            $districts->where('state_id', $req->state_id);
        }

        return response()->json($districts->get());
    }

    public function getDocuments(Request $request): JsonResponse
    {
        $documents = Document::where('module_name', $request->module_name)
            ->where('module_id', $request->module_id)
            ->get()
            ->map(static function ($document) {
                return [
                    'id' => $document->id,
                    'title' => $document->title,
                    'description' => $document->description ?? '',
                    'images' => [
                        [
                            'filename' => $document->file_name,
                            'url' => asset("storage/$document->file_path"),
                        ]
                    ]
                ];
            });

        return response()->json($documents);
    }


    public function documentHandler(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'module_name' => 'required|string',
                'module_id' => 'required|integer',
                'images.*' => 'required|file|max:10240|mimes:png,jpg,jpeg,pdf,docx,xls,webp'
            ]);

            $uploadedFiles = [];
            $title = $request->input('title', 'Untitled Document');
            $description = $request->input('description', '');

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $filename = uniqid('', true) . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('documents', $filename, 'public');

                    $document = Document::create([
                        'title' => $title,
                        'description' => $description,
                        'module_name' => $validated['module_name'],
                        'module_id' => $validated['module_id'],
                        'file_path' => $path,
                        'file_name' => $filename,
                        'uploaded_by' => auth()->id()
                    ]);

                    $uploadedFiles[] = [
                        'id' => $document->id,
                        'name' => $filename,
                        'path' => asset("storage/$path"),
                        'extension' => $file->getClientOriginalExtension()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'files' => $uploadedFiles
            ]);
        } catch (\Throwable $exception) {
            Log::error("Error::Place@GeneralController@documentHandler - " . $exception->getMessage());
            return response()->json([
                'success' => false,'message' => 'An error occurred during upload',
                'error' => $exception->getMessage()
            ], 500);
        }
    }
    public function updateDocuments(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'documentIds' => 'required',
                'title' => 'required|string',
                'description' => 'nullable|string',
                'deletedDocuments' => 'nullable',
            ]);
            $documentIds = is_array($request->documentIds) ? $request->documentIds : [$request->documentIds];
            $deletedDocuments = is_array($request->deletedDocuments) ? $request->deletedDocuments : [$request->deletedDocuments];

            Document::whereIn('id', $documentIds)->update([
                'title' => $request->input('title'),
                'description' => $request->input('description', ''),
            ]);

            if (!empty($deletedDocuments)) {
                $documents = Document::whereIn('id', $deletedDocuments)->get();
                foreach ($documents as $document) {
                    $filePath = 'project_documents/' . $document->file_name;
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                    $document->delete();
                }
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error::GeneralController@updateDocuments - ' . $exception->getMessage());
            return response()->json([
                'success' => false,'message' => 'An error occurred during update',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    public function deleteDocuments(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required',
            ]);
            $documentIds = is_array($request->id) ? $request->id : [$request->id];
            $documents = Document::whereIn('id', $documentIds)->get(['id', 'file_path']);
            if ($documents->isEmpty()) {
                return response()->json(['message' => 'No documents found to delete'], 404);
            }
            $deletedFiles = $documents->pluck('file_path')->toArray();
            Document::whereIn('id', $documentIds)->delete();
            Storage::disk('public')->delete($deletedFiles);
            DB::commit();
            return response()->json([
                'message' => 'Documents deleted successfully'
            ]);
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error("Error::Place@GeneralController@deleteDocuments - " . $exception->getMessage());
            return response()->json([
                'success' => false, 'message' => 'An error occurred during delete',
                'error' => $exception->getMessage()
            ], 500);
        }
    }



}
