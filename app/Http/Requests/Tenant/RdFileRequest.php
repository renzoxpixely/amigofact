<?php

namespace App\Http\Requests\Tenant;

class RdFileRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rd_filename' => [
                'required',
                'string'
                ],
            'rd_file' => [
                'required',
                'file',
                'max:5000'
            ],
            'rd_lot_ids' => [
              'required',
              'array'
            ],
            'rd_lot_ids.*' => [
                'int'
            ]
        ];
    }

    public function attributes()
    {
        return [
            'rd_filename' => 'nombre RD',
            'rd_file' => 'archivo',
            'rd_lot_ids' => 'series seleccionadas'
        ];
    }
}
