<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddCurrencyTypeToConsortiums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consortiums', function (Blueprint $table) {
            $table->string('currency_type_id')->nullable();
            $table->json('currency_type')->nullable();
            $table->unsignedInteger('sale_note_id')->nullable();

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
        Schema::table('consortiums', function (Blueprint $table) {
            $table->dropForeign(['currency_type_id']);
            $table->dropForeign(['sale_note_id']);
            $table->dropColumn('currency_type_id');
            $table->dropColumn('currency_type');
            $table->dropColumn('sale_note_id');
        });
    }
}
