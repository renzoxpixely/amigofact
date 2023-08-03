<?php

namespace Modules\Contract\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Contract\Models\ContractType;

class ContractCollection extends ResourceCollection
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
            
            $contract_type_name = $row->contract_type->name ?? null;

            return [
                'id' => $row->id, 
                'soap_type_id' => $row->soap_type_id,
                'external_id' => $row->external_id,
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                'delivery_date' => ($row->delivery_date) ? $row->delivery_date->format('Y-m-d') : null,
                'number_full' => $row->number_full,
                'user_name' => $row->user->name,
                'customer_name' => $row->customer->name,
                'customer_number' => $row->customer->number,
                'currency_type_id' => $row->currency_type_id,
                'total_exportation' => number_format($row->total_exportation,2),
                'total_free' => number_format($row->total_free,2),
                'total_unaffected' => number_format($row->total_unaffected,2),
                'total_exonerated' => number_format($row->total_exonerated,2),
                'total_taxed' => number_format($row->total_taxed,2),
                'total_igv' => number_format($row->total_igv,2),
                'total' => number_format($row->total,2),
                'state_type_id' => $row->state_type_id, 
                'state_type_description' => $row->state_type->description, 
                'quotation_number_full' => ($row->quotation) ? $row->quotation->number_full:null,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
                'contract_type' => $row->contract_type,
                'document_id' => $row->document_id,
                'btn_generate' => $contract_type_name == ContractType::SALE,
				'invoice' => $row->document ? "{$row->document->series}-{$row->document->number}" : "-",
                'participation_type' => $row->participation_type,
                'status' => $row->status
            ];
        });
    }
    
}
