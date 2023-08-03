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

Route::prefix('logistic')->group(function() {
    Route::get('', 'LogisticController@index')->name('tenant.logistics.index');
    Route::get('/create/{id?}', 'LogisticController@create')->name('tenant.logistics.create')->middleware('redirect.level');
    // Route::get('/', 'LogisticController@index');
});
