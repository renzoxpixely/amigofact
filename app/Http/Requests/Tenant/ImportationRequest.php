<?php

namespace App\Http\Requests\Tenant;

use App\Models\Tenant\Importation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        $importation = Importation::find($this->input('id'));

        return [
            'supplier_id' => [
                'required',
                'numeric'
            ],
            'date_of_issue' => [
                'required',
                'date'
            ],
            'date_periodo' => [
                'required',
                'date'
            ],
            'arrival_date' => [
                'nullable',
                'date'
            ],
            'import_order_task' => [
                'required',
                Rule::unique('tenant.importations')->ignore($importation),
                'string'
            ],
            'number_proforma' => [
                'nullable',
                'string'
            ],
            'amount_proforma' => [
                'nullable',
                'numeric'
            ],
            'sale_reference' => [
                'nullable',
                'string'
            ],
            'ref_customs_agent' => [
                'nullable',
                'string'
            ],
            'transport_type' => [
                'required',
                Rule::in(['BL', 'AWB']),
            ],
            'transport_code' => [
                'required',
                'alpha_dash'
            ],
            'dam' => [
                'nullable',
                'alpha_dash'
            ],
            'uploadedFiles.*' => 'required|max:5000|file',
        ];
    }
}
