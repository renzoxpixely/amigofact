<?php


use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {

            /**
             sale-opportunities
             sale-opportunities/columns
             sale-opportunities/records
             sale-opportunities/record/{id}
             sale-opportunities/create/{id}
             sale-opportunities/earch/customers
             sale-opportunities/earch/customers/{id}
             sale-opportunities/tables
             sale-opportunities/table/{table}
             sale-opportunities/item/tables
             sale-opportunities/email
             sale-opportunities/download/{external_id}/{format?}
             sale-opportunities/print/{external_id}/{format?}
             sale-opportunities/search-items
             sale-opportunities/search/item/{item}
             sale-opportunities/uploads
             sale-opportunities/download-file/{filename}
             */
            Route::prefix('sale-opportunities')->group(function() {

                Route::get('', 'SaleOpportunityController@index')->name('tenant.sale_opportunities.index')->middleware(['redirect.level']);
                Route::post('', 'SaleOpportunityController@store');

                Route::get('columns', 'SaleOpportunityController@columns');
                Route::get('records', 'SaleOpportunityController@records');
                Route::get('record/{id}', 'SaleOpportunityController@record');
                Route::get('create/{id?}', 'SaleOpportunityController@create')->name('tenant.sale_opportunities.create')->middleware(['redirect.level']);
                // Route::get('edit/{id}', 'SaleOpportunityController@edit');
                Route::get('search/customers', 'SaleOpportunityController@searchCustomers');
                Route::get('search/customer/{id}', 'SaleOpportunityController@searchCustomerById');

                Route::get('tables', 'SaleOpportunityController@tables');
                Route::get('table/{table}', 'SaleOpportunityController@table');
                Route::get('item/tables', 'SaleOpportunityController@item_tables');
                Route::post('email', 'SaleOpportunityController@email');

                Route::get('download/{external_id}/{format?}', 'SaleOpportunityController@download');
                Route::get('print/{external_id}/{format?}', 'SaleOpportunityController@toPrint');

                Route::post('uploads', 'SaleOpportunityFileController@uploadFile');
                Route::get('download-file/{filename}', 'SaleOpportunityFileController@download');
                Route::get('search-items', 'SaleOpportunityFileController@searchItems');
                Route::get('search/item/{item}', 'SaleOpportunityFileController@searchItemById');

            });

            Route::prefix('payment-method-types')->group(function () {

                Route::get('/records', 'PaymentMethodTypeController@records');
                Route::get('/record/{id}', 'PaymentMethodTypeController@record');
                Route::post('', 'PaymentMethodTypeController@store');
                Route::delete('/{id}', 'PaymentMethodTypeController@destroy');

            });

            

        });

        


        

        /**
         * technical-services/
         * technical-services/columns
         * technical-services/records
         * technical-services/tables
         * technical-services/record/{contract}
         * technical-services/search/customers
         * technical-services/search/customer/{id}
         * technical-services/download/{id}/{format?}
         * technical-services/print/{id}/{format?}
         * technical-services/{id
        */
        Route::prefix('technical-services')->group(function () {

            Route::get('', 'TechnicalServiceController@index')->name('tenant.technical_services.index')->middleware(['redirect.level']);
            Route::get('/columns', 'TechnicalServiceController@columns');
            Route::get('/records', 'TechnicalServiceController@records');
            Route::get('/tables', 'TechnicalServiceController@tables');
            Route::post('', 'TechnicalServiceController@store');
            Route::get('/record/{contract}', 'TechnicalServiceController@record');
            Route::get('/search/customers', 'TechnicalServiceController@searchCustomers');
            Route::get('/search/customer/{id}', 'TechnicalServiceController@searchCustomerById');
            Route::get('/download/{id}/{format?}', 'TechnicalServiceController@download');
            Route::get('/print/{id}/{format?}', 'TechnicalServiceController@toPrint');
            Route::delete('/{id}', 'TechnicalServiceController@destroy');

        });


        Route::prefix('user-commissions')->group(function () {

            Route::get('', 'UserCommissionController@index')->name('tenant.user_commissions.index')->middleware(['redirect.level']);
            Route::get('/columns', 'UserCommissionController@columns');
            Route::get('/records', 'UserCommissionController@records');
            Route::get('/tables', 'UserCommissionController@tables');
            Route::post('', 'UserCommissionController@store');
            Route::get('/record/{id}', 'UserCommissionController@record');
            Route::delete('/{id}', 'UserCommissionController@destroy');

        });

        Route::prefix('quotation_payments')->group(function () {
            /**
            quotation_payments/records/{quotation}
            quotation_payments/document/{quotation}
            quotation_payments/tables
            quotation_payments
            quotation_payments/{quotation_payment}
             */
            Route::get('/records/{quotation}', 'QuotationPaymentController@records');
            Route::get('/document/{quotation}', 'QuotationPaymentController@document');
            Route::get('/tables', 'QuotationPaymentController@tables');
            Route::post('', 'QuotationPaymentController@store');
            Route::delete('/{quotation_payment}', 'QuotationPaymentController@destroy');

        });

        /**
         * technical-service-payments/
         * technical-service-payments/records/{record}
         * technical-service-payments/document/{record}
         * technical-service-payments/tables
         * technical-service-payments/{record_payment}
         */
        Route::prefix('technical-service-payments')->group(function () {

            Route::get('/records/{record}', 'TechnicalServicePaymentController@records');
            Route::get('/document/{record}', 'TechnicalServicePaymentController@document');
            Route::get('/tables', 'TechnicalServicePaymentController@tables');
            Route::post('', 'TechnicalServicePaymentController@store');
            Route::delete('/{record_payment}', 'TechnicalServicePaymentController@destroy');

        });

        /**
         * generate-document/
         * generate-document/record/{table}/{record}
         * generate-document/table
         * generate-document/customers
         */

        Route::prefix('generate-document')->group(function () {
            Route::get('/record/{table}/{record}', 'GenerateDocumentController@record');
            Route::get('/tables', 'GenerateDocumentController@tables');
            Route::post('/', 'GenerateDocumentController@store');
            Route::post('/customers', 'GenerateDocumentController@customers');
//            Route::post('/store_item', 'GenerateDocumentController@storeItem');
        });
    });
}
