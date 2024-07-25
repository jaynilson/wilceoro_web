<?php
namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\User;
use App\Models\Rol;
use Carbon\Carbon;
class UserSheet implements FromCollection, WithTitle, WithHeadings
{
    protected $sheetName;
    public $role;

    public function __construct($sheetName, $role)
    {
        $this->sheetName = $sheetName;
        $this->role = $role;
    }

    public function collection()
    {
        $rows = User::where('id_rol', $this->role)->orderBy('id', 'asc')->get();
        $data = [];
        foreach($rows as $row){
            $data[] = [
                $row->id,
                $row->name,
                $row->last_name,
                $row->email,
                $row->tel,
                $row->pin,
                $row->address,
                $row->sex,
                $row->date_of_birth!=null&&$row->date_of_birth!=""?Carbon::createFromFormat('Y-m-d', $row->date_of_birth)->format('m/d/Y'):"",
                $row->department,
                $row->yard_location,
                $row->email_verified_at!=null&&$row->email_verified_at!=""?Carbon::createFromFormat('Y-m-d H:i:s', $row->email_verified_at)->format('m/d/Y'):"",
                $row->phone_verified_at!=null&&$row->phone_verified_at!=""?Carbon::createFromFormat('Y-m-d', $row->phone_verified_at)->format('m/d/Y'):"",
                $row->cdl_verified_at!=null&&$row->cdl_verified_at!=""?Carbon::createFromFormat('Y-m-d', $row->cdl_verified_at)->format('m/d/Y'):"",
                $row->status=="enable"?"Enabled":"Disabled",
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
            'Name',
            'Last Name',
            'Email',
            'Phone Number',
            'Pin',
            'Address',
            'Gender',
            'Date of Birth',
            'Department',
            'Yard Location',
            'Email Verified At',
            'Phone Verified At',
            'CDL Verified At',
            'Status',
            'Created At',
            'Updated At'
        ];
    }
}

class UserExports implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $roles = Rol::orderBy('id')->get();
        foreach($roles as $role){
            $sheets[] = new UserSheet($role->name, $role->id);
        }
        return $sheets;
    }
}