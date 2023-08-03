<?php

namespace Modules\Commercial\Models;

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Item;
use App\Models\Tenant\PersonHall;
use Modules\Inventory\Models\Warehouse;
use App\Traits\AttributePerItems;

class ParticipationItem extends ModelTenant
{
    use AttributePerItems;
    protected $with = ['affectation_igv_type', 'system_isc_type', 'price_type'];
    public $timestamps = false;

    protected $fillable = [
        'contract_id',
        'item_id',
        'item',
        'quantity',
        'unit_value',

        'affectation_igv_type_id',
        'total_base_igv',
        'percentage_igv',
        'total_igv',

        'system_isc_type_id',
        'total_base_isc',
        'percentage_isc',
        'total_isc',

        'total_base_other_taxes',
        'percentage_other_taxes',
        'total_other_taxes',
        'total_taxes',

        'price_type_id',
        'unit_price',

        'total_value',
        'total_charge',
        'total_discount',
        'total',

        'attributes',
        'charges',
        'discounts',

        'hall_id',
        'hall',
        'depreciation_start_date',
        'depreciation_end_date',
        'depreciation_percentage',
        'depreciation_accumulated',
        'activation_date',
        'warehouse_id'
    ];

    protected $casts = [
        'depreciation_start_date' => 'date',
        'depreciation_end_date' => 'date',
        'depreciation_percentage' => 'float',
        'depreciation_accumulated' => 'float',
        'activation_date' => 'date'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function (self $item) {
            $contract = $item->contract()->first();
            if ($contract !== null) {
                $warehouse = Warehouse::where('establishment_id', $contract->establishment_id)->first();
                if ($warehouse !== null) {
                    $item->warehouse_id = $warehouse->id;
                }
            }
        });
    }

    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }

    public function getAttributesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = (is_null($value))?null:json_encode($value);
    }

    public function getChargesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setChargesAttribute($value)
    {
        $this->attributes['charges'] = (is_null($value))?null:json_encode($value);
    }

    public function getDiscountsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDiscountsAttribute($value)
    {
        $this->attributes['discounts'] = (is_null($value))?null:json_encode($value);
    }

    public function affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'affectation_igv_type_id');
    }

    public function system_isc_type()
    {
        return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
    }

    public function price_type()
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }

    public function relation_item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function contract()
    {
        return $this->belongsTo(Participation::class, 'contract_id');
    }

    public function model_hall()
    {
        return $this->belongsTo(PersonHall::class, 'hall_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function getHallAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setHallAttribute($value)
    {
        $this->attributes['hall'] = (is_null($value))?null:json_encode($value);
    }

    public function lots()
    {
        return $this->hasMany(ParticipationItemLot::class);
    }

}
