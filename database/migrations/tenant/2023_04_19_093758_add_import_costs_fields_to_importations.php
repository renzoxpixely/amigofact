<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImportCostsFieldsToImportations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('importations', function (Blueprint $table) {
            $table->float('isc_dol')->default(0);
            $table->float('igv_dol')->default(0);
            $table->float('ipm_dol')->default(0);
            $table->float('ad_valorem_dol')->default(0);
            $table->float('dispatch_fee_dol')->default(0);
            $table->float('perception_dol')->default(0);
            $table->float('fine_dol')->default(0);
            $table->float('utility')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('importations', function (Blueprint $table) {
            $table->dropColumn('isc_dol');
            $table->float('igv_dol');
            $table->dropColumn('ipm_dol');
            $table->dropColumn('ad_valorem_dol');
            $table->dropColumn('dispatch_fee_dol');
            $table->dropColumn('perception_dol');
            $table->dropColumn('fine_dol');
            $table->dropColumn('utility');
        });
    }
}
