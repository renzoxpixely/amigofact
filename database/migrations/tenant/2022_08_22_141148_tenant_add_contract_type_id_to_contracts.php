<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddContractTypeIdToContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->unsignedInteger('contract_type_id')->nullable()->after('quotation_id');
            $table->text('english_clauses')->nullable()->after('contract_type_id');
            $table->text('spanish_clauses')->nullable()->after('english_clauses');
            $table->foreign('contract_type_id')->references('id')->on('contract_types');
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
            $table->dropForeign(['contract_type_id']);
            $table->dropColumn('contract_type_id');
            $table->dropColumn('english_clauses');
            $table->dropColumn('spanish_clauses');
        });
    }
}
