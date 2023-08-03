<?php

namespace Modules\Contract\Http\Controllers;

use App\Http\Resources\Tenant\SaleNoteCollection;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\SaleNote;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Contract\Http\Requests\StakePeriodRequest;
use Modules\Contract\Http\Resources\StakePeriodCollection;
use Modules\Contract\Http\Resources\ContractTypeResource;
use Modules\Contract\Http\Resources\StakePeriodResource;
use Modules\Contract\Models\StakePeriod;
use Modules\Contract\Models\ContractType;

class StakePeriodController extends Controller
{

    public function records(Request $request)
    {
        $contract_item_id = $request->contract_item_id ?? null;
        $item_lot_id = $request->item_lot_id ?? null;
        //dd($contract_item_id, $item_lot_id);
        $records = StakePeriod::where('contract_item_id', $contract_item_id)->where('item_lot_id', $item_lot_id);

        return new StakePeriodCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function paymentReceipts(Request $request)
    {
        $contract_item_id = $request->contract_item_id ?? null;
        $item_lot_id = $request->item_lot_id ?? null;
        
        $records = SaleNote::with(['stake_period'])->whereHas('stake_period', function($query) use ($contract_item_id, $item_lot_id){
            $query->where('contract_item_id', $contract_item_id)->where('item_lot_id', $item_lot_id);
        })->latest();
        return new SaleNoteCollection($records->paginate(config('tenant.items_per_page')));
    }
	
	public function record($id)
    {
        $record = new StakePeriodResource(StakePeriod::findOrFail($id));
		
        return $record;
    }

    public function tables($contract_type_id) {

        $record = new ContractTypeResource(ContractType::findOrFail($contract_type_id));

        $factor_creed = $record->factor_creed ?? 0;
        $maintenance_rate = $record->maintenance_rate ?? 0;
        $tax_rate = $record->tax_rate ?? 0;
        $uit = $record->uit ?? 0;
        $isc_one_uit = $record->isc_one_uit ?? 0;
        $isc_three_uit = $record->isc_three_uit ?? 0;
        $isc_more_than_three_uit = $record->isc_more_than_three_uit ?? 0;
        $currency_type = CurrencyType::find('PEN');

        return compact('factor_creed', 'maintenance_rate', 'tax_rate', 'uit', 'isc_one_uit', 'isc_three_uit', 'isc_more_than_three_uit', 'currency_type');
    }

    public function store(StakePeriodRequest $request)
    {
        $response = ['success' => false];
        try{

            $id = (int)$request->input('id');
            $stake_period = StakePeriod::firstOrNew(['id' => $id]);
            $stake_period->fill($request->all());
            if($request->is_confirmed){
                $sale_note_id = null;
                $response_sale_note = StakePeriod::setSaleNote($stake_period);
                if(!$response_sale_note['success']) throw new Exception($response_sale_note['message']);
                if(isset($response_sale_note['data']) && isset($response_sale_note['data']['id'])) $sale_note_id = (int)$response_sale_note['data']['id'];

                $stake_period->sale_note_id = $sale_note_id;
            }
            $stake_period->save();

            $response['success'] = true;
            $response['message'] = ($request->is_confirmed) ? 'Periodo confirmado con éxito' : 'Periodo guardado con éxito';
            $response['data'] = $stake_period;
            return response()->json($response);

        }catch(Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }

    }
}