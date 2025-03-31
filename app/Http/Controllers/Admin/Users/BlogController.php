<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\Document;
use App\Models\BlogDetail;
use App\Models\SupportType;
use App\Models\User;
use Carbon\Carbon;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use function Laravel\Prompts\warning;

class BlogController extends Controller{
    use AuthorizesRequests;

    /**
     * @param Request $request
     * @return Factory|Application|View|JsonResponse
     * @throws AuthorizationException
     * @throws Exception
     */
    public function index(Request $request): Factory|Application|View|JsonResponse
    {
        $this->authorize('View Blogs');

        if ($request->ajax()) {
            $query = Blog::with('user')->withTrashed()->orderByDesc()
                ->when($request->get('project_id'), static fn($q) => $q->where('project_id', $request->project_id))
                ->when($request->get('stage_id'), static fn($q) => $q->where('stage_id', $request->stage_id))
                ->when($request->get('user_id'), static fn($q) => $q->where('user_id', $request->user_id))
                ->when($request->get('from_date'), static fn($q) => $q->whereDate('created_at', '>=', $request->from_date))
                ->when($request->get('to_date'), static fn($q) => $q->whereDate('created_at', '<=', $request->to_date));

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('user_name', static fn($data) => $data->user?->name)
                ->editColumn('created_on', static fn($data) => Carbon::parse($data->created_at)->format('d-m-Y'))
                ->addColumn('action', static function ($data) {
                    $button = '<div class="d-flex justify-content-center">';
                    if ($data->deleted_at) {
                        $button .= '<a onclick="commonRestore(\'' . route('blogs.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                    } else {
                        $button .= '<a href="' . route('blogs.show', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                        $button .= '<a onclick="commonDelete(\'' . route('blogs.destroy', $data->id) . '\')"  class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></a>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('blogs.index');
    }

    /**
     * @return View|Factory|Application
     * @throws AuthorizationException
     */
    public function create(): View|Factory|Application
    {
        $this->authorize('Create Blogs');
        return view('blogs.data', ['blog' => '']);
    }

    /**
     * @param BlogRequest $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(BlogRequest $request): RedirectResponse
    {
        $this->authorize('Create Blogs');
        DB::beginTransaction();
        try {
            $blog = Blog::create(
                [
                'user_id'       => $request->user_id,
                'stage_ids'       => $request->stage_ids
            ]);
            foreach($request->stage_ids as $stage_id) {
                $message = BlogDetail::create([
                    'blog_id' => $blog->id,
                    'project_id' => $request->project_id,
                    'project_stage_id' => $stage_id,
                    'remarks' => $request->remarks,
                    'is_damage' => $request->is_damage,
                ]);
            }
            Document::where('module_name', 'User-Blog')->where('module_id', Auth::id())
                ->update(['module_name' => 'Blog', 'module_id' => $message->id]);
            DB::commit();
            return redirect()->route('blogs.index')->with('success', 'Blog created successfully.');
        } catch (Exception $exception) {
            DB::rollBack();
            $ErrMsg = $exception->getMessage();
            warning('Error::Place@BlogController@store - ' . $ErrMsg);
            return redirect()->back()->withInput()->with("warning", "Something went wrong" . $ErrMsg);
        }
    }

    /**
     * @param Blog $blog
     * @return View|Factory|Application
     */
    public function show(Blog $blog): View|Factory|Application
    {
        $user_name = User::find($blog->user_id)->first()?->name;
        return view('blogs.show', compact('blog', 'user_name'));
    }

    /**
     * @param $id
     * @return Application|Response|RedirectResponse|ResponseFactory
     * @throws AuthorizationException
     */
    public function destroy($id): Application|Response|RedirectResponse|ResponseFactory
    {
        $this->authorize('Delete Blogs');
        try {
            $ticket = Blog::findOrFail($id);
            $ticket->delete();
            return response(['status' => 'warning', 'message' => 'Blog deleted Successfully!']);
        } catch (Exception $exception) {
            info('Error::Place@BlogController@destroy - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return Application|Response|RedirectResponse|ResponseFactory
     * @throws AuthorizationException
     */
    public function restore($id): Application|Response|RedirectResponse|ResponseFactory
    {
        $this->authorize('Restore Blogs');
        try {
            Blog::withTrashed()->findOrFail($id)?->restore();
            return response(['status' => 'success', 'message' => 'Blog restored Successfully!']);
        } catch (Exception $exception) {
            info('Error::Place@BlogController@restore - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
}
