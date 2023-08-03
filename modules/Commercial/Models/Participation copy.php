<?php

namespace Modules\Commercial\Models;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Document;
use App\Models\Tenant\User;
use App\Models\Tenant\SoapType;
// use App\Models\Tenant\StateType;
use App\Models\Tenant\Person;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\InventoryKardex;
use App\Models\Tenant\Quotation;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\ModelTenant;
use App\Traits\SellerIdTrait;

class Participation extends ModelTenant
{
    use SellerIdTrait;

    // protected $with = ['user', 'soap_type', 'state_type', 'currency_type', 'items', 'payments'];

    protected $fillable = [
        'id',
        'months',
        'status',
        'registered_date',
        'negotiation_date',
        'accepted_date',
        'customer_id',
        'one_way_transportation',
        'return_transport',
        'comments'
    ];

    // protected $casts = [
    //     'number' => 'int',
    //     'date_of_issue' => 'date',
    //     'date_of_due' => 'date',
    //     'delivery_date' => 'date',
    //     'period' => 'int',
    //     'participation_percentage' => 'float',
    //     'automatic_renew' => 'bool'
    // ];

    function tipocontrato()
    {
        return $this->hasOne(ContractType::class, 'id', 'contract_type_id');
    }

    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getCustomerAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value))?null:json_encode($value);
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

    public function getPrepaymentsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setPrepaymentsAttribute($value)
    {
        $this->attributes['prepayments'] = (is_null($value))?null:json_encode($value);
    }

    public function getGuidesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setGuidesAttribute($value)
    {
        $this->attributes['guides'] = (is_null($value))?null:json_encode($value);
    }

    public function getRelatedAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setRelatedAttribute($value)
    {
        $this->attributes['related'] = (is_null($value))?null:json_encode($value);
    }

    public function getPerceptionAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setPerceptionAttribute($value)
    {
        $this->attributes['perception'] = (is_null($value))?null:json_encode($value);
    }

    public function getDetractionAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDetractionAttribute($value)
    {
        $this->attributes['detraction'] = (is_null($value))?null:json_encode($value);
    }

    public function getLegendsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = (is_null($value))?null:json_encode($value);
    }

    public function getIdentifierAttribute()
    {
        return $this->prefix.'-'.$this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(ContractStateType::class, 'state_type_id');
    }

    public function person() {
        return $this->belongsTo(Person::class, 'customer_id');
    }


    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function items()
    {
        return $this->hasMany(ContractItem::class);
    }


    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function getNumberToLetterAttribute()
    {
        $legends = $this->legends;
        $legend = collect($legends)->where('code', '1000')->first();
        return $legend->value;
    }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->prefix.'-'.($this->number ?? $this->id);
    }

    public function scopeWhereStateTypeAccepted($query)
    {
        return $query->whereIn('state_type_id', ['01']);
    }

    public function payments()
    {
        return $this->hasMany(ContractPayment::class);
    }

    public function scopeWhereNotChanged($query)
    {
        return $query->where('changed', false);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id')->withDefault([
            'name' => ''
        ]);
    }

    // public function contract_type()
    // {
    //     return $this->belongsTo(ContractType::class, 'contract_type_id');
    // }

    // public function document()
    // {
    //     return $this->belongsTo(Document::class, 'document_id');
    // }

    // public function inventory_kardex()
    // {
    //     return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    // }


    public function getCollectionData($withFullAddress = false, $childrens = false)
    {

        $addresses = $this->addresses;
        if ($withFullAddress == true) {
            $addresses = collect($addresses)->transform(function ($row) {
                return $row->getCollectionData();
            });
        }
        $person_halls = $this->halls;
        $person_halls = collect($person_halls)->transform(function ($row) {
            return $row->getCollectionData();
        });
        
        $user = $this->user;
        if($user) $user = ['name' => $user->name, 'email' => $user->email];

        $person_type_descripton = '';
        if ($this->person_type !== null) {
            $person_type_descripton = $this->person_type->description;
        }


        $data = [
            'id' => $this->id,
            // 'description' => $this->number . ' - ' . $this->name,
            'months' => $this->months,
            'status' => $this->status,
            'participation' => $this->participation,
            'customer_id' => $this->customer_id,
            'name' => $this->name,
            // 'number' => $this->number,
            // 'identity_document_type_id' => $this->identity_document_type_id,
            // 'identity_document_type_code' => $this->identity_document_type->code,
            // 'address' => $this->address,
            // 'internal_code' => $this->internal_code,
            // 'observation' => $this->observation,
            // 'zone' => $this->zone,
            // 'website' => $this->website,
            // 'document_type' => $this->identity_document_type->description,
            // 'enabled' => (bool)$this->enabled,
            // 'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            // 'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            // 'type' => $this->type,
            // 'trade_name' => $this->trade_name,
            // 'country_id' => $this->country_id,
            // 'department_id' => $this->department_id,
            // 'province_id' => $this->province_id,
            // 'district_id' => $this->district_id,
            // 'telephone' => $this->telephone,
            // 'email' => $this->email,
            // 'perception_agent' => (bool)$this->perception_agent,
            // 'percentage_perception' => $this->percentage_perception,
            // 'state' => $this->state,
            // 'condition' => $this->condition,
            // 'person_type_id' => $this->person_type_id,
            // 'person_type' => $person_type_descripton,
            // 'contact' => $this->contact,
            // 'comment' => $this->comment,
            // 'addresses' => $addresses,
            // 'parent_id' => $this->parent_id,
            // 'credit_days' => (int)$this->credit_days,
            // 'childrens' => [],
            // 'edad'=>$this->edad,
            // 'person_halls' => $person_halls,
            // 'halls' => $person_halls,
            'user' => $user,
            'user_id' => $this->user_id
        ];
        if($childrens == true){
            $child = $this->children_person->transform(function($row){
                return $row->getCollectionData();
            });
            $data['childrens'] = $child;
            $parent = null;
            if($this->parent_person) {
                $parent = $this->parent_person->getCollectionData();
            }

            $data['parent'] = $parent;

        }

        return $data;
    }
}
