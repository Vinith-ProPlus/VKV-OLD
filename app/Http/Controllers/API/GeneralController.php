<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\admin\ManageProjects\ProjectStage;
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
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class GeneralController extends Controller
{
    use ApiResponse;

    /**
     * @param $query
     * @param Request $request
     * @param array $searchColumns
     * @return mixed
     */
    public function dataFilter($query, Request $request, array $searchColumns = []): mixed
    {
        // Get only non-empty request inputs
        $inputs = collect($request->all())->filter();

        // Search filter
        $query->when($inputs->has('search') && !empty($searchColumns), function ($q) use ($inputs, $searchColumns) {
            $search = $inputs->get('search');
            $q->where(function ($subQuery) use ($searchColumns, $search) {
                foreach ($searchColumns as $column) {
                    $subQuery->orWhere($column, 'like', "%{$search}%");
                }
            });
        });

        // Date filter
        $query->when($inputs->has('created_from') && $inputs->has('created_to'), function ($q) use ($inputs) {
            $q->whereBetween('created_at', [
                Carbon::parse($inputs->get('created_from'))->startOfDay(),
                Carbon::parse($inputs->get('created_to'))->endOfDay()
            ]);
        });

        // Sorting
        $sortBy = $inputs->get('sort_by', 'created_at');
        $sortOrder = $inputs->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $inputs->get('per_page', 10);
        $page = $inputs->get('page', 1);

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Format paginated data into an array.
     *
     * @param $query
     * @return array
     */
    public function dataFormatter($query): array
    {
        return [
            'current_page' => $query->currentPage(),
            'data' => $query->items(),
            'total' => $query->total(),
            'per_page' => $query->perPage(),
            'last_page' => $query->lastPage(),
        ];
    }

    public function getCities(Request $request): JsonResponse
    {
        $query = City::where('is_active', 1);

        $query->when($request->filled('district_id'), function ($q) use ($request) {
            $q->where('district_id', $request->district_id);
        });

        $cities = $this->dataFilter($query, $request, ['name']);

        return $this->successResponse($this->dataFormatter($cities), "Cities fetched successfully!");
    }

    public function getStates(Request $request): JsonResponse
    {
        $query = State::where('is_active', 1);

        $states = $this->dataFilter($query, $request, ['name']);

        return $this->successResponse($this->dataFormatter($states), "States fetched successfully!");
    }

    public function getPinCodes(Request $request): JsonResponse
    {
        $query = Pincode::where('is_active', 1);

        $query->when($request->filled('district_id'), function ($q) use ($request) {
            $q->where('district_id', $request->district_id);
        });

        $pinCodes = $this->dataFilter($query, $request, ['pincode']);

        return $this->successResponse($this->dataFormatter($pinCodes), "Pincodes fetched successfully!");
    }


    public function getLeadSource(Request $request): JsonResponse
    {
        $query = LeadSource::where('is_active', 1);

        $states = $this->dataFilter($query, $request, ['name']);

        return $this->successResponse($this->dataFormatter($states), "Lead Source fetched successfully!");
    }

    public function getLeadStatus(Request $request): JsonResponse
    {
        $query = LeadStatus::where('is_active', 1);

        $states = $this->dataFilter($query, $request, ['name']);

        return $this->successResponse($this->dataFormatter($states), "Lead Status fetched successfully!");
    }

    public function getUsers(Request $request): JsonResponse
    {
        $query = User::where('active_status', 'Active');

        $states = $this->dataFilter($query, $request, ['name', 'email']);

        return $this->successResponse($this->dataFormatter($states), "User fetched successfully!");
    }

    public function getRoles(Request $request): JsonResponse
    {
        $query = Role::query();

        $roles = $this->dataFilter($query, $request, ['name']);

        return $this->successResponse($this->dataFormatter($roles), "Roles fetched successfully!");
    }

    public function getProjects(Request $request): JsonResponse
    {
        $query = Project::with('stages');

        $roles = $this->dataFilter($query, $request, ['name']);

        return $this->successResponse($this->dataFormatter($roles), "Project fetched successfully!");
    }

    public function getStages(Request $request): JsonResponse
    {
        $query = ProjectStage::query();

        $query->when($request->filled('project_id'), static function ($q) use ($request) {
            $q->where('project_id', $request->project_id);
        });

        $stages = $this->dataFilter($query, $request, ['name']);

        return $this->successResponse($this->dataFormatter($stages), "Project stages fetched successfully!");
    }

    public function getDistricts(Request $request): JsonResponse
    {
        $query = District::where('is_active', 1);

        $query->when($request->filled('state_id'), static function ($q) use ($request) {
            $q->where('state_id', $request->state_id);
        });

        $districts = $this->dataFilter($query, $request, ['name']);

        return $this->successResponse($this->dataFormatter($districts), "Districts fetched successfully!");
    }

    public function getCategories(Request $request): JsonResponse
    {
        $query = ProductCategory::where('is_active', 1);

        $query = $this->dataFilter($query, $request, ['name']);
        return $this->successResponse($this->dataFormatter($query), "Categories fetched successfully!");
    }

    public function getProducts(Request $request): JsonResponse
    {
        $query = Product::with('category', 'unit')->where('is_active', 1);

        $query->when($request->filled('category_id'), static function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });

        $query = $this->dataFilter($query, $request, ['name', 'code']);

        $query->getCollection()->transform(static function ($product) {
            $product->image = $product->image ? url('storage/' . $product->image) : null;
            return $product;
        });
        return $this->successResponse($this->dataFormatter($query), "Products fetched successfully!");
    }

    public function HomeScreen(Request $request): JsonResponse
    {
        $query = Product::with('category', 'unit')->where('is_active', 1);

        $query->when($request->filled('category_id'), static function ($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });

        $query = $this->dataFilter($query, $request, ['name', 'code']);

        $query->getCollection()->transform(static function ($product) {
            $product->image = $product->image ? url('storage/' . $product->image) : null;
            return $product;
        });
        return $this->successResponse($this->dataFormatter($query), "Products fetched successfully!");
    }


}
