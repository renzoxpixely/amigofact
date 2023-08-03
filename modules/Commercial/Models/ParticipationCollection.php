<?php

namespace Modules\Commercial\Models;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ParticipationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            /** @var \App\Models\Tenant\Participation $row */
            return  $row->getCollectionData();
            /** Pasado al modelo  */
            return [
                'id' => $row->id,
                'months' => $row->months,
                'status' => $row->status,
                'participation' => $row->participation,
                'name' => $row->name,
                'participation_type' => $row->participation_type,
                // 'internal_code' => $row->internal_code,
                // 'document_type' => $row->identity_document_type->description,
                // 'enabled' => (bool) $row->enabled,
                // 'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                // 'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
// Modules\Commercial\Models;