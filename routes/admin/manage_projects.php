<?php

use App\Http\Controllers\Admin\ManageProjects\AmenityController;
use App\Http\Controllers\Admin\ManageProjects\ProjectController;
use App\Http\Controllers\Admin\ManageProjects\ProjectSpecificationsController;
use App\Http\Controllers\Admin\ManageProjects\ProjectTaskController;
use Illuminate\Support\Facades\Route;

// Route::get('project-specifications', [ProjectSpecificationsController::class, 'index'])->name('project_specifications.index');

Route::resource('amenities', AmenityController::class);
Route::put('amenities/restore/{id}', [AmenityController::class, 'restore'])->name('amenities.restore')->middleware('can:Restore Amenities');

Route::resource('projects', ProjectController::class);
Route::put('projects/restore/{id}', [ProjectController::class, 'restore'])->name('projects.restore')->middleware('can:Restore Projects');

Route::resource('project_tasks', ProjectTaskController::class);
Route::put('project_tasks/restore/{id}', [ProjectTaskController::class, 'restore'])->name('project_tasks.restore')->middleware('can:Restore Project Tasks');


Route::prefix('project-specifications')->name('project_specifications.')->group(function () {
    Route::controller(ProjectSpecificationsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::post('update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });
});
