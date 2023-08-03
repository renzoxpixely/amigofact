<?php

namespace Modules\Item\Services;

use App\Models\Tenant\Importation;
use Carbon\Carbon;

class ItemLotService
{
    public function createImportationSeriesReport(): array
    {
        $series = [];

        foreach (Importation::all() as $importation) {
            foreach ($importation->invoices as $invoice) {
                foreach ($invoice->items as $purchase_item) {
                    if ($purchase_item->item->series_enabled) {
                        foreach ($purchase_item->lots as $lot) {
                            $series[] = [
                                'matrix_code' => $purchase_item->matrix_code,
                                'import_order_task' => $importation->import_order_task,
                                'invoice_number' => $invoice->number,
                                'month' => Carbon::parse($importation->created_at)->month,
                                'prod_year' => Carbon::parse($importation->created_at)->year,
                                'type_version' => $purchase_item->item->description,
                                'serial' => $lot->series,
                                'mincetur_code' => $lot->mincetur_code,
                                'rd' => preg_split("/-/", $lot->rd_file_url)[0]
                            ];
                        }
                    }
                }
            }
        }

        return $series;
    }
}
