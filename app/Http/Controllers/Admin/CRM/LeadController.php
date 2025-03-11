<?php

namespace App\Http\Controllers\Admin\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeadRequest;
use App\Models\Lead;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('View Lead');

        if ($request->ajax()) {
            $data = Lead::withTrashed()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('is_active', fn($data) => $data->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', function ($data) {
                    $button = '<div class="d-flex justify-content-center">';
                    if ($data->deleted_at) {
                        $button .= '<a onclick="commonRestore(\'' . route('leads.restore', $data->id) . '\')" class="btn btn-outline-warning"><i class="fa fa-undo"></i></a>';
                    } else {
                        $button .= '<a href="' . route('leads.edit', $data->id) . '" class="btn btn-outline-success btn-sm m-1"><i class="fa fa-pencil"></i></a>';
                        $button .= '<a onclick="commonDelete(\'' . route('leads.destroy', $data->id) . '\')" class="btn btn-outline-danger btn-sm m-1"><i class="fa fa-trash" style="color: red"></i></a>';
                    }
                    $button .= '</div>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.crm.leads.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('Create Lead');
        return view('admin.crm.leads.data', ['leads' => '']);
    }

    public function store(LeadRequest $request)
    {
        $this->authorize('Create Lead');
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('leads', 'public');
            }

            Lead::create($data);
            DB::commit();
            return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $ErrMsg = $exception->getMessage();
            info('Error::Place@LeadController@store - ' . $ErrMsg);
            return redirect()->back()->with("warning", "Something went wrong" . $ErrMsg);
        }
    }

    public function edit(Lead $leads)
    {
        $this->authorize('Edit Lead');
        return view('admin.crm.leads.data', compact('leads'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(LeadRequest $request, Lead $leads)
    {
        $this->authorize('Edit Lead');
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                $oldImage = $leads->image;
                $newImage = $data['image'] = $request->file('image')->store('leads', 'public');
            }

            $leads->update($data);
            DB::commit();
            if (isset($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            if(isset($newImage)){
                Storage::disk('public')->delete($newImage);
            }
            info('Error::Place@LeadController@update - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy($id)
    {
        $this->authorize('Delete Lead');
        try {
            $leads = Lead::findOrFail($id);
            $leads->delete();
            return response(['status' => 'warning', 'message' => 'Lead deleted Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@LeadController@destroy - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }

    /**
     * @throws AuthorizationException
     */
    public function restore($id)
    {
        $this->authorize('Restore Lead');
        try {
            Lead::withTrashed()->findOrFail($id)?->restore();
            return response(['status' => 'success', 'message' => 'Lead restored Successfully!']);
        } catch (\Exception $exception) {
            info('Error::Place@LeadController@restore - ' . $exception->getMessage());
            return redirect()->back()->with("warning", "Something went wrong" . $exception->getMessage());
        }
    }
}
