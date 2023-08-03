<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToPurchaseItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->boolean('prorated_by_ad_valorem')->default(false);
            $table->boolean('prorated_by_weight')->default(false);
            $table->date('enters_warehouse_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn('prorated_by_ad_valorem');
            $table->dropColumn('prorated_by_weight');
            $table->dropColumn('enters_warehouse_at');
        });
    }
}
