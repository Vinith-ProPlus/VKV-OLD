<?php

use App\Http\Controllers\Admin\ManageProjects\AmenityController;
use App\Http\Controllers\Admin\ManageProjects\ProjectSpecificationsController;
use Illuminate\Support\Facades\Route;

// Route::get('project-specifications', [ProjectSpecificationsController::class, 'index'])->name('project_specifications.index');

Route::resource('amenities', AmenityController::class);
Route::put('amenities/restore/{id}', [AmenityController::class, 'restore'])->name('amenities.restore')->middleware('can:Restore Amenities');


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
