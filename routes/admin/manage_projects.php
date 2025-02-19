<?php

use App\Http\Controllers\Admin\ManageProjects\ProjectSpecificationsController;
use Illuminate\Support\Facades\Route;

// Route::get('project-specifications', [ProjectSpecificationsController::class, 'index'])->name('project_specifications.index');
Route::get('role/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('role/store', [RoleController::class, 'store'])->name('role.store');
    Route::get('role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::get('role/{id}', [RoleController::class, 'show'])->name('role.show');
    Route::put('role/update/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('role/delete/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

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
    