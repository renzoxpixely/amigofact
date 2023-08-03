<?php

namespace Modules\Contract\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContractTypeRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    { 
        
        $id = $this->input('id');
        return [
             /*
            'name' => [
                'required',
            ],
            'type' => [
                'required',
            ],
            'english_clauses' => [
                'required',
            ],
            'spanish_clauses' => [
                'required',
            ]*/
        ];

    }
}
