<?php

namespace App\Http\Controllers\Admin\ManageProjects;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectLaborDateRequest;
use App\Models\ProjectLaborDate;
use App\Models\Labor;
use App\Models\ContractLabor;
use App\Models\Project;
use App\Models\ProjectContract;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ProjectLaborDateController extends Controller
{
    use AuthorizesRequests;

    /**
     * @throws AuthorizationException
     */
    public function index(Request $request): Factory|Application|View|JsonResponse
    {
        $this->authorize('View Labors');
        if ($request->ajax()) {
            $data = ProjectLaborDate::with(['project', 'labors', 'contractLabors'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('project_name', static fn($data) => $data->project->name ?? 'N/A')
                ->addColumn('labor_count', static fn($data) => $data->labors->count())
                ->addColumn('contract_labor_count', static fn($data) => $data->contractLabors->sum('count'))
                ->addColumn('action', static function ($data) {
                    $button = '<div class="d-flex justify-content-center">';
                    if ($data->deleted_at) {
                        $button .= '<a onclick="commonRestore(\'' . route('labors.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                    } else {
                        $button .= '<a href="' . route('labors.create', ['project_id' => $data->project_id, 'date' => $data->date]) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                        $button .= '<a onclick="commonDelete(\'' . route('labors.destroy', $data->id) . '\')"  class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></a>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.manage_projects.labors.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function laborsList(Request $request): Factory|Application|View|JsonResponse
    {
        if ($request->ajax()) {
            $this->authorize('View Labors');
            $project_labor_date_id = $request->project_labor_date_id;
            if (!$project_labor_date_id) {
                return response()->json(['error' => 'Invalid request: Missing project labor date ID.'], 400);
            }
            $project_labor_date = ProjectLaborDate::find($project_labor_date_id);
            if (!$project_labor_date) {
                return response()->json(['error' => 'Project labor date not found.'], 404);
            }
            $data = Labor::where('project_labor_date_id', $project_labor_date_id)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('salary', static fn($data) => $data->salary ?? 'N/A')
                ->addColumn('action', static function ($data) use ($project_labor_date) {
                    $button = '<div class="d-flex justify-content-center">';
//                    if ($project_labor_date->date->isToday()) {
                        $button .= '<button data-id="' . $data->id . '" data-type="Self" class="btn btn-outline-success btn-sm m-1 editLabor">
                                    <i class="fa fa-pencil" aria-hidden="true"></i></button>';
                        $button .= '<button data-id="' . $data->id . '" data-type="Self" class="btn btn-outline-danger btn-sm m-1 deleteLabor">
                                    <i class="fa fa-trash" style="color: red"></i>
                                </button>';
//                    }

                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return response()->json([]);
    }

    /**
     * @throws AuthorizationException
     */
    public function contractLaborsList(Request $request): Factory|Application|View|JsonResponse
    {
        if ($request->ajax()) {
            $this->authorize('View Labors');
            $project_labor_date_id = $request->project_labor_date_id;
            if (!$project_labor_date_id) {
                return response()->json(['error' => 'Invalid request: Missing project labor date ID.'], 400);
            }

            $project_labor_date = ProjectLaborDate::find($project_labor_date_id);
            if (!$project_labor_date) {
                return response()->json(['error' => 'Project labor date not found.'], 404);
            }

            $data = ContractLabor::with('projectContract.user', 'projectContract.contract_type')
                ->where('project_labor_date_id', $project_labor_date_id)
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('contractor_name', static function ($data) {
                    $contractorName = $data->projectContract?->user?->name ?? 'Unknown Contractor';
                    $contractType = $data->projectContract?->contract_type?->name ?? 'Unknown Type';
                    return "$contractorName - $contractType";
                })
                ->addColumn('action', static function ($data) use ($project_labor_date) {
                    $button = '<div class="d-flex justify-content-center">';
//                    if ($project_labor_date->date->isToday()) {
                        $button .= '<button data-id="' . $data->id . '" data-type="Contract" class="btn btn-outline-success btn-sm m-1 editLabor">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>';
                        $button .= '<button data-id="' . $data->id . '" data-type="Contract" class="btn btn-outline-danger btn-sm m-1 deleteLabor">
                                    <i class="fa fa-trash" style="color: red"></i>
                                </button>';
//                    }

                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return response()->json([]);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(ProjectLaborDateRequest $request): Response
    {
        $this->authorize('Create Labors');
        $labor = ProjectLaborDate::firstOrCreate(
            ['project_id' => $request->project_id, 'date' => $request->date]
        );
        return response()->view('admin.manage_projects.labors.data', compact('labor'));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('Create Labors');
        DB::beginTransaction();
        try {
            $request->validate([
                'project_labor_date_id' => 'required|exists:project_labor_dates,id',
                'labor_type' => 'required|in:Self,Contract',
                'project_contract_id' => [
                    'required_if:labor_type,Contract',
                    static function ($attribute, $value, $fail) use ($request) {
                        $exists = ContractLabor::where('project_labor_date_id', $request->project_labor_date_id)
                            ->where('project_contract_id', $value)
                            ->exists();

                        if ($exists) {
                            $fail('This contractor is already assigned for the selected project labor date.');
                        }
                    },
                ],
                'name' => 'required_if:labor_type,Self',
                'mobile' => [
                    'required_if:labor_type,Self',
                    'digits:10',
                    static function ($attribute, $value, $fail) use ($request) {
                        $exists = Labor::where('project_labor_date_id', $request->project_labor_date_id)
                            ->where('mobile', $value)
                            ->exists();

                        if ($exists) {
                            $fail('This mobile number is already registered for the selected project labor date.');
                        }
                    },
                ],
                'designation' => 'required_if:labor_type,Self',
                'salary' => 'required_if:labor_type,Self|numeric',
                'count' => 'required_if:labor_type,Contract|numeric|min:1',
            ], [
                'project_labor_date_id.required' => 'Please select a valid project labor date.',
                'project_labor_date_id.exists' => 'The selected project labor date does not exist.',
                'labor_type.required' => 'Please select a labor type.',
                'labor_type.in' => 'Invalid labor type selected.',
                'project_contract_id.required_if' => 'Please select a contractor for contract labor.',
                'name.required_if' => 'Please enter the laborer\'s name.',
                'mobile.required_if' => 'Please enter the laborer\'s mobile number.',
                'mobile.digits' => 'The mobile number must be exactly 10 digits.',
                'designation.required_if' => 'Please enter the laborer\'s designation.',
                'salary.required_if' => 'Please enter the salary amount.',
                'salary.numeric' => 'Salary must be a numeric value.',
                'count.required_if' => 'Please enter the number of contract laborers.',
                'count.numeric' => 'The contract labor count must be a number.',
                'count.min' => 'The contract labor count must be at least 1.',
            ]);

            if ($request->labor_type === 'Self') {
                $labor = Labor::create($request->only(['project_labor_date_id', 'name', 'designation', 'mobile', 'salary']));
            } else {
                $labor = ContractLabor::create($request->only(['project_labor_date_id', 'project_contract_id', 'count']));
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Labor added successfully!', 'data' => $labor]);

        } catch (Exception $exception) {
            DB::rollBack();
            Log::error("Error-ProjectLaborDateController@store: " . $exception->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $exception->getMessage()]);
        }
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Request $request): JsonResponse
    {
        $this->authorize('Edit Labors');
        if($request->labor_type === 'Self'){
            $labor = Labor::find($request->id);
        } else {
            $labor = ContractLabor::with('projectContract')->find($request->id);
        }
        return response()->json($labor);
    }

    /**
     * @throws AuthorizationException
     */

    public function update(Request $request, $labor): JsonResponse
    {
        $this->authorize('Edit Labors');
        DB::beginTransaction();

        try {
            if ($request->labor_type === 'Self') {
                $laborModel = Labor::findOrFail($labor);
            } elseif ($request->labor_type === 'Contract') {
                $laborModel = ContractLabor::findOrFail($labor);
            } else {
                throw new \RuntimeException("Invalid labor type");
            }

            $request->validate([
                'project_labor_date_id' => 'required|exists:project_labor_dates,id',
                'labor_type' => 'required|in:Self,Contract',
                'project_contract_id' => [
                    'required_if:labor_type,Contract',
                    static function ($attribute, $value, $fail) use ($request, $laborModel) {
                        $exists = ContractLabor::where('project_labor_date_id', $request->project_labor_date_id)
                            ->where('project_contract_id', $value)
                            ->where('id', '!=', $laborModel->id)
                            ->exists();
                        if ($exists) {
                            $fail('This contractor is already assigned to the selected project labor date.');
                        }
                    },
                ],
                'name' => 'required_if:labor_type,Self',
                'mobile' => [
                    'required_if:labor_type,Self',
                    'digits:10',
                    static function ($attribute, $value, $fail) use ($request, $laborModel) {
                        $exists = Labor::where('project_labor_date_id', $request->project_labor_date_id)
                            ->where('mobile', $value)
                            ->where('id', '!=', $laborModel->id)
                            ->exists();
                        if ($exists) {
                            $fail('This mobile number is already registered for the selected project labor date.');
                        }
                    },
                ],
                'designation' => 'required_if:labor_type,Self',
                'salary' => 'required_if:labor_type,Self|numeric',
                'count' => 'required_if:labor_type,Contract|numeric|min:1',
            ], [
                'project_labor_date_id.required' => 'Please select a valid project labor date.',
                'project_labor_date_id.exists' => 'The selected project labor date does not exist.',
                'labor_type.required' => 'Please select a labor type.',
                'labor_type.in' => 'Invalid labor type selected.',
                'project_contract_id.required_if' => 'Please select a contractor for contract labor.',
                'name.required_if' => 'Please enter the laborer\'s name.',
                'mobile.required_if' => 'Please enter the laborer\'s mobile number.',
                'mobile.digits' => 'The mobile number must be exactly 10 digits.',
                'designation.required_if' => 'Please enter the laborer\'s designation.',
                'salary.required_if' => 'Please enter the salary amount.',
                'salary.numeric' => 'Salary must be a numeric value.',
                'count.required_if' => 'Please enter the number of contract laborers.',
                'count.numeric' => 'The contract labor count must be a number.',
                'count.min' => 'The contract labor count must be at least 1.',
            ]);

            // Update logic
            if ($request->labor_type === 'Self') {
                $laborModel->update([
                    'name' => $request->name,
                    'designation' => $request->designation,
                    'mobile' => $request->mobile,
                    'salary' => $request->salary,
                ]);
            } elseif ($request->labor_type === 'Contract') {
                $laborModel->update([
                    'project_contract_id' => $request->project_contract_id,
                    'count' => $request->count,
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Labor updated successfully']);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error("Error-ProjectLaborDateController@update: " . $exception->getMessage());
            return response()->json(['success' => false, 'message' => 'Labor update failed']);
        }
    }


    /**
     * @throws AuthorizationException
     */
    public function destroy($labor): JsonResponse
    {
        $this->authorize('Delete Labors');
        try {
            $labor = Labor::find($labor) ?? ContractLabor::find($labor);
            if ($labor) {
                $labor->delete();
                return response()->json(['success' => 'Labor deleted successfully']);
            }

            return response()->json(['error' => 'Labor not found'], 404);
        } catch (Exception $exception) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong: ' . $exception->getMessage()]);
        }
    }
    /**
     * @throws AuthorizationException
     */
    public function restore($id): Application|Response|RedirectResponse|ResponseFactory
    {
        $this->authorize('Restore Labors');
        try {
            ProjectLaborDate::withTrashed()->findOrFail($id)?->restore();
            return response(['status' => 'success', 'message' => 'Labour date restored Successfully!']);
        } catch (Exception $exception) {
            info('Error::Place@ProjectLaborDateController@restore - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    public function addLabor(Request $request): JsonResponse
    {
        try {
            if ($request->labor_type === 'Self') {
                $labor = Labor::create($request->only(['project_labor_date_id', 'name', 'designation', 'mobile', 'salary']));
            } else {
                $labor = ContractLabor::create($request->only(['project_labor_date_id', 'project_contract_id', 'count']));
            }
            return response()->json(['status' => 'success', 'message' => 'Labor added successfully!', 'data' => $labor]);
        } catch (Exception $exception) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong: ' . $exception->getMessage()]);
        }
    }
}
