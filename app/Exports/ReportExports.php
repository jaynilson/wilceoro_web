<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
class ReportExports implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new FleetExport();
        $sheets[] = new ServiceExport();
        $sheets[] = new ToolExport();
        return $sheets;
    }
}