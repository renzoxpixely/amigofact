<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateRentalPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rental_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contract_item_id')->nullable();
            $table->unsignedInteger('item_lot_id')->nullable();
            $table->date('start_period')->nullable();
            $table->date('end_period')->nullable();
        
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency_type_id', 12, 2)->default(null);
            $table->json('currency_type')->nullable();
            $table->unsignedInteger('sale_note_id')->nullable();
            $table->timestamps();

            $table->foreign('contract_item_id')->references('id')->on('contract_items');
            $table->foreign('item_lot_id')->references('id')->on('item_lots');
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');
            $table->foreign('sale_note_id')->references('id')->on('sale_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rental_periods');
    }
}
