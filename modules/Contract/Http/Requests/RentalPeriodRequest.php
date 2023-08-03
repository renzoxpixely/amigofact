<?php

namespace Modules\Contract\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RentalPeriodRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    { 
        return [
            'start_period' => [
                'required',
            ],
            'months' => [
                'required',
                'numeric',
                'min:1'
            ],
            'contract_item_id' => [
                'required',
            ],
            'item_lot_id' => [
                'required',
            ]
        ];

    }
}
