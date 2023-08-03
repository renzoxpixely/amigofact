<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\Province;


class PersonHall extends ModelTenant
{
    public const HALL_CONTRACT_ID = 999;
    public const HALL_CONTRACT_NAME = 'Almacén con Contrato';
    
    protected $table = 'person_halls';
    protected $with = [];
    public $timestamps = false;
    protected $fillable = [
        'person_id',
        'name',
        'department_id',
        'province_id',
        'district_id',
        'address',
        'district_id',
        'warehouse_id'
    ];

    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @return array
     */
    public function getCollectionData() {
        return [
			'id' => $this->id,
            'person_id' => $this->person_id,
            'name' => $this->name,
            'department_id' => $this->department_id,
            'province_id' => $this->province_id,
            'district_id' => $this->district_id,
            'address' => $this->address,
            'warehouse_id' => $this->warehouse_id

        ];
    
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function getAddressFullAttribute()
    {
        $address = trim($this->address);
        $address = ($address === '-' || $address === '')?'':$address.' ,';
        if ($address === '') {
            return '';
        }
        return "{$address} {$this->department->description} - {$this->province->description} - {$this->district->description}";
    }
}
