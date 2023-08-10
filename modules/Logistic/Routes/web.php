<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {

            Route::prefix('logistic')->group(function() {
                Route::get('', 'LogisticController@index')->name('tenant.logistics.index');
                Route::get('/create/{id?}', 'LogisticController@create')->name('tenant.logistics.create')->middleware('redirect.level');
                // Route::get('/', 'LogisticController@index');
            });


        });

        // Route::prefix('production-orders')->group(function () {

        //     Route::get('', 'ProductionOrderController@index')->name('tenant.production_orders.index')->middleware(['redirect.level']);
        //     Route::get('/columns', 'ProductionOrderController@columns');
        //     Route::get('/records', 'ProductionOrderController@records');
        
        // });

    });

}
