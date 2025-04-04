<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PurchaseRequestController extends Controller
{
    use AuthorizesRequests;

    /**
     * @param Request $request
     * @return Factory|Application|View|JsonResponse
     * @throws AuthorizationException
     */
    public function index(Request $request): Factory|Application|View|JsonResponse
    {
        $this->authorize('View Purchase Requests');

        if ($request->ajax()) {
            $data = PurchaseRequest::withTrashed()->with(['supervisor', 'project'])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('status', static fn($data) => $data->deleted_at ? 'Deleted' : ucfirst($data->status))
                ->addColumn('action', static function ($data) {
                    $button = '<div class="d-flex justify-content-center">';
                    if ($data->deleted_at) {
                        $button .= '<a onclick="commonRestore(\'' . route('purchase-requests.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                    } else {
                        $button .= '<a href="' . route('purchase-requests.edit', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil"></i></a>';
                        $button .= '<a onclick="commonDelete(\'' . route('purchase-requests.destroy', $data->id) . '\')" class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></a>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.purchase_requests.index');
    }

    /**
     * @return View|Factory|\Illuminate\Foundation\Application
     * @throws AuthorizationException
     */
    public function create(): View|Factory|\Illuminate\Foundation\Application
    {
        $this->authorize('Create Purchase Requests');
        $categories = ProductCategory::whereIsActive(1)->get();
        $projects = Project::whereNot('status', COMPLETED)->get();
        return view('admin.purchase_requests.data', ['purchaseRequest' => '', 'projects' => $projects, 'categories' => $categories]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'products' => 'required|array',
            'products.*.category_id' => 'required|exists:product_categories,id',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0',
        ]);
        $supervisorId = auth()->id();
        DB::beginTransaction();

        try {
            $purchaseRequest = PurchaseRequest::create([
                'supervisor_id' => $supervisorId,
                'project_id' => $request->project_id,
                'product_count' => count($request->products),
                'status' => PENDING,
                'remarks' => $request->remarks ?? null,
            ]);
            foreach ($request->products as $product) {
                PurchaseRequestDetail::create([
                    'purchase_request_id' => $purchaseRequest->id,
                    'category_id' => $product['category_id'],
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                ]);
            }

            DB::commit();
            return redirect()->route('purchase-requests.index')
                ->with('success', 'Purchase request created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create purchase request. ' . $e->getMessage())->withInput();
        }
    }

    /**
     * @param PurchaseRequest $purchaseRequest
     * @return View|Factory|\Illuminate\Foundation\Application
     */
    public function edit(PurchaseRequest $purchaseRequest): View|Factory|\Illuminate\Foundation\Application
    {
        $purchaseRequest->load('details.category', 'details.product', 'project');
        $projects = Project::all();
        return view('admin.purchase_requests.data', compact('purchaseRequest', 'projects'));
    }

    /**
     * @param Request $request
     * @param PurchaseRequest $purchaseRequest
     * @return RedirectResponse
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest): RedirectResponse
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'products' => 'required|array',
            'products.*.category_id' => 'required|exists:product_categories,id',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:0',
        ]);
        DB::beginTransaction();

        try {
            $purchaseRequest->update([
                'project_id' => $request->project_id,
                'product_count' => count($request->products),
                'remarks' => $request->remarks ?? null,
            ]);
            $purchaseRequest->details()->delete();
            foreach ($request->products as $product) {
                PurchaseRequestDetail::create([
                    'purchase_request_id' => $purchaseRequest->id,
                    'category_id' => $product['category_id'],
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                ]);
            }
            DB::commit();

            return redirect()->route('purchase-requests.index')
                ->with('success', 'Purchase request updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating purchase request: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update purchase request. ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function restore($id): JsonResponse
    {
        $this->authorize('Restore Purchase Requests');
        PurchaseRequest::withTrashed()->findOrFail($id)?->restore();
        return response()->json(['success' => 'Purchase Request restored successfully!']);
    }

    /**
     * @param $id
     * @return \Illuminate\Foundation\Application|Response|RedirectResponse|ResponseFactory
     * @throws AuthorizationException
     */
    public function destroy($id): \Illuminate\Foundation\Application|Response|RedirectResponse|ResponseFactory
    {
        $this->authorize('Delete Purchase Requests');
        try {
            $purchaseRequest = PurchaseRequest::findOrFail($id);
            $purchaseRequest->details()->delete();
            $purchaseRequest->delete();
            return response(['status' => 'warning', 'message' => 'Purchase Request deleted Successfully!']);
        } catch (Exception $exception) {
            info('Error::Place@PurchaseRequestController@destroy - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
}
