<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GeneralController;
use App\Http\Controllers\API\MobileUserAttendanceController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/sendForgotPasswordOTP', [AuthController::class, 'sendForgotPasswordOTP']);
Route::post('/verifyForgotPasswordOTP', [AuthController::class, 'verifyForgotPasswordOTP']);
Route::post('/changePassword', [AuthController::class, 'changePassword'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'profile'])->middleware('auth:sanctum');
Route::post('/updateProfile', [AuthController::class, 'updateProfile'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix'=>'master'], static function (){
    Route::post('/getCategories', [GeneralController::class, 'getCategories'])->name('getCategories');
    Route::post('/getProducts', [GeneralController::class, 'getProducts'])->name('getProducts');
    Route::post('/getStates', [GeneralController::class, 'getStates'])->name('getStates');
    Route::post('/getDistricts', [GeneralController::class, 'getDistricts'])->name('getDistricts');
    Route::post('/getCities', [GeneralController::class, 'getCities'])->name('getCities');
    Route::post('/getPinCodes', [GeneralController::class, 'getPinCodes'])->name('getPinCodes');
    Route::post('/getLeadSource', [GeneralController::class, 'getLeadSource'])->name('getLeadSource');
    Route::post('/getLeadStatus', [GeneralController::class, 'getLeadStatus'])->name('getLeadStatus');
    Route::post('/getUsers', [GeneralController::class, 'getUsers'])->name('getUsers');
    Route::post('/getRoles', [GeneralController::class, 'getRoles'])->name('getRoles');
    Route::post('/getProjects', [GeneralController::class, 'getProjects'])->name('getProjects');
    Route::post('/getStages', [GeneralController::class, 'getStages'])->name('getStages');
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(static function () {
    // Attendance
    Route::post('attendance/record-attendance', [MobileUserAttendanceController::class, 'recordAttendance']);
    Route::post('attendance/history', [MobileUserAttendanceController::class, 'getAttendanceHistory']);

    // Manage Task
    Route::post('manage-task/get-task', [GeneralController::class, 'getTask']);
    Route::post('manage-task/list', [GeneralController::class, 'getTasks']);
    Route::post('manage-task/create-task', [GeneralController::class, 'createProjectTask']);
    Route::post('manage-task/update-task-status/{task}', [GeneralController::class, 'updateTaskStatus']);

    Route::post('HomeScreen', [GeneralController::class, 'HomeScreen']);
});
