<?php

namespace Modules\Contract\Http\Resources;

use App\Models\Tenant\Catalogs\CurrencyType;
use Illuminate\Http\Resources\Json\JsonResource;

class StakePeriodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		$currency_type = $this->currency_type;
		if(is_null($currency_type)){
			$currency_type = CurrencyType::find('PEN');
		}


        return [
			'id' => $this->id, 
			'contract_item_id' => $this->contract_item_id,
			'item_lot_id' => $this->item_lot_id,
			'start_period' => $this->start_period->format('Y-m-d'),
			'end_period' => $this->end_period->format('Y-m-d'),
			'is_confirmed' => $this->is_confirmed,
			'initial_bet' => $this->initial_bet,
			'initial_win' => $this->initial_win,
			'initial_progressive_win' => $this->initial_progressive_win,
			'final_bet' => $this->final_bet,
			'final_win' => $this->final_win,
			'final_progressive_win' => $this->final_progressive_win,
			
			'factor_creed' => $this->factor_creed,
			'maintenance_rate' => $this->maintenance_rate,
			'tax_rate' => $this->tax_rate,
			'uit' => $this->uit,
			'isc_one_uit' => $this->isc_one_uit,
			'isc_three_uit' => $this->isc_three_uit,
			'isc_more_than_three_uit' => $this->isc_more_than_three_uit,

			'currency_type_id' => $currency_type->id,
			'currency_type' => $currency_type,

			'result_in' => $this->result_in,
			'result_out' => $this->result_out,
			'result_progressive_win' => $this->result_progressive_win,
			'result_crude' => $this->result_crude,
			'result_maintenance' => $this->result_maintenance,
			'result_tax' => $this->result_tax,
			'result_isc' => $this->result_isc,
			'result_net' => $this->result_net,
			'participation_percentage' => $this->participation_percentage,
			'participation_value' => $this->participation_value,
		];
    }
}