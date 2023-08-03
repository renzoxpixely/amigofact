<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateContractItemLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_item_lots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contract_item_id')->nullable();
            $table->unsignedInteger('item_lot_id')->nullable();

            $table->foreign('contract_item_id')->references('id')->on('contract_items');
            $table->foreign('item_lot_id')->references('id')->on('item_lots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_item_lots');
    }
}
