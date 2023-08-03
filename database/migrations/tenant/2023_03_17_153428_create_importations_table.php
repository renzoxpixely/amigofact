<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('import_order_task');
            $table->integer('supplier_id');
            $table->integer('document_type_id');
            $table->integer('number_proforma');
            $table->float('amount_proforma');
            $table->date('date_periodo');
            $table->date('date_of_issue');
            $table->date('arrival_date');
            $table->string('sale_reference');
            $table->string('ref_customs_agent');
            $table->string('transport_type');
            $table->integer('transport_code');
            $table->string('dam');
            $table->string('comments');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('importations');
    }
}
