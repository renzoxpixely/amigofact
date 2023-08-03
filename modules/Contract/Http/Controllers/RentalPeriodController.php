<?php

namespace Modules\Contract\Http\Controllers;

use App\Http\Resources\Tenant\SaleNoteCollection;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\SaleNote;
use Carbon\Carbon;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Contract\Http\Requests\RentalPeriodIncidentRequest;
use Modules\Contract\Http\Requests\RentalPeriodRequest;
use Modules\Contract\Http\Resources\RentalPeriodCollection;
use Modules\Contract\Models\ContractItem;
use Modules\Contract\Models\RentalPeriod;
use Modules\Contract\Models\RentalPeriodIncident;

class RentalPeriodController extends Controller
{

    public function records(Request $request)
    {
        $contract_item_id = $request->contract_item_id ?? null;
        $item_lot_id = $request->item_lot_id ?? null;
        $records = RentalPeriod::with(['incidents'])->where('contract_item_id', $contract_item_id)->where('item_lot_id', $item_lot_id);

        return new RentalPeriodCollection($records->paginate(10));
    }

    public function paymentReceipts(Request $request)
    {
        $contract_item_id = $request->contract_item_id ?? null;
        $item_lot_id = $request->item_lot_id ?? null;
        
        $records = SaleNote::with(['rental_period'])->whereHas('rental_period', function($query) use ($contract_item_id, $item_lot_id){
            $query->where('contract_item_id', $contract_item_id)->where('item_lot_id', $item_lot_id);
        })->latest();
        return new SaleNoteCollection($records->paginate(config('tenant.items_per_page')));
    }
	
	public function confirm(Request $request)
    {
        $response = ['success' => false];
        try{
            $rental_period_id = $request->rental_period_id;
            
            $rental_period = RentalPeriod::find($rental_period_id);
            if(!$rental_period) throw new Exception('No existe el dato para realizar el incidente!!');

            $response_sale_note = RentalPeriod::setSaleNote($rental_period);
            if(!$response_sale_note['success']) throw new Exception($response_sale_note['message']);

            $sale_note_id = null;
            if(isset($response_sale_note['data']) && isset($response_sale_note['data']['id'])) $sale_note_id = (int)$response_sale_note['data']['id'];
            else throw new Exception('Error al procesar la nota de venta!!');

            $rental_period->sale_note_id = $sale_note_id;
            $rental_period->save();

            $response['success'] = true;
            $response['message'] = 'Se confirmó correctamente!!';
            return response()->json($response);

        }catch(Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function store(RentalPeriodRequest $request)
    {
        $response = ['success' => false];
        try{
            $contract_item_id = $request->contract_item_id;
            $item_lot_id = $request->item_lot_id;
            $start_period = Carbon::parse($request->start_period);
            $months = (int)$request->months;

            if(!$start_period) throw new Exception('Error con la Incio de Periodo!!');

            $count = RentalPeriod::where('contract_item_id', $contract_item_id)->where('item_lot_id', $item_lot_id)->get()->count();
            if($count>0) throw new Exception('Ya existen periodos para este Item o Serie, borrar e intentar nuevamente!!');

            $contract_item = ContractItem::with(['contract'])->find($contract_item_id);
            if(!$contract_item) throw new Exception("No existe el contrato para continuar!!");

            $currency_type = CurrencyType::find($contract_item->contract->currency_type_id);
            if(!$currency_type) throw new Exception("No se encontró el tipo de moneda!!");

            $rental_periods = [];
            for($i = 0; $i < $months ; $i++){
                $start_format = $start_period->format('Y-m-d');
                $end_format = $start_period->addMonths(1)->format('Y-m-d');
                $rental_periods[] = [
                    'contract_item_id' => $contract_item_id,
                    'item_lot_id' => $item_lot_id,
                    'start_period' => $start_format,
                    'end_period' => $end_format,
                    'currency_type_id' => $currency_type->id,
                    'currency_type' => $currency_type,
                    'amount' => floatval($contract_item->unit_price)
                ];
                //$start_period = $end_period;
            }
            RentalPeriod::insert($rental_periods);

            $response['success'] = true;
            $response['message'] = 'Se creó correctamente los periodos!!';
            return response()->json($response);

        }catch(Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function destroy(Request $request) {
        $response = ['success' => false];
        try{
            $contract_item_id = $request->contract_item_id;
            $item_lot_id = $request->item_lot_id;

            $count = RentalPeriod::where('contract_item_id', $contract_item_id)->where('item_lot_id', $item_lot_id)->whereNotNull('sale_note_id')->get()->count();
            if($count>0) throw new Exception('Existen periodos con recibos de pagos!!');

            RentalPeriod::where('contract_item_id', $contract_item_id)->where('item_lot_id', $item_lot_id)->delete();

            $response['success'] = true;
            $response['message'] = 'Se eliminó correctamente los periodos!!';
            return response()->json($response);

        }catch(Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function saveCode(Request $request){
        $response = ['success' => false];
        try{
            $rental_period_id = $request->rental_period_id;
            $code = $request->code ?? null;

            $rental_period = RentalPeriod::find($rental_period_id);
            if(!$rental_period) throw new Exception('No existe el dato para guardar el código!!');

            $rental_period->code = $code;
            $rental_period->save();

            $response['success'] = true;
            $response['message'] = 'Se guardó el código correctamente!!';
            return response()->json($response);

        }catch(Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function incident(RentalPeriodIncidentRequest $request) {
        $response = ['success' => false];
        try{
            $rental_period_id = $request->rental_period_id;
            $days = $request->days;
            $observations = $request->observations;

            $rental_period = RentalPeriod::with(['incidents'])->find($rental_period_id);
            if(!$rental_period) throw new Exception('No existe el dato para realizar el incidente!!');

            $start_period = Carbon::parse($rental_period->start_period);
            $end_period = Carbon::parse($rental_period->end_period);
            $days_diff = $end_period->diffInDays($start_period);

            $day_incidents = 0;
            foreach($rental_period->incidents as $incident){$day_incidents += $incident->days;}
            $days_diff -= $day_incidents;

            if($days>$days_diff) throw new Exception('Los dias superan el periodo!!');
            $amount_diff = ($days * $rental_period->amount)/$days_diff;
            
            $rental_period_incident = [
                'rental_period_id' => $rental_period_id,
                'days' => $days,
                'amount' => $amount_diff,
                'currency_type_id' => $rental_period->currency_type_id,
                'currency_type' => $rental_period->currency_type,
                'observations' => $observations
            ];
            RentalPeriodIncident::create($rental_period_incident);

            $rental_period->amount -= $amount_diff;
            $rental_period->save();

            $response['success'] = true;
            $response['message'] = 'Se realizó el incidente correctamente!!';
            return response()->json($response);

        }catch(Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }


}