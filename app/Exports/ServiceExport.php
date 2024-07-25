<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Service;
use App\Models\Fleet;
use App\Models\User;
use Carbon\Carbon;
class ServiceExport implements FromCollection, WithTitle, WithHeadings
{
    protected $sheetName;

    public function __construct($sheetName="Services")
    {
        $this->sheetName = $sheetName;
    }

    public function collection()
    {
        $rows = Service::orderBy('id')->get();;
        $data = [];
        foreach($rows as $row){
            $fleet = Fleet::find($row->id_fleet);
            $fleet_title = "";
            if($fleet) $fleet_title = "N°: $fleet->n $fleet->model";
            $driver_name = "";
            $driver = User::find($row->id_employee);
            if($driver) $driver_name = "$driver->name $driver->last_name";
            $data[] = [
                $row->id,
                $row->type,
                $row->description,
                $fleet_title,
                $driver_name,
                $row->cost,
                $row->needed_date!=""&&$row->needed_date!=null?Carbon::createFromFormat('Y-m-d', $row->needed_date)->format('m/d/Y'):"",
                $row->completed_date!=""&&$row->completed_date!=null?Carbon::createFromFormat('Y-m-d', $row->completed_date)->format('m/d/Y'):"",
                $row->engine_hours,
                $row->notes,
                $row->status,
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
            'Type',
            'Description',
            'Vehicle',
            'Driver',
            'Cost',
            'Requested Date',
            'Completed_date',
            'Engine Hours',
            'Notes',
            'Status',
            'Created At',
            'Updated At'
        ];
    }
}