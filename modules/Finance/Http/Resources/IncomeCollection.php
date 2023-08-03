<?php

namespace Modules\Finance\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class IncomeCollection extends ResourceCollection
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

            $payment = $row->payments->first();
            //dd($payment);
            $payment_method_type_description = $payment->payment_method_type->description??null;
            $destination_description = ($payment->global_payment) ? $payment->global_payment->destination_description:null;


            return [
                'id' => $row->id,
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                'number' => $row->number,
                'customer_name' => $row->customer, 
                'currency_type_id' => $row->currency_type_id,
                'state_type_id' => $row->state_type_id,
                'total' => $row->total,
                'income_type_description' => $row->income_type->description,
                'income_reason_description' => $row->income_reason->description,

                'payment_method_type_description' => $payment_method_type_description,
                'destination_description' => $destination_description,
                'reference' => $payment->reference??null,

                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }

}
