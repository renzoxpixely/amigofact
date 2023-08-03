<?php

namespace Modules\Contract\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Contract\Models\ContractType;

class ContractTypeCollection extends ResourceCollection
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

            $valid = in_array($row->name, [ContractType::RENTAL, ContractType::STAKE, ContractType::SALE]);
            $is_stake = $row->name == ContractType::STAKE;

            return [
                'id' => $row->id,
                'name' => $row->name,
                'btn_remove' => $valid ? false : true,
                'is_stake' => $is_stake,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });

    }
    
}
