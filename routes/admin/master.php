<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Master\CityController;
use App\Http\Controllers\Admin\Master\DistrictController;
use App\Http\Controllers\Admin\Master\PincodeController;
use App\Http\Controllers\Admin\Master\StatesController;
use App\Http\Controllers\Admin\Master\ProductCategoryController;
use App\Http\Controllers\Admin\Master\TaxController;


Route::resource('states', StatesController::class);
Route::put('states/restore/{id}', [StatesController::class, 'restore'])->name('states.restore')->middleware('can:Restore States');

Route::get('/districts/getStates', [DistrictController::class, 'getStates'])->name('district.getstates');
Route::resource('districts', DistrictController::class);
Route::put('districts/restore/{id}', [DistrictController::class, 'restore'])->name('districts.restore')->middleware('can:Restore Districts');

Route::get('/pincodes/getDistricts', [PincodeController::class, 'getDistricts'])->name('pincodes.getDistricts');
Route::resource('pincodes', PincodeController::class);
Route::put('pincodes/restore/{id}', [PincodeController::class, 'restore'])->name('pincodes.restore')->middleware('can:Restore Pincodes');

Route::resource('cities', CityController::class);
Route::put('cities/restore/{id}', [CityController::class, 'restore'])->name('cities.restore')->middleware('can:Restore Pincodes');

Route::resource('product_categories', ProductCategoryController::class);
Route::put('product_categories/restore/{id}', [ProductCategoryController::class, 'restore'])->name('product_categories.restore')->middleware('can:Restore Product Category');

Route::resource('taxes', TaxController::class);
Route::put('taxes/restore/{id}', [TaxController::class, 'restore'])->name('taxes.restore')->middleware('can:Restore Tax');





