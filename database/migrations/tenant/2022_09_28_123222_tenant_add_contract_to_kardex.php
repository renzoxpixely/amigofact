<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TenantAddContractToKardex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `kardex` CHANGE COLUMN `type` `type` ENUM('sale','purchase','contract') NULL DEFAULT NULL");
        Schema::table('kardex', function (Blueprint $table) {
            //$table->enum('type', ['sale', 'purchase','contract'])->change();
            $table->unsignedInteger('contract_id')->nullable()->after('sale_note_id');

            $table->foreign('contract_id')->references('id')->on('contracts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kardex', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropColumn('contract_id');
        });
    }
}
