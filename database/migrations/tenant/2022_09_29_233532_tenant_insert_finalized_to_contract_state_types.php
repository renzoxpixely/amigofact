<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractStateType;

class TenantInsertFinalizedToContractStateTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('contract_state_types')->insert(['id' => ContractStateType::FINALIZED_TO_BREACH, 'description' => 'Finalizado por Incumplimiento']);
        DB::table('contract_state_types')->insert(['id' => ContractStateType::FINALIZED, 'description' => 'Finalizado']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('cat_document_types')->where('id', ContractStateType::FINALIZED_TO_BREACH)->delete();
        DB::table('cat_document_types')->where('id', ContractStateType::FINALIZED)->delete();
    }
}
