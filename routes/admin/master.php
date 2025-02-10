<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\master\StatesController;
use App\Http\Controllers\web\masters\general\CityController;
use App\Http\Controllers\web\masters\general\DistrictsController;
use App\Http\Controllers\web\masters\general\PostalCodesController;


Route::group(['prefix'=>'states'],function (){
    Route::controller(StatesController::class)->group(function () {
        Route::get('/', 'view');
        Route::get('/trash', 'TrashView');
        Route::get('/create', 'Create');
        Route::get('/edit/{ID}', 'Edit');

        Route::post('/data', 'TableView');
        Route::post('/create', 'Save');
        Route::POST('/edit/{ID}', 'Update');
        Route::POST('/delete/{ID}', 'Delete');
        Route::POST('/restore/{ID}', 'Restore');
        Route::post('/trash-data', 'TrashTableView');

    });
});

Route::group(['prefix'=>'districts'],function (){
    Route::controller(DistrictsController::class)->group(function () {
        Route::get('/', 'view');
        Route::get('/trash', 'TrashView');
        Route::get('/create', 'Create');
        Route::get('/edit/{ID}', 'Edit');

        Route::post('/data', 'TableView');
        Route::post('/create', 'Save');
        Route::POST('/edit/{ID}', 'Update');
        Route::POST('/delete/{ID}', 'Delete');
        Route::POST('/restore/{ID}', 'Restore');
        Route::post('/trash-data', 'TrashTableView');

    });
});


Route::group(['prefix'=>'city'],function (){
    Route::controller(CityController::class)->group(function () {
        Route::get('/', 'view');
        Route::get('/trash', 'TrashView');
        Route::get('/create', 'Create');
        Route::get('/edit/{ID}', 'Edit');

        Route::post('/data', 'TableView');
        Route::post('/create', 'Save');
        Route::POST('/edit/{ID}', 'Update');
        Route::POST('/delete/{ID}', 'Delete');
        Route::POST('/restore/{ID}', 'Restore');
        Route::post('/trash-data', 'TrashTableView');

    });
});

Route::group(['prefix'=>'postal-codes'],function (){
    Route::controller(PostalCodesController::class)->group(function () {
        Route::get('/', 'view');
        Route::get('/trash', 'TrashView');
        Route::get('/create', 'Create');
        Route::get('/edit/{ID}', 'Edit');

        Route::post('/data', 'TableView');
        Route::post('/create', 'Save');
        Route::POST('/edit/{ID}', 'Update');
        Route::POST('/delete/{ID}', 'Delete');
        Route::POST('/restore/{ID}', 'Restore');
        Route::post('/trash-data', 'TrashTableView');

    });
});

