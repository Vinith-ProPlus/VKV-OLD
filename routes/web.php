<?php

use App\Http\Controllers\Admin\Master\ProductCategoryController;
use App\Http\Controllers\Admin\Master\ProductController;
use App\Http\Controllers\Admin\Master\TaxController;
use App\Http\Controllers\Admin\Master\UnitOfMeasurementController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SqlImportController;
use App\Models\ProductCategory;
use App\Models\Tax;
use App\Models\UnitOfMeasurement;
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
            Route::resource('taxes', TaxController::class)->except(['show']);
            Route::put('taxes/restore/{id}', [TaxController::class, 'restore'])->name('taxes.restore')->middleware('can:Restore Tax');

            Route::resource('units', UnitOfMeasurementController::class)->except(['show']);
            Route::put('units/restore/{id}', [UnitOfMeasurementController::class, 'restore'])->name('units.restore')->middleware('can:Restore Unit of Measurement');
            Route::resource('product_categories', ProductCategoryController::class)->except(['show']);
            Route::put('product_categories/restore/{id}', [ProductCategoryController::class, 'restore'])->name('product_categories.restore')->middleware('can:Restore Product Category');

            Route::resource('products', ProductController::class)->except(['show']);
            Route::put('products/restore/{id}', [ProductController::class, 'restore'])->name('products.restore')->middleware('can:Restore Product');

            Route::get('categories/list', function () {
                return response()->json(ProductCategory::select('id', 'name')->get());
            })->name('categories.list');

            Route::get('taxes/list', function () {
                $t = Tax::select('id', 'name')->get();
                logger($t);
                return response()->json(Tax::select('id', 'name')->get());
            })->name('taxes.list');

            Route::get('uoms/list', function () {
                return response()->json(UnitOfMeasurement::select('id', 'name')->get());
            })->name('uoms.list');

        });


//role crud
            Route::get('roles', [RoleController::class, 'index'])->name('role.index');
            Route::get('role/create', [RoleController::class, 'create'])->name('role.create')->middleware('can:Create Roles and Permissions');
            Route::post('role/store', [RoleController::class, 'store'])->name('role.store');
            Route::get('role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
            Route::get('role/{id}', [RoleController::class, 'show'])->name('role.show');
            Route::put('role/update/{id}', [RoleController::class, 'update'])->name('role.update');
            Route::delete('role/delete/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
    });
});

require __DIR__.'/auth.php';
