<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDepreciationToContractItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_items', function (Blueprint $table) {
            $table->date('depreciation_start_date')->nullable();
            $table->date('depreciation_end_date')->nullable();
            $table->decimal('depreciation_percentage', 12, 2)->default(0);
            $table->decimal('depreciation_accumulated', 12, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_items', function (Blueprint $table) {
            $table->dropColumn('depreciation_start_date');
            $table->dropColumn('depreciation_end_date');
            $table->dropColumn('depreciation_percentage');
            $table->dropColumn('depreciation_accumulated');
        });
    }
}
