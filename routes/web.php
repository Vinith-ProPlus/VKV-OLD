<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SqlImportController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
});

Route::get('/', function () {
    if(auth()->user()){
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Route::get('/import-default-rows', [SqlImportController::class, 'importSqlFiles']);
Route::get('/import-table-rows/{TableName}', [SqlImportController::class, 'importTableRows']);
Route::get('/export-menus', [SqlImportController::class, 'exportMenus']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


});

Route::group(['prefix'=>'admin'],function (){
    Route::middleware('auth')->group(function () {

        Route::group(['prefix'=>'master'],function (){
            require __DIR__.'/admin/master.php';
        });
        Route::group(['prefix'=>'manage-projects'],function (){
            require __DIR__.'/admin/manage_projects.php';
        });


        //role crud
            Route::get('roles', [RoleController::class, 'index'])->name('role.index');
            Route::get('role/create', [RoleController::class, 'create'])->name('role.create')->middleware('can:Create Roles and Permissions');
            Route::post('role/store', [RoleController::class, 'store'])->name('role.store');
            Route::get('role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
            Route::get('role/{id}', [RoleController::class, 'show'])->name('role.show');
            Route::put('role/update/{id}', [RoleController::class, 'update'])->name('role.update');
            Route::delete('role/delete/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

        /* Route::group(['prefix'=>'transaction'],function (){
            require __DIR__.'/admin/transaction.php';
        });
        Route::group(['prefix'=>'reports'],function (){
            require __DIR__.'/admin/reports.php';
        });
        Route::group(['prefix'=>'users-and-permissions'],function (){
            require __DIR__.'/admin/users.php';
        });
        Route::group(['prefix'=>'settings'],function (){
            require __DIR__.'/admin/settings.php';
        }); */
    });
});

require __DIR__.'/auth.php';
