<?php

use App\Http\Controllers\admin\master\ProductCategoryController;
use App\Http\Controllers\admin\RoleController;
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
    return view('welcome');
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


    Route::resource('product_categories', ProductCategoryController::class);
    Route::put('product_categories/restore/{id}', [ProductCategoryController::class, 'restore'])->name('product_categories.restore');
});

Route::group(['prefix'=>'admin'],function (){
    Route::middleware('auth')->group(function () {
        Route::controller(dashboardController::class)->group(function () {
            Route::get('/','dashboard')->name('admin.dashboard');
            Route::get('/dashboard','dashboard');
            Route::post('/dashboard/get/dashboard-stats','getDashboardStats')->name('admin.dashboard.get.dashboard-stats');
            Route::post('/dashboard/get/recent/quote-enquiry','getRecentQuoteEnquiry')->name('admin.dashboard.get.recent.quote-enquiry');
            Route::post('/dashboard/get/recent/orders','getRecentOrders')->name('admin.dashboard.get.recent.orders');
            Route::post('/dashboard/get/orders/stats','getOrderStats')->name('admin.dashboard.get.orders.stats');
            Route::post('/dashboard/get/payments/stats','getPaymentStats')->name('admin.dashboard.get.payments.stats');
            Route::get('/dashboard/get/upcoming/payments','getUpcomingPayments')->name('admin.dashboard.get.upcoming.payments');
            Route::POST('/dashboard/get/circle/stats/enquiry','getEnquiryCircleStats')->name('admin.dashboard.get.circle.stats.enquiry');
            Route::POST('/dashboard/get/circle/stats/orders','getOrdersCircleStats')->name('admin.dashboard.get.circle.stats.orders');
            Route::POST('/dashboard/get/circle/stats/delivery','getDeliveryCircleStats')->name('admin.dashboard.get.circle.stats.delivery');
        });
        Route::group(['prefix'=>'master'],function (){
            require __DIR__.'/admin/master.php';
        });

//        Route::group(['middleware' => ['can:Roles']], function () {
//role crud
            Route::get('roles', [RoleController::class, 'index'])->name('role.index');
            Route::get('role/create', [RoleController::class, 'create'])->name('role.create');
            Route::post('role/store', [RoleController::class, 'store'])->name('role.store');
            Route::get('role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
            Route::get('role/{id}', [RoleController::class, 'show'])->name('role.show');
            Route::put('role/update/{id}', [RoleController::class, 'update'])->name('role.update');
            Route::delete('role/delete/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
//        });

        Route::delete('/role/delete/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

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
