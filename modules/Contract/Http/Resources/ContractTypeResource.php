<?php

namespace Modules\Contract\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Contract\Models\ContractType;

class ContractTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $valid = in_array($this->name, [ContractType::RENTAL, ContractType::STAKE, ContractType::SALE]);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'english_clauses' => $this->english_clauses,
            'spanish_clauses' => $this->spanish_clauses,
            'factor_creed' => $this->factor_creed,
            'maintenance_rate' => $this->maintenance_rate,
            'tax_rate' => $this->tax_rate,
            'uit' => $this->uit,
            'isc_one_uit' => $this->isc_one_uit,
            'isc_three_uit' => $this->isc_three_uit,
            'edit_all' => $valid ? false : true,
            'isc_more_than_three_uit' => $this->isc_more_than_three_uit
        ];
    }
}