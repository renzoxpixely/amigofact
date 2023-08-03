<?php
use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {

            Route::redirect('/', '/dashboard');

            Route::prefix('dashboard')->group(function () {
                Route::get('/', 'DashboardController@index')->name('tenant.dashboard.index');
                Route::get('filter', 'DashboardController@filter');
                
                Route::post('data', 'DashboardController@data');
                Route::post('data_aditional', 'DashboardController@data_aditional');
                // Route::post('unpaid', 'DashboardController@unpaid');
                // Route::get('unpaidall', 'DashboardController@unpaidall')->name('unpaidall');
                Route::get('stock-by-product/records', 'DashboardController@stockByProduct');
                Route::post('utilities', 'DashboardController@utilities');
                Route::get('global-data', 'DashboardController@globalData');


                Route::get('/tables', 'DashboardController@tables');
                Route::get('records_sale', 'DashboardController@records_sale');
				Route::get('records_sale_excel', 'DashboardController@records_sale_excel');
                Route::get('records_stake', 'DashboardController@records_stake');
				Route::get('records_stake_excel', 'DashboardController@records_stake_excel');
                Route::get('records_rental', 'DashboardController@records_rental');
				Route::get('records_rental_excel', 'DashboardController@records_rental_excel');
            });

            //Commands
            Route::get('command/df', 'DashboardController@df')->name('command.df');

        });
    });
}
