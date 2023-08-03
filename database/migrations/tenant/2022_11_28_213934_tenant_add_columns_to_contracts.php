<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsToContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->integer('period')->nullable()->after('spanish_clauses');
            $table->string('pays_transport')->nullable()->after('period');
            $table->decimal('participation_percentage', 12, 2)->default(0)->after('pays_transport');
            $table->boolean('automatic_renew')->default(true)->after('participation_percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('period');
            $table->dropColumn('pays_transport');
            $table->dropColumn('participation_percentage');
            $table->dropColumn('automatic_renew');
        });
    }
}
