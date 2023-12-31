<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class CommercialExport implements  FromView, ShouldAutoSize
{
    use Exportable;

    public function records($records) {
        $this->records = $records;

        return $this;
    }


    public function name($name) {
        $this->name = $name;

        return $this;
    }


    public function view(): View {
        return view('tenant.commercials.exports.commercials', [
            'records'=> $this->records,
            'name'=> $this->name,
        ]);
    }


}
