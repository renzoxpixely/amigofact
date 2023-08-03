<?php

namespace Modules\Contract\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StakePeriodRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    { 
        
        $id = $this->input('id');
        return [
            'start_period' => [
                'required',
            ],
            'end_period' => [
                'required',
            ],
            'factor_creed' => [
                'required',
            ],
            'maintenance_rate' => [
                'required',
            ],
            'tax_rate' => [
                'required',
            ],
            'uit' => [
                'required',
            ],
            'isc_one_uit' => [
                'required',
            ],
            'isc_three_uit' => [
                'required',
            ],
            'isc_more_than_three_uit' => [
                'required',
            ],
			'participation_percentage' => [
				'required',
			]
        ];

    }
}
