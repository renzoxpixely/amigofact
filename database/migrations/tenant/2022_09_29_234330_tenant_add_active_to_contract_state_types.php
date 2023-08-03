<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Contract\Models\ContractStateType;

class TenantAddActiveToContractStateTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contract_state_types', function (Blueprint $table) {
            $table->boolean('active')->default(true);
        });
        ContractStateType::where('id', ContractStateType::REFUSED)->update(['active'=>false]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_state_types', function (Blueprint $table) {
            $table->dropColumn('active');
        });
        ContractStateType::where('id', ContractStateType::REFUSED)->update(['active'=>true]);
    }
}
