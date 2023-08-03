<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsToContractTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_types', function (Blueprint $table) {
            $table->decimal('factor_creed', 12, 2)->default(0);
            $table->decimal('maintenance_rate', 12, 2)->default(0);
            $table->decimal('tax_rate', 12, 2)->default(0);
            $table->decimal('uit', 12, 2)->default(0);
            $table->decimal('isc_one_uit', 12, 2)->default(0);
            $table->decimal('isc_three_uit', 12, 2)->default(0);
            $table->decimal('isc_more_than_three_uit', 12, 2)->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_types', function (Blueprint $table) {
            $table->dropColumn('factor_creed');
            $table->dropColumn('maintenance_rate');
            $table->dropColumn('tax_rate');
            $table->dropColumn('uit');
            $table->dropColumn('isc_one_uit');
            $table->dropColumn('isc_three_uit');
            $table->dropColumn('isc_more_than_three_uit');

        });
    }
}
