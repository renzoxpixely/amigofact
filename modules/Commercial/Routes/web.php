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


            Route::prefix('commercial')->group(function() {
                Route::get('', 'CommercialController@index')->name('tenant.commercials.index');    
                Route::get('/participacion/create/{id?}', 'CommercialController@participacionCreate')->name('tenant.commercials.participacion.create')->middleware('redirect.level');
                Route::get('/venta/create/{id?}', 'CommercialController@ventaCreate')->name('tenant.commercials.venta.create')->middleware('redirect.level');
                Route::get('/renta/create/{id?}', 'CommercialController@rentaCreate')->name('tenant.commercials.renta.create')->middleware('redirect.level');

                Route::post('', 'CommercialController@store');
                Route::get('/columns', 'CommercialController@columns');
                Route::get('/record/{participation}', 'CommercialController@record');
                Route::get('/records', 'CommercialController@records');
                Route::get('/filter', 'CommercialController@filter');

                Route::get('/tables', 'CommercialController@tables');
                Route::get('/resources', 'CommercialController@resources');
                Route::get('/table/{table}', 'CommercialController@table');
                Route::get('/view/{id?}', 'CommercialController@view')->name('tenant.contracts.view')->middleware('redirect.level');
                Route::get('/create/{id?}', 'CommercialController@create')->name('tenant.contracts.create')->middleware('redirect.level');

                Route::get('/search/customers', 'CommercialController@searchCustomers');
                Route::get('/search/customer/{id}', 'CommercialController@searchCustomerById');
            });


        });

        // Route::prefix('production-orders')->group(function () {

        //     Route::get('', 'ProductionOrderController@index')->name('tenant.production_orders.index')->middleware(['redirect.level']);
        //     Route::get('/columns', 'ProductionOrderController@columns');
        //     Route::get('/records', 'ProductionOrderController@records');
        
        // });

    });

}
