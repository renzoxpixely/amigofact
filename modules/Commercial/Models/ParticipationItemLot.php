<?php

namespace Modules\Commercial\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Item\Models\ItemLot;

class ParticipationItemLot extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'contract_item_id',
        'item_lot_id',
        'depreciation_start_date',
        'depreciation_end_date',
        'depreciation_percentage',
        'depreciation_accumulated',
        'activation_date'
    ];

    protected $casts = [
        'depreciation_start_date' => 'date',
        'depreciation_end_date' => 'date',
        'depreciation_percentage' => 'float',
        'depreciation_accumulated' => 'float',
        'activation_date' => 'date'
    ];

    public function contract_item()
    {
        return $this->belongsTo(ParticipationItem::class, 'contract_item_id');
    }

    public function item_lot()
    {
        return $this->belongsTo(ItemLot::class, 'item_lot_id');
    }

}