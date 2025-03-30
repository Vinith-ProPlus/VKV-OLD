<?php

use App\Http\Controllers\Admin\ManageProjects\AmenityController;
use App\Http\Controllers\Admin\ManageProjects\ProjectController;
use App\Http\Controllers\Admin\ManageProjects\ProjectSpecificationsController;
use App\Http\Controllers\Admin\ManageProjects\ProjectTaskController;
use App\Http\Controllers\Admin\ManageProjects\SiteController;
use Illuminate\Support\Facades\Route;

// Route::get('project-specifications', [ProjectSpecificationsController::class, 'index'])->name('project_specifications.index');

Route::resource('amenities', AmenityController::class);
Route::put('amenities/restore/{id}', [AmenityController::class, 'restore'])->name('amenities.restore')->middleware('can:Restore Amenities');

Route::resource('projects', ProjectController::class);
Route::put('projects/restore/{id}', [ProjectController::class, 'restore'])->name('projects.restore')->middleware('can:Restore Projects');

Route::resource('project_tasks', ProjectTaskController::class);
Route::put('project_tasks/restore/{id}', [ProjectTaskController::class, 'restore'])->name('project_tasks.restore')->middleware('can:Restore Project Tasks');

Route::resource('project_specifications', ProjectSpecificationsController::class);
Route::put('project_specifications/restore/{id}', [ProjectSpecificationsController::class, 'restore'])->name('project_specifications.restore')->middleware('can:Restore Projects Specifications');

Route::resource('sites', SiteController::class);
Route::put('sites/restore/{id}', [SiteController::class, 'restore'])->name('sites.restore')->middleware('can:Restore Sites');

Route::post('handle_documents', [ProjectController::class,'docxHandler'])->name('projects.handle_documents');
Route::post('update_documents', [ProjectController::class,'updateDocuments'])->name('projects.updateDocuments');
Route::delete('delete_documents', [ProjectController::class,'deleteDocx'])->name('projects.delete_documents');
