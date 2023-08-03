<?php

namespace Modules\Contract\Providers;

use App\Traits\SellerIdTrait;
use Illuminate\Support\ServiceProvider;
use Modules\Contract\Models\Contract;
use Modules\Contract\Models\ContractItemLot;
use Modules\Contract\Models\ContractStateType;
use Modules\Contract\Models\ContractType;
use Modules\Inventory\Traits\InventoryTrait;
use Modules\Item\Models\ItemLot;

class InventoryContractServiceProvider extends ServiceProvider
{
    use InventoryTrait, SellerIdTrait;
    
    public function register()
    {
    }

    public function boot()
    {
        $this->contract();
        $this->seller_id();
        $this->contract_item_lot();
    }

    private function seller_id()
    {
        Contract::creating(function (Contract $model) {
            $this->adjustSellerIdField($model);
        });
    }

    private function contract()
    {
        Contract::updated(function (Contract $contract){
            if($contract->state_type_id == ContractStateType::DELIVERED){
                $contract_type = $contract->contract_type()->first();
                if($contract_type && $contract_type->name == ContractType::SALE){
                    foreach($contract->items as $item){
                        $factor = -1;
                        $this->createInventoryKardex($contract, $item->item_id, $item->quantity * $factor, $item->warehouse_id);
                        $this->updateStock($item->item_id, $item->quantity * $factor, $item->warehouse_id);
                    }
                }else if($contract_type && ($contract_type->name == ContractType::RENTAL || $contract_type->name == ContractType::STAKE)){
                    foreach($contract->items as $item){
                        $hall = $item->model_hall()->first();
                        $warehouse_destination_id = $hall->warehouse_id??null;
                        if($warehouse_destination_id){
                            $lots = $item->lots()->get();
                            //dd($lots);
                            $this->createInventoryTransfer($contract, $item->item_id, $item->quantity, $item->warehouse_id, $warehouse_destination_id, $lots);
                        }
                    }
                }
            }
        });
    }

    private function contract_item_lot()
    {
        ContractItemLot::created(function (ContractItemLot $contract_item_lot) {
            $r = ItemLot::find($contract_item_lot->item_lot_id);
            $r->has_sale = true;
            $r->save();
        });

        ContractItemLot::deleted(function (ContractItemLot $contract_item_lot) {
            $r = ItemLot::find($contract_item_lot->item_lot_id);
            $r->has_sale = false;
            $r->save();
        });
    }

}