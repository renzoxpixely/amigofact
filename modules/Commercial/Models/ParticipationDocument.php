<?php

namespace Modules\Commercial\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Models\Warehouse;
use App\Models\Tenant\ModelTenant;

class ParticipationDocument extends ModelTenant
{
    protected $fillable = [
        'participation_id',
        'document_name',
        'document_url'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function participation()
    {
        return $this->belongsTo(Participation::class, 'participation_id');
    }
}