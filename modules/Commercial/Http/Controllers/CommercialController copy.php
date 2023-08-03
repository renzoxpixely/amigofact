<?php

namespace Modules\Commercial\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Configuration;
use Illuminate\Support\Facades\DB;
use Modules\Commercial\Models\Participation;
use Modules\Commercial\Models\ParticipationCollection;
use Illuminate\Support\Str;
use App\Models\Tenant\Company;
use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use App\Traits\OfflineTrait;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use Modules\Finance\Traits\FinanceTrait;
use App\Models\Tenant\Person;
use Modules\Commercial\Models\ContractStateType;

class CommercialController extends Controller
{
    use FinanceTrait;
    use StorageDocument;
    use OfflineTrait;
    protected $contract;
    protected $company;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $configuration = Configuration::first();

        return view('commercial::commercials.index', compact('configuration'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function participacionCreate($id = null)
    {

        if(auth()->user()->type == 'client') return redirect('/commercials');

        $quotationId = null;
        $showPayments = true;

        return view('commercial::commercials.participacion_form', compact('id', 'quotationId', 'showPayments'));
        
    }

    public function rentaCreate($id = null)
    {
        if(auth()->user()->type == 'client') return redirect('/commercials');

        $quotationId = null;
        $showPayments = true;

        return view('commercial::commercials.renta_form', compact('id', 'quotationId', 'showPayments'));
        
    }

    public function ventaCreate($id = null)
    {
        if(auth()->user()->type == 'client') return redirect('/commercials');

        $quotationId = null;
        $showPayments = true;

        return view('commercial::commercials.venta_form', compact('id', 'quotationId', 'showPayments'));
        
    }
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) {

        DB::connection('tenant')->transaction(function () use ($request) {

            $data = $this->mergeData($request);
            $data['terms_condition'] = $this->getTermsCondition();
            $this->contract =  Participation::updateOrCreate( ['id' => $request->input('id')], $data);


        });

        return [
            'success' => true,
            'data' => [
                'id' => $this->contract->id,
                'number_full' => $this->contract->number_full,
            ],
        ];
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('commercial::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('commercial::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    public function mergeData($inputs)
    {

        $this->company = Company::active();

        $values = [
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'customer' => PersonInput::set($inputs['customer_id']),
            'establishment' => EstablishmentInput::set($inputs['establishment_id']),
            'soap_type_id' => $this->company->soap_type_id,
            'state_type_id' => '01'
        ];

        $inputs->merge($values);

        return $inputs->all();
    }

    private function getTermsCondition(){

        $configuration = Configuration::select('terms_condition')->first();

        if($configuration){
            return $configuration->terms_condition;
        }

        return null;

    }    


    public function columns()
    {
        return [
            'months' => 'Meses',
            'status' => 'Estado',
            'customer_id' => 'Cliente ID',
            'participation' => 'participacion'
        ];
    }


    public function records()
    {
        $records = Participation::all();
        
        $ojito = Person::find(3);

        // Verifica si se encontró el registro antes de acceder a sus atributos
            $name = $ojito->name;
        

        foreach ($records as $record) {
            $record->participation = "participacion";

            $person = Person::find($record -> customer_id) ;
            $name = $person->name;
            $record->name = $name;
            
        }
      
        return new ParticipationCollection($records);
    }

    
    public function filter()
    {
        $state_types = ContractStateType::where('active',true)->orderBy('id', 'asc')->get();
        dd
        return compact('state_types');
    }

}
