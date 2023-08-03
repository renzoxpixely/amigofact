<?php

namespace Modules\Item\Http\Controllers;

use App\Models\Tenant\Item;
use App\Models\Tenant\Person;
use App\Models\Tenant\PersonHall;
use App\Helpers\CustomPagination;
use App\Models\Tenant\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Contract\Models\Contract;
use Modules\Item\Exports\ImportationSeriesExport;
use Modules\Item\Models\ItemLot;
use Modules\Item\Http\Resources\ItemLotCollection;
use Modules\Item\Http\Requests\ItemLotRequest;
use Modules\Item\Exports\ItemLotExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractType;
use Modules\Item\Services\ItemLotService;

class ItemLotController extends Controller
{
    use CustomPagination;

    private ItemLotService  $itemLotService;

    public function __construct(ItemLotService $itemLotService)
    {
        $this->itemLotService = $itemLotService;
    }

    public function index()
    {
        return view('item::item-lots.index');
    }

    public function reportIndex(): View
    {
        return view('item::item-lots.report');
    }

    public function importationSeriesReportRecords(Request $request): Response
    {
        $series = $this->itemLotService->createImportationSeriesReport();

        $per_page = intval(config('tenant.items_per_page'));
        $paginatedResult = $this->paginate($series, $per_page, $request->request->get('page', 1));

        return response($paginatedResult);
    }

    public function exportImportationSeriesReport()
    {
        $series = $this->itemLotService->createImportationSeriesReport();

        return Excel::download(new ImportationSeriesExport($series), 'importacion-series-export.xlsx');
    }

    public function columns()
    {
        return [
            'ps.id' => 'Cliente',
            'ct.contract_type_id' => 'Tipo de Contrato',
            'free' => 'Libre Sin Contrato',
            // 'il.date' => 'Fecha',
            // 'state' => 'Estado',
            'its.description' => 'Producto',
            'phs.id' => 'Sala',
            'il.series' => 'Serie',
        ];
    }

    public function tables()
    {
        $customers = Person::select('id', 'number', 'name')->where('type', 'customers')->get()->transform(function($row){
            return ['id' => $row->id, 'name'=> "{$row->number} - {$row->name}"];
        });

        $halls = PersonHall::with(['person'])->select('id', 'name', 'person_id')->get()->transform(function($row){
            $name = "";
            if($row->person) $name .= "{$row->person->number} - {$row->person->name} - ";
            $name .= $row->name;
            return ['id' => $row->id, 'name'=> "{$name}"];
        });
        $products = Item::selectRaw('description')->where('unit_type_id', '!=', 'ZZ')->get()->transform(function($row){
            return $row->description;
        });

        $contract_types = ContractType::select('id', 'name')->get()->transform(function($row){
            return ['id' => $row->id, 'name'=> $row->name];
        });

        $series = ItemLot::select('series')->get()->transform(function ($row) {
            return $row->series;
        });

        $contract_numbers = Contract::select(['id', 'prefix', 'number'])->get()->transform(function ($row) {
            return ['id' => $row->id, 'number' => $row->prefix . '-' . $row->number];
        });

        return compact('customers', 'halls', 'products', 'contract_types', 'series', 'contract_numbers');
    }



    public function records(Request $request)
    {

        $records = $this->getRecords($request);

        return new ItemLotCollection($records->paginate(config('tenant.items_per_page')));

    }


    public function getRecords(Request $request){

        $records = DB::connection('tenant')
        ->table('item_lots AS il')
        ->leftJoin('items AS its', 'il.item_id', '=', 'its.id')
        ->leftJoin(DB::raw('(SELECT MAX(contract_item_id) AS contract_item_id, item_lot_id FROM contract_item_lots GROUP BY item_lot_id) AS cil'), 'il.id', '=', 'cil.item_lot_id')
        ->leftJoin('contract_items AS ci', 'cil.contract_item_id', '=', 'ci.id')
        ->leftJoin('person_halls AS phs', 'ci.hall_id', '=', 'phs.id')
        ->leftJoin('contracts AS ct', 'ci.contract_id', '=', 'ct.id')
        ->leftJoin('contract_types AS ctt', 'ct.contract_type_id', '=', 'ctt.id')
        ->leftJoin('persons AS ps', 'ct.customer_id', '=', 'ps.id')
        ->leftJoin('person_types AS pts', 'ps.person_type_id', '=', 'pts.id')
        ->leftJoin('warehouses AS wh', 'il.warehouse_id', '=', 'wh.id')
        ->selectRaw("il.id, il.series, il.date,  il.item_id, its.description, CONCAT(ct.prefix,'-', ct.number) AS contract,
        CONCAT(ps.number,' - ',ps.name) AS customer, pts.description AS 'group', phs.name AS hall, wh.description AS warehouse, ctt.name AS contract_type");

        $records->where('il.item_loteable_type', '!=',  PurchaseItem::class);

        if ($request->client) {
            $records->whereRaw("CONCAT(ps.number,' - ',ps.name) like '%{$request->client}%'");
        }

        if ($request->product_description) {
            $records->where('its.description', 'like', '%'.$request->product_description.'%');
        }

        if ($request->hall) {
            $records->where('hall', 'like', '%'.$request->hall.'%');
        }

        if ($request->contract_type) {
            $records->where('ctt.name', 'like', '%'.$request->contract_type.'%');
        }

        if ($request->inStorageWithContract === 'true') {
            $records->where('hall', 'like', '%'.'Almacén con Contrato'.'%');
        }

        if ($request->isFree === 'true') {
            $records->whereRaw('il.has_sale = 0');
        }

        if ($request->hasContract === 'true') {
            $records->whereRaw('il.has_sale = 1');
        }

        if ($request->serie) {
            $records->where('il.series', 'like', '%'.$request->serie.'%');
        }

        if ($request->contract_number) {
            [$prefix, $number] = explode("-", $request->contract_number);

            $records->where([['ct.prefix', $prefix], ['ct.number', $number]]);
        }

        return $records;
    }


    public function record($id)
    {
        $record = ItemLot::findOrFail($id);

        return $record;
    }


    public function store(ItemLotRequest $request)
    {

        $id = $request->input('id');
        $record = ItemLot::findOrFail($id);
        $record->series = $request->series;
        $record->save();

        return [
            'success' => true,
            'message' => ($id)?'Serie editada con éxito':'Serie registrada con éxito',
        ];

    }

    public function export(Request $request)
    {

        $records = $this->getRecords($request)->get();
        $data = new ItemLotCollection($records);
        return (new ItemLotExport)
                ->records($data->toArray(true))
                ->download('Series_'.Carbon::now().'.xlsx');

    }

}
