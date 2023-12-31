<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'redirect.module', 'locked.tenant'])->group(function() {

            /**
             * account/
             * account/download
             * account/format
             * account/format/download
             * account/summary-report
             * account/summary-report/records
             * account/summary-report/format/download
             */
            Route::prefix('account')->group(function () {
                Route::get('/', 'AccountController@index')->name('tenant.account.index');
                Route::get('download', 'AccountController@download');
                Route::get('format', 'FormatController@index')->name('tenant.account_format.index');
                Route::get('ple', 'PleController@index')->name('tenant.account_ple.index');
                Route::get('format/download', 'FormatController@download');
                Route::get('ple/download', 'PleController@download');

                Route::get('tributo', 'TributoController@index')->name('tenant.account_tributo.index');
                Route::post('tributo/table', 'TributoController@table')->name('tenant.account_tributo.table');



                Route::get('summary-report', 'SummaryReportController@index')->name('tenant.account_summary_report.index');
                Route::get('summary-report/records', 'SummaryReportController@records');
                Route::get('summary-report/format/download', 'SummaryReportController@download');

            });

            Route::prefix('company_accounts')->group(function () {
                Route::get('create', 'CompanyAccountController@create')->name('tenant.company_accounts.create')->middleware('redirect.level');
                Route::get('record', 'CompanyAccountController@record');
                Route::post('', 'CompanyAccountController@store');
            });

            Route::prefix('accounting_ledger')->group(function () {
                Route::get('/', 'LedgerAccountController@index')->name('tenant.accounting_ledger.create');
                // accounting_ledger?date_end=2021-10-24&date_start=2021-10-24&month_end=2021-10&month_start=2021-10&period=month
                Route::get('/excel/', 'LedgerAccountController@excel');
                //->middleware('redirect.level')
                Route::post('record', 'LedgerAccountController@record');
            });

            Route::prefix('accounting')->group(function () {
            //plan contable
                Route::get('tables', 'AccountingController@tables');
                Route::get('plan', 'AccountingController@index')->name('tenant.accounting.index');
                Route::get('plan/columns', 'AccountingController@columns');
                Route::get('plan/records', 'AccountingController@records');
                Route::get('plan/record/{id}', 'AccountingController@record');
                Route::get('params', 'CompanyAccountController@create')->name('tenant.accounting_params.index');
            });


        });
    });
}
else {

    Route::domain(env('APP_URL_BASE'))->group(function() {

        Route::middleware('auth:admin')->group(function() {

            Route::prefix('accounting')->group(function () {

                Route::get('', 'System\AccountingController@index')->name('system.accounting.index');
                Route::get('records', 'System\AccountingController@records');
                Route::get('download', 'System\AccountingController@download');

            });


        });
    });

}

