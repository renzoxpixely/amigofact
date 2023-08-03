<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipationItemLotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participation_item_lots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('participation_item_id')->nullable();
            $table->unsignedInteger('item_lot_id')->nullable();

            $table->foreign('participation_item_id')->references('id')->on('participation_items');
            $table->foreign('item_lot_id')->references('id')->on('item_lots');
            
            $table->date('depreciation_start_date')->nullable();
            $table->date('depreciation_end_date')->nullable();
            $table->decimal('depreciation_percentage', 12, 2)->default(0);
            $table->decimal('depreciation_accumulated', 12, 2)->default(0);
            $table->date('activation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participation_item_lots');
    }
}
