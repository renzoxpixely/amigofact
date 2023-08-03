<?php

namespace Modules\Contract\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RentalPeriodCollection extends ResourceCollection
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
            $days = 0;
            foreach($row->incidents as $incident){
                $days += $incident->days;
            }

            return [
                'id' => $row->id, 
                'contract_item_id' => $row->contract_item_id,
                'item_lot_id' => $row->item_lot_id,
                'start_period' => $row->start_period->format('Y-m-d'),
                'end_period' => $row->end_period->format('Y-m-d'),
                'days' => $days,
                'code' => $row->code,
                'amount' => number_format($row->amount,2),
                'currency_type_id' => $row->currency_type_id,
                'currency_type' => $row->currency_type,
                'sale_note_id' => $row->sale_note_id
            ];
        });
    }
    
}
