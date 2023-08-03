<?php

namespace Modules\Contract\Models;

use App\Http\Controllers\SearchItemController;
use App\Http\Controllers\Tenant\SaleNoteController;
use App\Http\Requests\Tenant\SaleNoteRequest;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Series;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Modules\Item\Models\ItemLot;

class RentalPeriod extends ModelTenant
{
    protected $fillable = [
        'contract_item_id',
        'item_lot_id',
        'start_period',
        'end_period',
        'currency_type_id',
        'currency_type',

        'amount',
        'code',
        'sale_note_id'
    ];

    protected $casts = [
        'start_period' => 'date',
        'end_period' => 'date',
        'amount' => 'float'
    ];

    public function contract_item()
    {
        return $this->belongsTo(ContractItem::class, 'contract_item_id');
    }

    public function sale_note()
    {
        return $this->belongsTo(ContractItem::class, 'sale_note_id');
    }

    public function item_lot()
    {
        return $this->belongsTo(ItemLot::class, 'item_lot_id');
    }
    
    public function getCurrencyTypeAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCurrencyTypeAttribute($value)
    {
        $this->attributes['currency_type'] = (is_null($value))?null:json_encode($value);
    }

    public function incidents()
    {
        return $this->hasMany(RentalPeriodIncident::class);
    }

    public static function setSaleNote(self $rental_period)
    {
        Carbon::setLocale('es');
        Carbon::setLocale('Spanish_Peru');
        setlocale(LC_ALL, 'Spanish_Peru');

        $contract_item = $rental_period->contract_item()->first();
        $contract = $contract_item->contract()->first();
        $contract_type = $contract->contract_type()->first();
        $time_of_issue = Carbon::now()->format('H:m:s');

        $user = auth()->user();
        $stablisment = Establishment::where('id', '!=', 0)->first();
        if ( !empty($user)) { $stablisment = $user->establishment;}
        $serie = Series::where(['establishment_id' => $stablisment->id,'document_type_id' => '80',])->first();
        
        //Item
        $item_description = $contract_item->item->description ?? null;
        $item = SearchItemController::getServiceItemByDescription($item_description, $rental_period->currency_type_id);
        if(is_null($item)) throw new Exception("Error no se pudo crear y no se encontró el servicio para crear la nota de venta!!");
        //Total Item
        $unit_price = $rental_period->amount;
        $percentage_igv = 18;
        $affectation_igv_type_id='10';
        $unit_value = $unit_price/(($percentage_igv/100)+1);
        $total_base_igv =$unit_value;
        $total_igv = $unit_price-$unit_value;
        $total_taxes = $total_igv;
        $price_type_id = "01";
        $total_value=$unit_value;
        $total=$unit_price;
        $name_product_pdf = $item['description'] . ' - '.($contract_type?$contract_type->name:'');

        $row_item = [
            'item_id' => $item['id'],
            'item' => $item,
            'quantity' => 1,
            'unit_value' => $unit_value,
            'unit_price' => $unit_price,
            'affectation_igv_type_id' => $affectation_igv_type_id,
            'total_base_igv' => $total_base_igv,
            'percentage_igv' => $percentage_igv,
            'total_igv' => $total_igv,
            'total_taxes' => $total_taxes,
            'price_type_id' => $price_type_id,
            'total_value' => $total_value,
            'total' => $total,
            'attributes' => [],
            'discounts' => [],
            'charges' => [],
            'name_product_pdf' => $name_product_pdf,

        ];
        $items = [$row_item];
        
        $data = [
            'customer_id' => $contract->customer_id,
            'customer' => $contract->customer,
            'exchange_rate_sale' => $contract->exchange_rate_sale,
            'currency_type_id' => $rental_period->currency_type_id,
            'date_of_issue' => $rental_period->start_period->format('Y-m-d'),
            'series_id' => $serie->id,
            "payments" => [],

            "prefix" => "NV",
            "items" => $items,

            "establishment_id" => $stablisment->id,
            'due_date' => $rental_period->end_period->format('Y-m-d'),
            'time_of_issue' => $time_of_issue,


            "total_prepayment" => 0,
            "total_charge" => 0,
            "total_discount" => 0,
            "total_free" => 0,
            "total_exportation" => 0,
            "total_taxed" => $unit_value,
            "total_unaffected" => 0,
            "total_exonerated" => 0,


            "total_igv" => $total_igv,
            "total_base_isc" => 0,
            "total_isc" => 0,
            "total_base_other_taxes" => 0,
            "total_other_taxes" => 0,
            "total_taxes" => $total_taxes,
            "total_value" => $total_value,
            "subtotal" => 0,
            "total" => $total,
            "operation_type_id" => null,


            "charges" => null,
            "discounts" => null,
            "attributes" => [],
            "guides" => [],
            'user_rel_suscription_plan_id' => '0',
            'prepayments' => null,


        ];
        $request = new SaleNoteRequest();
        $request->merge($data);

        
        $saleNoteController = new SaleNoteController();
        $saleNoteSaved = $saleNoteController->store($request);

        return $saleNoteSaved;
        /*
        if (isset($saleNoteSaved['data']) && isset($saleNoteSaved['data']['id'])) {
            return (int)$saleNoteSaved['data']['id'];
        }else {
            return null;
        }*/

    }

}