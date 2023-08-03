<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractStateType;

class TenantInsertIncompleteDeliveryToContractStateTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('contract_state_types')->insert(['id' => ContractStateType::INCOMPLETE_DELIVERY, 'description' => 'Entrega Incompleta']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('cat_document_types')->where('id', ContractStateType::INCOMPLETE_DELIVERY)->delete();
    }
}
