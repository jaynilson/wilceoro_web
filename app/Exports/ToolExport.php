<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Tool;
use Carbon\Carbon;
class ToolExport implements FromCollection, WithTitle, WithHeadings
{
    protected $sheetName;

    public function __construct($sheetName="Inventories")
    {
        $this->sheetName = $sheetName;
    }

    public function collection()
    {
        $rows = Tool::orderBy('department')->orderBy('n')->get();
        $data = [];
        foreach($rows as $row){
            $data[] = [
                $row->n,
                $row->department,
                $row->title,
                $row->type,
                $row->available_stock,
                $row->price,
                $row->required_return==1?"Yes":"No",
                $row->status=="true"?(
                    $row->available_stock>0?"Available":"Stock of Out"
                ):(
                    $row->status=="false"?"Disabled":""
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
            'Department',
            'Title',
            'Type',
            'Stock',
            'Price',
            'Required Return',
            'Status',
            'Created At',
            'Updated At'
        ];
    }
}