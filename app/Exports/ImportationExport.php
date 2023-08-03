<?php

namespace App\Exports;

use App\Models\Tenant\Importation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ImportationExport implements FromCollection, WithHeadings, WithMapping
{
    private $index = 0;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Importation::all();
    }

    public function headings(): array
    {
        return [
            '#',
            'Import order task',
            'Proveedor',
            'Tipo de documento',
            'Número proforma',
            'Monto proforma',
            'Periodo',
            'Fecha de emissión',
            'Fecha de arribo',
            'Ref. liquidación',
            'Ref. agente aduanas',
            'Conocimiento de embarque',
            'Nro. de conocimiento',
            'DAM',
            'Comentarios'
        ];
    }

    /**
     * @var Importation $invoice
     */
    public function map($row): array
    {

        return [
            ++$this->index,
            $row->import_order_task,
            $row->supplier->name,
            $row->document_type->description,
            $row->number_proforma,
            $row->amount_proforma,
            $row->date_periodo,
            $row->date_of_issue,
            $row->arrival_date,
            $row->sale_reference,
            $row->ref_customs_agent,
            $row->transport_type,
            $row->transport_code,
            $row->dam,
            $row->comments
        ];
    }
}
