<?php

namespace Modules\Commercial\Models;

use App\Models\Tenant\ModelTenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

// class ParticipationCollection extends ResourceCollection
class ContractType extends ModelTenant
{
    protected $fillable = [
        'name',
        'type',
        'english_clauses',
        'spanish_clauses',
        'factor_creed',
        'maintenance_rate',
        'tax_rate',
        'uit',
        'isc_one_uit',
        'isc_three_uit',
        'isc_more_than_three_uit'
    ];

    protected $casts = [
        'factor_creed' => 'float',
        'maintenance_rate' => 'float',
        'tax_rate' => 'float',
        'uit' => 'float',
        'isc_one_uit' => 'float',
        'isc_three_uit' => 'float',
        'isc_more_than_three_uit' => 'float'
    ];

    public const RENTAL = 'Alquiler';
    public const STAKE = 'Participación';
    public const SALE = 'Venta';

    public const TYPE_SALE = 'sale';
    public const TYPE_MONTHLY = 'monthly';

    public static function getContractTypes()
    {
        return self::get()
            ->transform(function ($row) {

                
                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'type' => $row->type,
                    'english_clauses' => $row->english_clauses,
                    'spanish_clauses' => $row->spanish_clauses,
                    'valid_hall' => in_array($row->name, [self::RENTAL, self::STAKE]) ? true : false,
                    'factor_creed' => $row->factor_creed,
                    'maintenance_rate' => $row->maintenance_rate,
                    'tax_rate' => $row->tax_rate,
                    'uit' => $row->uit,
                    'isc_one_uit' => $row->isc_one_uit,
                    'isc_three_uit' => $row->isc_three_uit,
                    'isc_more_than_three_uit' => $row->isc_more_than_three_uit
                ];
            });
    }

}