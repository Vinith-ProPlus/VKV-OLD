<?php

use App\Http\Controllers\Admin\ManageProjects\AmenityController;
use App\Http\Controllers\Admin\ManageProjects\ProjectController;
use App\Http\Controllers\Admin\ManageProjects\ProjectSpecificationsController;
use Illuminate\Support\Facades\Route;

// Route::get('project-specifications', [ProjectSpecificationsController::class, 'index'])->name('project_specifications.index');

Route::resource('amenities', AmenityController::class);
Route::put('amenities/restore/{id}', [AmenityController::class, 'restore'])->name('amenities.restore')->middleware('can:Restore Amenities');

Route::resource('projects', ProjectController::class);
Route::put('projects/restore/{id}', [ProjectController::class, 'restore'])->name('projects.restore')->middleware('can:Restore Projects');

Route::resource('project_specifications', ProjectSpecificationsController::class);
Route::put('project_specifications/restore/{id}', [ProjectSpecificationsController::class, 'restore'])->name('project_specifications.restore')->middleware('can:Restore Projects Specifications');


    /* Route::prefix('project-specifications')->name('project_specifications.')->group(function () {
        Route::controller(ProjectSpecificationsController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::delete('/delete/{id}', 'destroy')->name('destroy');
            Route::put('/restore/{id}', 'restore')->name('restore');
        });
    }); */
    