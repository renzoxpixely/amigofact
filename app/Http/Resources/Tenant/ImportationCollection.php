<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;
use phpDocumentor\Reflection\Types\Mixed_;

class ImportationCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            return ['import_order_task' => $row->import_order_task,
                'id' => $row->id,
                'supplier' => $row->supplier,
                'number_proforma' => $row->number_proforma,
                'amount_proforma' => $row->amount_proforma,
                'date_periodo' => $row->date_periodo,
                'date_of_issue' => $row->date_of_issue,
                'arrival_date' => $row->arrival_date,
                'sale_reference' => $row->sale_reference,
                'ref_customs_agent' => $row->ref_customs_agent,
                'transport_type' => $row->transport_type,
                'transport_code' => $row->transport_code,
                'dam' => $row->dam,
                'comments' => $row->comments
            ];
        });

    }
}
