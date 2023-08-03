<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->enum('participation_type', ['Participacion', 'Renta', 'Venta']);

            //Nuevos campos participation
            $table->integer('months')->unsigned()->nullable();
            $table->enum('status', ['Registrado', 'En negociacion', 'Aceptado'])->nullable();
            $table->date('registered_date')->nullable();
            $table->date('negotiation_date')->nullable();
            $table->date('accepted_date')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->integer('customer_participation')->nullable();
            $table->string('one_way_transportation')->nullable();
            $table->string('return_transport')->nullable();
            $table->text('comments')->nullable();

            //Nuevos campos venta
            
            $table->enum('global_discount_type', ['percentage', 'fixed_amount'])->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('initial_payment', 10, 2)->nullable();
            $table->integer('installment_count')->nullable();
            $table->decimal('interest_percentage', 5, 2)->nullable();

            //Nuevos campos renta

            $table->uuid('external_id');
            $table->unsignedInteger('establishment_id');
            $table->json('establishment');
            $table->char('soap_type_id', 2);
            $table->char('state_type_id', 2);   
            $table->string('prefix'); 
            $table->integer('number')->nullable();
            $table->date('date_of_issue');
            $table->time('time_of_issue');
            $table->date('date_of_due')->nullable();
            $table->date('delivery_date')->nullable();
            // $table->unsignedInteger('customer_id');
            $table->json('customer');
            $table->text('shipping_address')->nullable();
            $table->char('payment_method_type_id', 2)->nullable();   
            $table->string('currency_type_id');
            $table->decimal('exchange_rate_sale', 13, 3);
            $table->decimal('total_prepayment', 12, 2)->default(0);
            $table->decimal('total_charge', 12, 2)->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->decimal('total_exportation', 12, 2)->default(0);
            $table->decimal('total_free', 12, 2)->default(0);
            $table->decimal('total_taxed', 12, 2)->default(0);
            $table->decimal('total_unaffected', 12, 2)->default(0);
            $table->decimal('total_exonerated', 12, 2)->default(0);
            $table->decimal('total_igv', 12, 2)->default(0);
            $table->decimal('total_base_isc', 12, 2)->default(0);
            $table->decimal('total_isc', 12, 2)->default(0);
            $table->decimal('total_base_other_taxes', 12, 2)->default(0);
            $table->decimal('total_other_taxes', 12, 2)->default(0);
            $table->decimal('total_taxes', 12, 2)->default(0);
            $table->decimal('total_value', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->json('charges')->nullable();
            $table->json('discounts')->nullable();
            $table->json('prepayments')->nullable();
            $table->json('guides')->nullable();
            $table->json('related')->nullable();
            $table->json('perception')->nullable();
            $table->json('detraction')->nullable();
            $table->json('legends')->nullable();

            $table->string('filename')->nullable(); 
            $table->string('terms_condition')->nullable(); 
            $table->string('account_number')->nullable(); 
            $table->string('description')->nullable(); 
            $table->string('changed')->default(false); 
            $table->unsignedInteger('quotation_id')->nullable();

            // Foreign key constraints nuevos campos participation
            $table->foreign('customer_id')->references('id')->on('persons');
            //Nuevos campos participation

            $table->timestamps();
        });

        // Ahora, agregamos las columnas con ALTER TABLE
        Schema::table('participations', function (Blueprint $table) {
            $table->unsignedInteger('contract_type_id')->nullable()->after('quotation_id');
            $table->text('english_clauses')->nullable()->after('contract_type_id');
            $table->text('spanish_clauses')->nullable()->after('english_clauses');

            $table->integer('period')->nullable()->after('spanish_clauses');
            $table->string('pays_transport')->nullable()->after('period');
            $table->decimal('participation_percentage', 12, 2)->default(0)->after('pays_transport');
            $table->boolean('automatic_renew')->default(true)->after('participation_percentage');

            $table->unsignedInteger('seller_id')->nullable()->after('user_id');
            
            $table->foreign('contract_type_id')->references('id')->on('contract_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participations');
    }
}

