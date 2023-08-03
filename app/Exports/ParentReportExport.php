<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ParentReportExport implements FromView, WithColumnWidths, WithColumnFormatting
{
    private $parent_report_items;

    public function __construct(array $parent_report_items)
    {
        $this->parent_report_items = $parent_report_items;
    }

    public function columnFormats(): array
    {
        return [
            'B' => '0',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 12,
            'C' => 10,
            'D' => 10,
            'E' => 20,
            'F' => 35,
            'G' => 11,
            'I' => 12,
            'M' => 18,
            'O' => 12,
            'R' => 12,
            'U' => 21,
            'V' => 11,
            'W' => 14,
            'X' => 12,
            'AA' => 21,
            'AB' => 12,
            'AC' => 14,
        ];
    }

    public function view(): View
    {
        $parent_report_items = $this->parent_report_items;

        return view('tenant.exports.parent-report', compact('parent_report_items'));
    }
}
