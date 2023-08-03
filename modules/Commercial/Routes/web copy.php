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
});
