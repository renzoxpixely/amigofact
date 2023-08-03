<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateRentalPeriodIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rental_period_incidents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rental_period_id')->nullable();
            $table->integer('days')->default(1);
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency_type_id', 12, 2)->default(null);
            $table->json('currency_type')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();

            $table->foreign('rental_period_id')->references('id')->on('rental_periods');
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
        Schema::dropIfExists('rental_period_incidents');
    }
}
