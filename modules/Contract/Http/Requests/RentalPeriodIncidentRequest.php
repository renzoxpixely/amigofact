<?php

namespace Modules\Contract\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RentalPeriodIncidentRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    { 
        return [
            'rental_period_id' => [
                'required',
            ],
            'days' => [
                'required',
                'numeric',
                'min:1'
            ]
        ];

    }
}
