<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Exports\AccountsReceivable;
use App\Http\Controllers\Tenant\DocumentController;
use App\Models\Tenant\Configuration;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Dashboard\Helpers\DashboardData;
use Modules\Dashboard\Helpers\DashboardUtility;
use Modules\Dashboard\Helpers\DashboardSalePurchase;
use Modules\Dashboard\Helpers\DashboardView;
use Modules\Dashboard\Helpers\DashboardStock;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Document;
use App\Models\Tenant\Company;
use App\Models\Tenant\Person;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Arr;
use Modules\Dashboard\Exports\ReportRentalExport;
use Modules\Dashboard\Exports\ReportSaleExport;
use Modules\Dashboard\Exports\ReportStakeExport;
use Modules\Dashboard\Traits\ReportTrait;

/**
 * Class DashboardController
 *
 * @package Modules\Dashboard\Http\Controllers
 * @mixin Controller
 */
class DashboardController extends Controller
{
    use ReportTrait;
    
    public function index()
    {
        $configuration = Configuration::first();
        //dd(auth()->user()->type);
        if((auth()->user()->type != 'admin' && auth()->user()->type != 'client') || !auth()->user()->searchModule('dashboard'))
            return redirect()->route('tenant.documents.index');

        $company = Company::select('soap_type_id')->first();
        $soap_company  = $company->soap_type_id;

        $pendientes = Document::where('state_type_id','01')
            ->get();

        $whatsapp= $configuration->phone_whatsapp;

        return view('dashboard::index', compact('soap_company'));
    }

    public function filter()
    {
        return [
            'establishments' => DashboardView::getEstablishments()
        ];
    }

    public function globalData()
    {
        return response()->json((new DashboardData())->globalData(), 200);
    }

    public function data(Request $request)
    {
        return [
            'data' => (new DashboardData())->data($request->all()),
        ];
    }

    // public function unpaid(Request $request)
    // {
    //     return [
    //             'records' => (new DashboardView())->getUnpaid($request->all())
    //     ];
    // }

    // public function unpaidall()
    // {

    //     return Excel::download(new AccountsReceivable, 'Allclients.xlsx');

    // }

    public function data_aditional(Request $request)
    {
        return [
            'data' => (new DashboardSalePurchase())->data($request->all()),
        ];
    }

    public function stockByProduct(Request $request)
    {
        return  (new DashboardStock())->data($request);
    }


    public function utilities(Request $request)
    {
        return [
            'data' => (new DashboardUtility())->data($request->all()),
        ];
    }

    public function df()
    {
        $path = app_path();
        //df -m -h --output=used,avail,pcent /

        $used = new Process('df -m -h --output=used /');
        $used->run();
        if (!$used->isSuccessful()) {
            return ['error'];
            throw new ProcessFailedException($used);
        }
        $disc_used = $used->getOutput();
        $array[] = str_replace("\n","",$disc_used);

        $avail = new Process('df -m -h --output=avail /');
        $avail->run();
        if (!$avail->isSuccessful()) {
            return ['error'];
            throw new ProcessFailedException($avail);
        }
        $disc_avail = $avail->getOutput();
        $array[] = str_replace("\n","",$disc_avail);

        $pcent = new Process('df -m -h --output=pcent /');
        $pcent->run();
        if (!$pcent->isSuccessful()) {
            return ['error'];
            throw new ProcessFailedException($pcent);
        }
        $disc_pcent = $pcent->getOutput();
        $array[] = str_replace("\n","",$disc_pcent);

        return $array;


    }


    public function tables(){
        $customers = Person::with('addresses')
        ->whereType('customers')
        ->whereIsEnabled()
        ->orderBy('name')
        ->get()->transform(function ($row) {
            return [
                'id' => $row->id,
                'name' => $row->number.' - '.$row->name,
            ];
        });
        return compact('customers');
    }

    public function records_stake(Request $request){
        $customer_id = $request->customer_id ?? null;
        $page = $request->page ?? 1;

        $records = $this->report_stake($customer_id, $page);
        return response()->json($records);
    }

    public function records_stake_excel(Request $request) {
        $customer_id = $request->customer_id ?? null;

        $company = Company::first();
        $export = new ReportStakeExport();
        $records = $this->report_stake($customer_id);
        $export ->company($company)
                ->records($records);
        return $export ->download('Reporte_Participación'.Carbon::now().'.xlsx');
    }

    public function records_rental(Request $request){
        $customer_id = $request->customer_id ?? null;
        $page = $request->page ?? 1;

        $records = $this->report_rental($customer_id, $page);
        return response()->json($records);
    }

    public function records_rental_excel(Request $request) {
        $customer_id = $request->customer_id ?? null;

        $company = Company::first();
        $export = new ReportRentalExport();
        $records = $this->report_rental($customer_id);
        $export ->company($company)
                ->records($records);
        return $export ->download('Reporte_Alquiler'.Carbon::now().'.xlsx');
    }


    public function records_sale(Request $request){
        $customer_id = $request->customer_id ?? null;
        $page = $request->page ?? 1;

        $records = $this->report_sale($customer_id, $page);
        return response()->json($records);
    }

    public function records_sale_excel(Request $request) {
        $customer_id = $request->customer_id ?? null;

        $company = Company::first();
        $export = new ReportSaleExport();
        $records = $this->report_sale($customer_id);
        $export ->company($company)
                ->records($records);
        return $export ->download('Reporte_Venta'.Carbon::now().'.xlsx');
    }

}
