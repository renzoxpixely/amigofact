<?php

namespace Modules\Item\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ImportationSeriesExport implements FromView
{
    private $series;

    public function __construct(array $series)
    {
        $this->series = $series;
    }

    public function view(): View
    {
        $series = $this->series;

        return view('tenant.exports.importation-series-export', compact('series'));
    }
}
