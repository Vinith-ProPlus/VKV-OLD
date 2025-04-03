<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin\ManageProjects\ProjectTask;
use App\Models\Blog;
use App\Models\Document;
use App\Models\ProjectLaborDate;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use function Laravel\Prompts\warning;

class LaborController extends Controller
{
    use ApiResponse;
    public function createBlog(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'remarks'      => 'required|string|max:500',
                'project_id' => 'required|exists:projects,id',
                'stage_ids' => 'required',
            ]);

            $attachments = [];
            if ($request->hasFile('attachments')) {
                $files = is_array($request->file('attachments')) ? $request->file('attachments') : [$request->file('attachments')];
                foreach ($files as $file) {
                    $filename = generateUniqueFileName($file);
                    $path = $file->storeAs('documents', $filename, 'public');
                    $attachments[] = [
                        'title'       => 'Blog Attachment',
                        'description' => '',
                        'module_name' => 'Blog',
                        'file_path'   => $path,
                        'file_name'   => $filename,
                        'uploaded_by' => Auth::id(),
                    ];
                }
            }

            foreach ($request->stage_ids as $stage_id) {
                $blog = Blog::create([
                    'user_id'          => Auth::id(),
                    'project_id'       => $request->project_id,
                    'project_stage_id' => $stage_id,
                    'remarks'          => $request->remarks,
                    'is_damaged'        => $request->is_damaged ?? 0,
                ]);

                foreach ($attachments as $attachment) {
                    Document::create(array_merge($attachment, ['module_id' => $blog->id]));
                }
            }
            DB::commit();
            return $this->successResponse($blog, "Blog created successfully!");
        } catch (Exception $exception) {
            DB::rollBack();
            $ErrMsg = $exception->getMessage();
            warning('Error::Place@Api\BlogController@store - ' . $ErrMsg);
            return $this->errorResponse($exception->getMessage(), "Failed to create blog!", 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getLaborDates(Request $request): JsonResponse
    {
        $request->validate([
            'project_id' => 'required|integer|exists:projects,id'
        ]);

        $projectId = $request->input('project_id');

        ProjectLaborDate::firstOrCreate([
            'project_id' => $projectId,
            'date' => today()->format('Y-m-d'),
        ]);

        $query = ProjectLaborDate::where('project_id', $projectId)
            ->withCount(['labors as labor_count']);
        $query = dataFilter($query, $request);
        $query->getCollection()->transform(function ($date) {
            return [
                'id' => $date->id,
                'date' => Carbon::parse($date->date)->format('d/m/Y'),
                'labor_count' => $date->labor_count
            ];
        });

        return $this->successResponse(dataFormatter($query), "Labor dates fetched successfully!");
    }

    public function getLaborData(Request $request): JsonResponse
    {
        $request->validate([
            'project_labor_date_id' => 'required|integer|exists:project_labor_dates,id',
        ]);

        $projectLaborDate = ProjectLaborDate::with(['labors', 'contractLabors.projectContract'])
            ->findOrFail($request->input('project_labor_date_id'));
        $projectLaborDate->count = $projectLaborDate->labor_count + $projectLaborDate->contract_count;

        return $this->successResponse($projectLaborDate, "Labor data fetched successfully!");
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getCompletedTaskData(Request $request): JsonResponse
    {
        $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'stage_id'   => 'required|integer|exists:project_stages,id',
            'type'       => ['required', Rule::in(['month', 'date'])],
            'value'      => 'required',
        ]);

        $user = auth()->user();
        $userId = $user->id;
        $type = $request->input('type');
        $value = $request->input('value');

        // Start building the query for completed tasks
        $query = ProjectTask::with(['project:id,name', 'stage:id,name', 'created_by:id,name'])
            ->whereHas('project.site.supervisors', fn($q) => $q->where('users.id', $userId))
            ->whereNotNull('completed_at');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('stage_id')) {
            $query->where('stage_id', $request->stage_id);
        }

        // Filter based on type and value
        if ($type === 'month') {
            try {
                $monthDate = Carbon::createFromFormat('F, Y', $value, 'Asia/Kolkata')->startOfMonth();
                $startOfMonth = $monthDate->copy()->setTime(0, 0, 0);
                $endOfMonth = $monthDate->copy()->endOfMonth()->setTime(23, 59, 59);
                $query->whereBetween('completed_at', [$startOfMonth, $endOfMonth]);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid month format. Please use "Month, Year" (e.g., March, 2025).'], 422);
            }
        } elseif ($type === 'date') {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $value);
                $formattedDate = $date->toDateString();
                $query->whereDate('completed_at', $formattedDate);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid date format. Please use "DD/MM/YYYY" (e.g., 01/04/2025).'], 422);
            }
        }

        $tasks = $query->orderByDesc('completed_at');

        // Apply additional data filtering if needed
        $filteredData = dataFilter($tasks, $request);

        // Add file URL conversion
        $filteredData->transform(static function ($task) {
            $task->image = generate_file_url($task->image);
            return $task;
        });

        return $this->successResponse(dataFormatter($filteredData), "Completed tasks fetched successfully!");
    }
}
