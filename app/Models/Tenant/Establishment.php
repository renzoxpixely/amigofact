<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\Country;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\Province;
use Modules\Inventory\Models\Warehouse;

class Establishment extends ModelTenant
{
    public const ESTABLISHMENT_CUSTOMER_ID = 5;
    public const ESTABLISHMENT_CONTRACT_ID = 9;

    protected $with = ['country', 'department', 'province', 'district'];
    protected $fillable = [
        'description',
        'country_id',
        'department_id',
        'province_id',
        'district_id',
        'address',
        'email',
        'telephone',
        'code',
        'trade_address',
        'web_address',
        'aditional_information',
        'customer_id',
        'logo',
        'template_pdf',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function getAddressFullAttribute()
    {
        $address = ($this->address != '-')? $this->address.' ,' : '';
        return "{$address} {$this->department->description} - {$this->province->description} - {$this->district->description}";
    }

    public function customer()
    {
        return $this->belongsTo(Person::class, 'customer_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithMyEstablishment(\Illuminate\Database\Eloquent\Builder $query){
        $user = \Auth::user();
        if(null === $user) {
            $user = new User();
        }
        return $query->where('id',$user->establishment_id);
    }
}
