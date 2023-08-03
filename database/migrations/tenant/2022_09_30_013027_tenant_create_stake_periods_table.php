<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateStakePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('consortiums');
        Schema::create('stake_periods', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('contract_item_id')->nullable();
            $table->unsignedInteger('item_lot_id')->nullable();
            $table->date('start_period')->nullable();
            $table->date('end_period')->nullable();
            $table->boolean('is_confirmed')->default(false);
        
            $table->decimal('initial_bet', 12, 2)->default(0);
            $table->decimal('initial_win', 12, 2)->default(0);
            $table->decimal('initial_progressive_win', 12, 2)->default(0);
            $table->decimal('final_bet', 12, 2)->default(0);
            $table->decimal('final_win', 12, 2)->default(0);
            $table->decimal('final_progressive_win', 12, 2)->default(0);

            $table->decimal('factor_creed', 12, 2)->default(0);
            $table->decimal('maintenance_rate', 12, 2)->default(0);
            $table->decimal('tax_rate', 12, 2)->default(0);
            $table->decimal('uit', 12, 2)->default(0);
            $table->decimal('isc_one_uit', 12, 2)->default(0);
            $table->decimal('isc_three_uit', 12, 2)->default(0);
            $table->decimal('isc_more_than_three_uit', 12, 2)->default(0);

            $table->decimal('result_in', 12, 2)->default(0);
            $table->decimal('result_out', 12, 2)->default(0);
            $table->decimal('result_progressive_win', 12, 2)->default(0);
            $table->decimal('result_crude', 12, 2)->default(0);
            $table->decimal('result_maintenance', 12, 2)->default(0);
            $table->decimal('result_tax', 12, 2)->default(0);
            $table->decimal('result_isc', 12, 2)->default(0);
            $table->decimal('result_net', 12, 2)->default(0);
            $table->decimal('participation_percentage', 12, 2)->default(0);
            $table->decimal('participation_value', 12, 2)->default(0);

            $table->string('currency_type_id')->nullable();
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
        Schema::dropIfExists('stake_periods');
    }
}
