<?php

namespace App\Http\Requests\Tenant;

use App\Models\Tenant\Purchase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

    public function rules()
    {
        $purchase = Purchase::find($this->input('id'));

        if($this->input('document_type_id')=='07'|| $this->input('document_type_id')=='08' )
        {
            return [
                'supplier_id' => [
                    'required',
                ],
                'number' => [
                    'required',
                    'numeric',
                    Rule::unique('tenant.purchases')->ignore($purchase),
                ],
                'series' => [
                    'required',
                ],
                'date_of_issue' => [
                    'required',
                ],
                'note.series' => [
                    'required',
                ],
                'note.number' => [
                    'required',
                ],
                'note.note_description' => [
                    'required',
                ],
                'items' => [
                    'required',
                    'array',
                ],
                'arrival_date' => [
                    'required_if:has_import,1',
                ]
            ];
        }
        else {
            return [
                'supplier_id' => [
                    'required',
                ],
                'number' => [
                    'required',
                    'numeric',
                    Rule::unique('tenant.purchases')->ignore($purchase),
                ],
                'series' => [
                    'required',
                ],
                'date_of_issue' => [
                    'required',
                ],
                'items' => [
                    'required',
                    'array',
                ],
                'arrival_date' => [
                    'required_if:has_import,1',
                ],
                'sub_item_ids' => [
                    'array'
                ]
            ];
        }
    }
}
