<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MobileUserAttendanceRequest;
use App\Models\MobileUserAttendance;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class MobileUserAttendanceController extends Controller
{
    use ApiResponse;
    /**
     * Record User Check-in/Check-out
     */
    public function recordAttendance(MobileUserAttendanceRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $attendance = MobileUserAttendance::create(array_merge(
                $request->validated(),
                [
                    'user_id' => auth()->user()->id,
                    'ip_address' => $request->ip(),
                    'time' => now(),
                ]
            ));
            DB::commit();
            return $this->successResponse($attendance, ucfirst($request->type) . " recorded successfully!");
        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('Error::AttendanceController@recordAttendance - ' . $exception->getMessage());
            return $this->errorResponse($exception->getMessage(), "Failed to record attendance!", 500);
        }
    }

    /**
     * Get User's Attendance History
     */
    public function getAttendanceHistory(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $query = MobileUserAttendance::where('user_id', $user->id);
            $query = dataFilter($query, $request);
            return $this->successResponse(dataFormatter($query), "Attendance history fetched successfully!");
        } catch (Throwable $exception) {
            Log::error('Error::AttendanceController@getAttendanceHistory - ' . $exception->getMessage());
            return $this->errorResponse($exception->getMessage(), "Failed to fetch attendance history!", 500);
        }
    }
}
