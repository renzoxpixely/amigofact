<?php

namespace Modules\Contract\Providers;

use App\Traits\KardexTrait;
use Illuminate\Support\ServiceProvider;
use Modules\Contract\Models\Contract;
use Modules\Contract\Models\ContractStateType;
use Modules\Contract\Models\ContractType;

class InventoryKardexContractServiceProvider extends ServiceProvider
{
    use KardexTrait;
    
    public function register()
    {
    }

    public function boot()
    {
        $this->contract();
    }

    private function contract()
    {
        Contract::updated(function (Contract $contract){
            if($contract->state_type_id == ContractStateType::DELIVERED){
                $contract_type = $contract->contract_type()->first();
                if($contract_type && $contract_type->name == ContractType::SALE){
                    foreach($contract->items as $item){
                        $kardex = $this->saveKardex('contract', $item->item_id, $contract->id, $item->quantity, 'contract');
                        $this->updateStock($item->item_id, $kardex->quantity, true);
                    }
                }else if($contract_type && ($contract_type->name == ContractType::RENTAL || $contract_type->name == ContractType::STAKE)){
                    foreach($contract->items as $item){
                        $kardex = $this->saveKardex('contract', $item->item_id, $contract->id, $item->quantity, 'contract');
                    }
                }
            }
        });
    }


}