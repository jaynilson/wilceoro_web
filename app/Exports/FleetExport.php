<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Fleet;
use Carbon\Carbon;
class FleetExport implements FromCollection, WithTitle, WithHeadings
{
    protected $sheetName;

    public function __construct($sheetName="Vehicles")
    {
        $this->sheetName = $sheetName;
    }

    public function collection()
    {
        $rows = Fleet::orderBy('type', 'desc')->orderBy('n')->get();
        $data = [];
        foreach($rows as $row){
            $data[] = [
                $row->n,
                $row->model,
                $row->type=="trucks_cars"?"Vehicle":(
                    $row->type=="trailers"?"Trailer":(
                        $row->type=="equipment"?"Equipment":""
                    )
                ),
                $row->department,
                $row->licence_plate,
                $row->year,
                $row->current_yard_location,
                $row->vin,
                $row->price,
                $row->required_cdl==0?"No":"Yes",
                $row->status=="true"?"Available":(
                    $row->status=="in-service"?"In Service":(
                        $row->status=="check-out"?"In-Use":"Out of Service"
                    )
                ),
                Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('m/d/Y'),
                Carbon::createFromFormat('Y-m-d H:i:s', $row->updated_at)->format('m/d/Y'),
            ];
        }
        return collect($data);
    }

    public function title(): string
    {
        return $this->sheetName;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Model',
            'Type',
            'Department',
            'Licence Plate',
            'Year',
            'Location',
            'Vin',
            'Purchase Price',
            'CDL Required',
            'Status',
            'Created At',
            'Updated At'
        ];
    }
}