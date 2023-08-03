<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCertainColumnsNullImportationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('importations', function(Blueprint $table) {
            $table->string('number_proforma')->nullable()->change();
            $table->string('amount_proforma')->nullable()->change();
            $table->string('sale_reference')->nullable()->change();
            $table->string('ref_customs_agent')->nullable()->change();
            $table->string('dam')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('importations', function(Blueprint $table) {
            $table->string('number_proforma')->change();
            $table->string('amount_proforma')->change();
            $table->string('sale_reference')->change();
            $table->string('ref_customs_agent')->change();
            $table->string('dam')->change();
        });
    }
}
