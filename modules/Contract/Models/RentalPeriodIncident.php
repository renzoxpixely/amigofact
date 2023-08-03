<?php

namespace Modules\Contract\Models;

use App\Models\Tenant\ModelTenant;
use Modules\Item\Models\ItemLot;

class RentalPeriodIncident extends ModelTenant
{
    protected $fillable = [
        'rental_period_id',
        'days',
        'amount',
        'currency_type_id',
        'currency_type',
    ];

    protected $casts = [
        'days' => 'integer',
        'amount' => 'float'
    ];

    public function rental_period()
    {
        return $this->belongsTo(RentalPeriod::class, 'rental_period_id');
    }
    
    public function getCurrencyTypeAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCurrencyTypeAttribute($value)
    {
        $this->attributes['currency_type'] = (is_null($value))?null:json_encode($value);
    }


}