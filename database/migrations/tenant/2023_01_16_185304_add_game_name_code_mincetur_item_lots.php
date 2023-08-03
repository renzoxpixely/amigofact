<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGameNameCodeMinceturItemLots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_lots', function(Blueprint $table) {
            $table->unsignedInteger('game_id')->after('series')->nullable();
            $table->bigInteger('mincetur_code')->after('game_id')->nullable();

            $table->foreign('game_id')->references('id')->on('items')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_lots', function(Blueprint $table) {
            $table->dropForeign('game_id');
            $table->dropColumn('game_id');
            $table->dropColumn('mincetur_code');
        });
    }
}
