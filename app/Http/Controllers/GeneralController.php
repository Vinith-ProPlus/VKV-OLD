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
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

    public function getDistricts(Request $req)
    {
        $districts = District::where('is_active', '1');

        if ($req->filled('state_id')) {
            $districts->where('state_id', $req->state_id);
        }

        return response()->json($districts->get());
    }

    public function uploadDocuments(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB limit
            'module_name' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('file')) {
                $path = $request->file('file')?->store('projects', 'public');
                $fileName = $request->input('file_name', $request->file('file')?->getClientOriginalName());

                $document = Document::create([
                    'file_path' => $path,
                    'file_name' => $fileName,
                    'uploaded_by' => auth()->id(),
                    'module_name' => $request->module_name ?? 'File',
                    'module_id' => $request->input('module_id', auth()->id()),
                ]);


                DB::commit();
                return response()->json(['success' => true, 'document' => $document]);
            }
            return response()->json(['success' => false]);
        } catch (\Exception $e) {
            info('Error::Place@GeneralController@uploadDocuments - ' . $e->getMessage());
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteDocument(Request $request): JsonResponse
    {
        $request->validate(['id' => 'required|exists:documents,id']);

        DB::beginTransaction();
        try {
            $document = Document::findOrFail($request->id);
            Storage::delete($document->file_path); // Delete file from storage
            $document->delete();

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            info('Error::Place@GeneralController@deleteDocument - ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
