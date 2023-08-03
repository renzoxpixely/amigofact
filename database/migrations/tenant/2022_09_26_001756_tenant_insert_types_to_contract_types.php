<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractType;

class TenantInsertTypesToContractTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $contract_types = [ContractType::RENTAL, ContractType::STAKE, ContractType::SALE];
        $data = ContractType::select()->whereIn('name', $contract_types)->get();
        foreach($contract_types as $row){
            $find = $data->filter(function($item) use ($row) { return $item->name == $row;})->first();
            if(!$find) ContractType::create(['name'=>$row, 'type' => ContractType::TYPE_SALE]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
