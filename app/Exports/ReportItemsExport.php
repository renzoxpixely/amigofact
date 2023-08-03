<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\Contract\Models\Contract;

class ReportItemsExport implements FromView
{
    public function view(): View
    {
        return view('contract::contracts.reportitems', [
            'contratos' => Contract::with('tipocontrato')->get()
        ]);

        return back();
    }
}
