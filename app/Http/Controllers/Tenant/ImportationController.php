<?php

namespace App\Http\Controllers\Tenant;

use App\Exports\ImportationExport;
use App\Exports\ParentReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ImportationRequest;
use App\Http\Resources\Tenant\ImportationCollection;
use App\Models\Tenant\Importation;
use App\Models\Tenant\ImportationUploadedFile;
use App\Services\ImportationService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ImportationController extends Controller
{
    private $importationService;

    public function __construct(ImportationService $importationService)
    {
        $this->importationService = $importationService;
    }

    public function show(Importation $importation): Response
    {
        return response($importation);
    }

    public function importIndex()
    {
        return view('tenant.purchases.import_index');
    }

    public function records(Request $request): ImportationCollection
    {
        $records = $this->findRecords($request->column, $request->value);

        return new ImportationCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function columns(): array
    {
        return [
            'import_order_task' => 'Import order task',
            'supplier' => 'proveedor',
            'number_proforma' => 'Número proforma',
            'amount_proforma' => 'Monto proforma',
            'date_periodo' => 'Periodo',
            'date_of_issue' => 'Fecha emisión',
            'arrival_date' => 'Fecha de arribo',
            'sale_reference' => 'Ref. liquidación',
            'ref_customs_agent' => 'Ref. agente aduanas',
            'transport_type' => 'Conocimiento de embarque',
            'transport_code' => 'Nro. de conocimiento',
            'dam' => 'DAM',
            'comments' => 'Comentarios'
        ];
    }

    public function store(ImportationRequest $request): Response
    {
        $importation = new Importation();

        $importation->fill($request->all());

        $importation->save();

        if ($request->hasFile('uploadedFiles')) {
            $directory = 'public'.DIRECTORY_SEPARATOR.'importations'.DIRECTORY_SEPARATOR;
            foreach ($request->uploadedFiles as $file) {
                $date_now = date('YmdHis');
                $file_name = Str::slug($file->getClientOriginalName()) . '-' . $date_now . '.' . $file->getClientOriginalExtension();
                Storage::put($directory.$file_name, file_get_contents($file));

                $importationUploadedFile = new ImportationUploadedFile();
                $importationUploadedFile->filename = $file_name;
                $importationUploadedFile->importation_id = $importation->id;

                $importationUploadedFile->save();
            }
        }

        return response(['success' => true, 'message' => 'Importación guardado con éxito']);

    }

    public function edit($id)
    {
        $resourceId = $id;
        return view('tenant.purchases.importation_edit', compact('resourceId'));
    }

    public function update(ImportationRequest $request, int $id): Response
    {
       try {
           $importation = Importation::findOrFail($id);

           // Update the import order task attribute as this is the key by which they are mapped
           foreach ($importation->invoices as $invoice) {
               $invoice->import_order_task = $request->get('import_order_task');
               $invoice->save();
           }

           if ($request->hasFile('uploadedFiles')) {
               $directory = 'public'.DIRECTORY_SEPARATOR.'importations'.DIRECTORY_SEPARATOR;
               foreach ($request->uploadedFiles as $file) {
                   $date_now = date('YmdHis');
                   $file_name = Str::slug($file->getClientOriginalName()) . '-' . $date_now . '.' . $file->getClientOriginalExtension();
                   Storage::put($directory.$file_name, file_get_contents($file));

                   $importationUploadedFile = new ImportationUploadedFile();
                   $importationUploadedFile->filename = $file_name;
                   $importationUploadedFile->importation_id = $importation->id;

                   $importationUploadedFile->save();
               }
           }

           if ($request->get('fileIdsToRemove')){
               foreach ($request->get('fileIdsToRemove') as $id) {
                   $uploadedFile = ImportationUploadedFile::findOrFail($id);
                   $path = storage_path('app/public/importations/');

                   if (file_exists($path . $uploadedFile->filename)) {
                       unlink($path . $uploadedFile->filename);
                   }

                   ImportationUploadedFile::destroy($id);
               }
           }

           $importation->fill($request->all());

           $importation->save();

           return response(['success' => true, 'message' => 'Importación actualizada con éxito']);
       } catch(Exception $exception) {

           return response(['success' => false, 'message' => 'Ocurrió un error, por favor intenta nuevamente']);
       }
    }

    public function findRecords(string $field, ?string $value)
    {
        if ($field === 'supplier') {
            return Importation::whereHas('supplier', function($query) use ($value) {
                $query->where('name', 'like', '%' . $value . '%');
            });
        }
        return Importation::where($field, 'like', '%'. $value . '%');
    }

    public function export()
    {
        return Excel::download(new ImportationExport, 'importaciones.xlsx');
    }

    public function parentReport(int $importation_id): Response
    {
        $report_items = $this->importationService->getParentReport($importation_id);

        return response(['items' => $report_items, 'importation_id' => $importation_id]);
    }

    public function parentReportExport(int $importation_id)
    {
        $parent_report_items = $this->importationService->getParentReport($importation_id);

        return Excel::download(new ParentReportExport($parent_report_items), 'parent_report.xlsx');
    }

    public function destroy(Importation $importation): Response
    {
        foreach ($importation->uploaded_files as $file) {
            $path = storage_path('app/public/importations/');

            if (file_exists($path . $file->filename)) {
                unlink($path . $file->filename);
            }

            ImportationUploadedFile::destroy($file->id);
        }

        Importation::destroy($importation->id);

        return response("Importación ha sido borrada");
    }
}
