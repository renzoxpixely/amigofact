<?php

namespace Modules\Commercial\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Commercial\Models\Participation;
use Modules\Commercial\Models\CommercialType;

class CommercialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $contract = Participation::with(['items.lots.item_lot'])
        ->where('id', $this->id)
        ->get()
        ->transform(function ($row, $key) {
            return self::getTransformParticipationItem($row);
        })
        ->first();


        $contract->payments = self::getTransformPayments($contract->payments);

        return [
            'id' => $this->id,
            'external_id' => $this->external_id,  
            'identifier' => $this->identifier,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'), 
            'customer_id' => $this->customer_id,
            'customer_email' => $this->customer->email,
            'contract' => $contract
        ];
    }



    public static function getTransformParticipationItem($rows){
        return $rows->transform(function($row, $key){ 
            $lots = $row->lots;
            $row = $row->toArray();
            //if($row['id'] == 33) dd($row);
            $row['depreciation'] = [
                'start_date' => $row['depreciation_start_date'] ? Carbon::parse($row['depreciation_start_date'])->format('Y-m-d') : null,
                'end_date' => $row['depreciation_end_date'] ? Carbon::parse($row['depreciation_end_date'])->format('Y-m-d') : null,
                'percentage' => $row['depreciation_percentage']??0,
                'accumulated' => 0,//$row['depreciation_accumulated']??0,
            ];
            $row['lots'] = self::getTransformParticipationItemLot($lots);
            
            $row['activation_date'] = $row['activation_date'] ? Carbon::parse($row['activation_date'])->format('Y-m-d') : null;
            $row['edit'] = false;
            $row['loading'] = false;

            unset($row['depreciation_start_date']);
            unset($row['depreciation_end_date']);
            unset($row['depreciation_percentage']);
            unset($row['depreciation_accumulated']);

            return $row;
        });
    }
	
	private static function getTransformItemsMachines($items){
		$machines = [];
		foreach($items as $item){
			$push = $item;
			$push['description'] = $item['item']->description;
			$push['unit_price'] = round(floatval($push['unit_price']),2);
            /** */
            $push['contract_item_lot_id'] = null;
            $push['lot'] = null;
            /** */
			if($item['item']->parent_id != null) continue;
			if(count($push['lots']) > 0){
				//dd($item->lots);
				foreach($push['lots'] as $lot){
                    $push['contract_item_lot_id'] = $lot['id'];
                    $push['depreciation'] = $lot['depreciation'];
                    $push['activation_date'] = $lot['activation_date'];
                    $push['lot'] = [
                        'id' => $lot['id'],
                        'item_lot_id' => $lot['item_lot_id'],
                        'series' => $lot['series']
                    ];
                    /** */
                    unset($push['lots']);
					$machines[] = $push;
				}
			}else{
                unset($push['lots']);
				$machines[] = $push;
			}
		}
		return $machines;
	}

    public static function getTransformParticipationItemLot($rows){
        return $rows->transform(function($row, $key){ 
            return [
                'id' => $row->id,
				'item_lot_id' => $row->item_lot->id ?? null,
                'series' => $row->item_lot->series ?? null,
                'depreciation' => [
                    'start_date' => $row->depreciation_start_date ? $row->depreciation_start_date->format('Y-m-d') : null,
                    'end_date' => $row->depreciation_end_date ? $row->depreciation_end_date->format('Y-m-d') : null,
                    'percentage' => $row->depreciation_percentage ?? 0,
                    'accumulated' => 0
                ],
                'activation_date' => $row->activation_date ? $row->activation_date->format('Y-m-d') : null
            ];
        });
    }

    
    public static function getTransformPayments($payments){
        
        return $payments->transform(function($row, $key){ 
            return [
                'id' => $row->id, 
                'contract_id' => $row->contract_id, 
                'date_of_payment' => $row->date_of_payment->format('Y-m-d'), 
                'payment_method_type_id' => $row->payment_method_type_id, 
                'has_card' => $row->has_card, 
                'card_brand_id' => $row->card_brand_id, 
                'reference' => $row->reference, 
                'payment' => $row->payment, 
                'payment_method_type' => $row->payment_method_type, 
                'payment_destination_id' => ($row->global_payment) ? ($row->global_payment->type_record == 'cash' ? 'cash':$row->global_payment->destination_id):null, 
            ];
        }); 

    }

}
