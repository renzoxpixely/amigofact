<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddImportCostsToPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->json('lading')->nullable();
            $table->json('insurace')->nullable();
            $table->json('import_costs')->nullable();
            $table->json('fees_settlements')->nullable();
            $table->decimal('total_lading', 12, 2)->default(0);
            $table->decimal('total_insurace', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->decimal('total_tax_customs_law', 12, 2)->default(0);
            $table->decimal('total_tax_isc', 12, 2)->default(0);
            $table->decimal('total_tax_igv', 12, 2)->default(0);
            $table->decimal('total_tax_ipm', 12, 2)->default(0);
            $table->decimal('total_tax_customs_fee', 12, 2)->default(0);
            $table->decimal('total_tax_rights', 12, 2)->default(0);
            $table->decimal('total_tax_perception', 12, 2)->default(0);
            $table->decimal('total_import_expenses_without_igv', 12, 2)->default(0);
            $table->decimal('total_igv_expenses_usd', 12, 2)->default(0);
            $table->decimal('total_ipm_expenses_usd', 12, 2)->default(0);
            $table->decimal('total_import_expenses', 12, 2)->default(0);
            $table->decimal('total_import_cost', 12, 2)->default(0);
            $table->decimal('total_tax_credit', 12, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('lading');
            $table->dropColumn('insurace');
            $table->dropColumn('import_costs');
            $table->dropColumn('fees_settlements');
            $table->dropColumn('total_lading');
            $table->dropColumn('total_insurace');
            $table->dropColumn('total_cost');
            $table->dropColumn('total_tax_customs_law');
            $table->dropColumn('total_tax_isc');
            $table->dropColumn('total_tax_igv');
            $table->dropColumn('total_tax_ipm');
            $table->dropColumn('total_tax_customs_fee');
            $table->dropColumn('total_tax_rights');
            $table->dropColumn('total_tax_perception');
            $table->dropColumn('total_import_expenses_without_igv');
            $table->dropColumn('total_igv_expenses_usd');
            $table->dropColumn('total_ipm_expenses_usd');
            $table->dropColumn('total_import_expenses');
            $table->dropColumn('total_import_cost');
            $table->dropColumn('total_tax_credit');
        });
    }
}
