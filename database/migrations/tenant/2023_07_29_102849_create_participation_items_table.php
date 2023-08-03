<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participation_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('participation_id');
            $table->unsignedInteger('item_id');
            $table->json('item');
            $table->decimal('quantity',12,4);
            $table->decimal('unit_value', 16, 6);

            $table->string('affectation_igv_type_id');
            $table->decimal('total_base_igv', 12, 2);
            $table->decimal('percentage_igv', 12, 2);
            $table->decimal('total_igv', 12, 2);

            $table->string('system_isc_type_id')->nullable();
            $table->decimal('total_base_isc', 12, 2)->default(0);
            $table->decimal('percentage_isc', 12, 2)->default(0);
            $table->decimal('total_isc', 12, 2)->default(0);

            $table->decimal('total_base_other_taxes', 12, 2)->default(0);
            $table->decimal('percentage_other_taxes', 12, 2)->default(0);
            $table->decimal('total_other_taxes', 12, 2)->default(0);
            $table->decimal('total_taxes', 12, 2);

            $table->string('price_type_id');
            $table->decimal('unit_price', 16, 6);

            $table->decimal('total_value', 12, 2);
            $table->decimal('total_charge', 12, 2)->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->json('attributes')->nullable();
            $table->json('discounts')->nullable();
            $table->json('charges')->nullable();
            
            $table->date('depreciation_start_date')->nullable();
            $table->date('depreciation_end_date')->nullable();
            $table->decimal('depreciation_percentage', 12, 2)->default(0);
            $table->decimal('depreciation_accumulated', 12, 2)->default(0);
            $table->date('activation_date')->nullable();
            
            $table->foreign('participation_id')->references('id')->on('participations')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('affectation_igv_type_id')->references('id')->on('cat_affectation_igv_types');
            $table->foreign('system_isc_type_id')->references('id')->on('cat_system_isc_types');
            $table->foreign('price_type_id')->references('id')->on('cat_price_types');
        });



         // Ahora, agregamos las columnas con ALTER TABLE
        Schema::table('participation_items', function (Blueprint $table) {
            $table->unsignedInteger('hall_id')->nullable();
            $table->json('hall')->nullable();
            $table->foreign('hall_id')->references('id')->on('person_halls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participation_items');
    }
}
