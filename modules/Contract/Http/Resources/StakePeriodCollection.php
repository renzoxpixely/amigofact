<?php

namespace Modules\Contract\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StakePeriodCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
        

            return [
                'id' => $row->id, 
                'contract_item_id' => $row->contract_item_id,
                'item_lot_id' => $row->item_lot_id,
                'start_period' => $row->start_period->format('Y-m-d'),
                'end_period' => $row->end_period->format('Y-m-d'),
                'is_confirmed' => $row->is_confirmed,
                'initial_bet' => number_format($row->initial_bet,2),
                'initial_win' => number_format($row->initial_win,2),
                'initial_progressive_win' => number_format($row->initial_progressive_win,2),
                'final_bet' => number_format($row->final_bet,2),
                'final_win' => number_format($row->final_win,2),
                'final_progressive_win' => number_format($row->final_progressive_win,2),

                'result_in' => number_format($row->result_in,2),
                'result_out' => number_format($row->result_out,2),
                'result_progressive_win' => number_format($row->result_progressive_win,2),
                'result_crude' => number_format($row->result_crude,2),
                'result_maintenance' => number_format($row->result_maintenance,2),
                'result_tax' => number_format($row->result_tax,2),
                'result_isc' => number_format($row->result_isc,2),
                'result_net' => number_format($row->result_net,2),
                'participation_percentage' => number_format($row->participation_percentage,2),
                'participation_value' => number_format($row->participation_value,2),
            ];
        });
    }
    
}
