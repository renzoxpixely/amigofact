<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddUnitCostValueToItemLots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_lots', function (Blueprint $table) {
            $table->decimal('unit_cost_value', 12, 2)->default(0);
            $table->string('currency_type_id')->nullable();

            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_lots', function (Blueprint $table) {
            $table->dropForeign(['currency_type_id']);
            $table->dropColumn('unit_cost_value');
            $table->dropColumn('currency_type_id');
        });
    }
}
