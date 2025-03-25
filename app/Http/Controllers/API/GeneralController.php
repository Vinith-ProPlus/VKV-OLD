<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectTaskRequest;
use App\Models\Admin\ManageProjects\ProjectStage;
use App\Models\Admin\ManageProjects\ProjectTask;
use App\Models\Admin\Master\City;
use App\Models\Admin\Master\District;
use App\Models\Admin\Master\Pincode;
use App\Models\Admin\Master\State;
use App\Models\LeadSource;
use App\Models\LeadStatus;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use function Laravel\Prompts\warning;

class GeneralController extends Controller
{
    use ApiResponse;


    public function getCities(Request $request): JsonResponse
    {
        $query = City::where('is_active', 1);

        $query->when($request->filled('district_id'), function ($q) use ($request) {
            $q->where('district_id', $request->district_id);
        });

        $cities = dataFilter($query, $request, ['name']);

        return $this->successResponse(dataFormatter($cities), "Cities fetched successfully!");
    }

    public function getStates(Request $request): JsonResponse
    {
        $query = State::where('is_active', 1);

        $states = dataFilter($query, $request, ['name']);

        return $this->successResponse(dataFormatter($states), "States fetched successfully!");
    }

    public function getPinCodes(Request $request): JsonResponse
    {
        $query = Pincode::where('is_active', 1);

        $query->when($request->filled('city_id'), function ($q) use ($request) {
            $q->where('city_id', $request->city_id);
        });

        $pinCodes = dataFilter($query, $request, ['pincode']);

        return $this->successResponse(dataFormatter($pinCodes), "Pincodes fetched successfully!");
    }


    public function getLeadSource(Request $request): JsonResponse
    {
        $query = LeadSource::where('is_active', 1);

        $states = dataFilter($query, $request, ['name']);

        return $this->successResponse(dataFormatter($states), "Lead Source fetched successfully!");
    }

    public function getLeadStatus(Request $request): JsonResponse
    {
        $query = LeadStatus::where('is_active', 1);

        $states = dataFilter($query, $request, ['name']);

        return $this->successResponse(dataFormatter($states), "Lead Status fetched successfully!");
    }

    public function getUsers(Request $request): JsonResponse
    {
        $query = User::where('active_status', 'Active');

        $states = dataFilter($query, $request, ['name', 'email']);

        return $this->successResponse(dataFormatter($states), "User fetched successfully!");
    }

    public function getRoles(Request $request): JsonResponse
    {
        $query = Role::query();

        $roles = dataFilter($query, $request, ['name']);

        return $this->successResponse(dataFormatter($roles), "Roles fetched successfully!");
    }

    public function getProjects(Request $request): JsonResponse
    {
        $query = Project::with('stages');

        $roles = dataFilter($query, $request, ['name']);

        return $this->successResponse(dataFormatter($roles), "Project fetched successfully!");
    }

    public function getStages(Request $request): JsonResponse
    {
        $query = ProjectStage::query();

        $query->when($request->filled('project_id'), static function ($q) use ($request) {
            $q->where('project_id', $request->project_id);
        });

        $stages = dataFilter($query, $request, ['name']);

        return $this->successResponse(dataFormatter($stages), "Project stages fetched successfully!");
    }

    public function getDistricts(Request $request): JsonResponse
    {
        $query = District::where('is_active', 1);

        $query->when($request->filled('state_id'), static function ($q) use ($request) {
            $q->where('state_id', $request->state_id);
        });

        $districts = dataFilter($query, $request, ['name']);

        return $this->successResponse(dataFormatter($districts), "Districts fetched successfully!");
    }

    public function getCategories(Request $request): JsonResponse
    {
        $query = ProductCategory::where('is_active', 1);

        $query = dataFilter($query, $request, ['name']);
        return $this->successResponse(dataFormatter($query), "Categories fetched successfully!");
    }

    public function getProducts(Request $request): JsonResponse
    {
        $query = Product::with('category', 'unit')->where('is_active', 1);

        $query->when($request->filled('category_id'), static function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });

        $query = dataFilter($query, $request, ['name', 'code']);

        $query->getCollection()->transform(static function ($product) {
            $product->image = generate_file_url($product->image);
            return $product;
        });
        return $this->successResponse(dataFormatter($query), "Products fetched successfully!");
    }

    public function getTasks(Request $request): JsonResponse
    {
        $user = auth()->user();
        $userId = $user->id;
        $today = today();
        $tasks = ProjectTask::with('project:id,name')
            ->whereHas('project.site.supervisors', static fn($q) => $q->where('users.id', $userId))
            ->where(static function ($q) use ($today) {
                $q->where(static function ($subQuery) use ($today) {
                    $subQuery->where('date', '<', $today)
                        ->whereIn('status', ['Created', 'In-progress']);
                })->orWhere(static function ($subQuery) use ($today) {
                    $subQuery->where('date', $today)
                        ->whereIn('status', ['Created', 'In-progress', 'Completed']);
                });
            });
        $tasks = dataFilter($tasks, $request);

        $tasks->transform(static function ($task) {
            $task->image = generate_file_url($task->image);
            return $task;
        });
        return $this->successResponse(dataFormatter($tasks), "Tasks fetched successfully!");
    }

    public function createProjectTask(ProjectTaskRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')?->store('project_tasks', 'public');
            }
            $data['created_by_id'] = auth()->id();
            $task = ProjectTask::create($data);
            DB::commit();
            return $this->successResponse(compact('task'), "Task created successfully!");
        } catch (Exception $exception) {
            DB::rollBack();
            $ErrMsg = $exception->getMessage();
            warning('Error::Place@GeneralController@createProjectTask - ' . $ErrMsg);
            return $this->errorResponse($ErrMsg, "Task creation failed!", 500);
        }
    }

    public function HomeScreen(): JsonResponse
    {
        $user = auth()->user();
        $user->role_name = Role::find($user->role_id)->name ?? 'N/A';
        $user->image = generate_file_url($user->image);

        $userId = $user->id;
        $query = ProjectTask::with('project:id,name')
            ->whereHas('project.site.supervisors', static fn($q) => $q->where('users.id', $userId))
            ->whereDate('date', today());

        $today_tasks = (clone $query)->limit(2)->get();
        $today_tasks->transform(static function ($today_task) {
            $today_task->image = generate_file_url($today_task->image);
            return $today_task;
        });
        $total_today_task = $query->count();
        $notification_count = 0;

        return $this->successResponse(
            compact('user', 'today_tasks', 'total_today_task', 'notification_count'),
            "Home Screen data fetched successfully!"
        );
    }


}
