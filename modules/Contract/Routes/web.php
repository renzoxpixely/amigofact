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

            /**
            contracts/
            contracts/columns
            contracts/records
            contracts/create/{id?}
            contracts/state-type/{state_type_id}/{id}
            contracts/filter
            contracts/tables
            contracts/table/{table}
            contracts/record/{contract}
            contracts/voided/{id}
            contracts/item/tables
            contracts/option/tables
            contracts/search/customers
            contracts/search/customer/{id}
            contracts/download/{external_id}/{format?}
            contracts/print/{external_id}/{format?}
            contracts/email
            contracts/search/item/{item}
            contracts/search-items
            contracts/record2/{contract}
            contracts/changed/{contract}
            contracts/generate-quotation/{quotation}
             */

            Route::prefix('contracts')->group(function () {

                Route::get('', 'ContractController@index')->name('tenant.contracts.index');
                Route::get('/getItemsContract', 'ContractController@getContratosItems')->name('tenant.contracts.items.report');
                Route::get('/getItemsContractView', 'ContractController@getContratosItemsView')->name('tenant.contracts.items.reportview');
                Route::get('/columns', 'ContractController@columns');
                Route::get('/records', 'ContractController@records');
                Route::get('/create/{id?}', 'ContractController@create')->name('tenant.contracts.create')->middleware('redirect.level');
                Route::get('/view/{id?}', 'ContractController@view')->name('tenant.contracts.view')->middleware('redirect.level');
                Route::get('search-items', 'ContractController@searchItems');
                Route::get('search/item/{item}', 'ContractController@searchItemById');
				Route::post('/save/items', 'ContractController@saveItems');

                Route::get('/state-type/{state_type_id}/{id}', 'ContractController@updateStateType');
                Route::get('/filter', 'ContractController@filter');
                Route::get('/tables', 'ContractController@tables');
                Route::get('/resources', 'ContractController@resources');
                Route::get('/table/{table}', 'ContractController@table');
                Route::post('', 'ContractController@store');
                Route::get('/record/{contract}', 'ContractController@record');
                Route::get('/voided/{id}', 'ContractController@voided');
                Route::get('/item/tables', 'ContractController@item_tables');
                Route::get('/option/tables', 'ContractController@option_tables');
                Route::get('/search/customers', 'ContractController@searchCustomers');
                Route::get('/search/customer/{id}', 'ContractController@searchCustomerById');
                Route::get('/download/{external_id}/{format?}', 'ContractController@download');
                Route::get('/print/{external_id}/{format?}', 'ContractController@toPrint');
                Route::post('/email', 'ContractController@email');
                Route::get('/record2/{contract}', 'ContractController@record2');
                Route::get('/changed/{contract}', 'ContractController@changed');
                Route::get('/generate-quotation/{quotation}', 'ContractController@generateContract')->middleware(['redirect.level']);
                Route::get('/machines-by-contract/{contract}', 'ContractController@getMachinesByContract');

            });

            /**
            contract-types/
            contract-types/records
            contract-types/tables
            contract-types/record/{contract}
            contract-types/columns
            contract-types/{id}
                */
            Route::prefix('contract-types')->group(function () {
                Route::get('', 'ContractTypeController@index')->name('tenant.contract-types.index')->middleware(['redirect.level']);
                Route::get('/records', 'ContractTypeController@records');
                Route::get('/tables', 'ContractTypeController@tables');
                Route::get('/record/{id}', 'ContractTypeController@record');
                Route::post('', 'ContractTypeController@store');
                Route::get('/columns', 'ContractTypeController@columns');
                Route::delete('/{id}', 'ContractTypeController@destroy');
            });

            Route::prefix('stake_periods')->group(function () {
                Route::get('/tables/{contract_type_id}', 'StakePeriodController@tables');
				Route::get('/records', 'StakePeriodController@records');
                Route::get('/record/{id}', 'StakePeriodController@record');
                Route::post('', 'StakePeriodController@store');
                Route::get('/payment_receipts/records', 'StakePeriodController@paymentReceipts');
            });

            Route::prefix('rental_periods')->group(function () {
				Route::get('/records', 'RentalPeriodController@records');
                Route::post('', 'RentalPeriodController@store');
                Route::post('/destroy', 'RentalPeriodController@destroy');
                Route::post('/incident', 'RentalPeriodController@incident');
                Route::post('/confirm', 'RentalPeriodController@confirm');
                Route::get('/payment_receipts/records', 'RentalPeriodController@paymentReceipts');
                Route::post('/save/code', 'RentalPeriodController@saveCode');
            });

        });

        Route::prefix('production-orders')->group(function () {

            Route::get('', 'ProductionOrderController@index')->name('tenant.production_orders.index')->middleware(['redirect.level']);
            Route::get('/columns', 'ProductionOrderController@columns');
            Route::get('/records', 'ProductionOrderController@records');

        });

    });

}
