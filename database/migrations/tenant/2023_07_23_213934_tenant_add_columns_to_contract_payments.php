<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsToContractPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_payments', function (Blueprint $table) {
            $table->integer('participation_id')->nullable()->after('contract_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('period');
            $table->dropColumn('pays_transport');
            $table->dropColumn('participation_percentage');
            $table->dropColumn('automatic_renew');
        });
    }
}
