<?php
namespace App\Imports;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Rol;
use Carbon\Carbon;

class UserSheetImport implements ToModel, WithHeadingRow
{
    protected $sheetName;
    public $id_rol = 1;
    public function __construct($sheetName)
    {
        $this->sheetName = $sheetName;
        $role = Rol::where('name', $this->sheetName)->first();
        if($role) $this->id_rol = $role->id;
    }

    public function model(array $row)
    {
        // "id" => 1
        // "name" => "Joe[import]"
        // "last_name" => "Smith"
        // "email" => "admin@gmail.com"
        // "phone_number" => null
        // "pin" => null
        // "address" => null
        // "gender" => "male"
        // "date_of_birth" => "14/03/2024"
        // "department" => null
        // "yard_location" => null
        // "email_verified_at" => null
        // "phone_verified_at" => "30/11/-0001"
        // "cdl_verified_at" => null
        // "status" => "Enabled"
        // "created_at" => "14/03/2024"
        // "updated_at" => "27/05/2024"
        $id = $row['id'];
        if(User::find($id)){
            $id = null;
        }
        if($row['email']==null||$row['email']==''||User::where('email', $row['email'])->first()){
            return;
        }
        $password = '12345';
        $row['id_rol'] = $this->id_rol;
        $row['password'] = Hash::make($password);
        if ($row['date_of_birth'] != null && $row['date_of_birth'] != "") {
            $dateParts = explode('/', str_replace("-","",$row['date_of_birth']));
            if (count($dateParts) === 3) {
                $row['date_of_birth'] = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
            } else {
                $row['date_of_birth'] = null;
            }
        }
        if ($row['email_verified_at'] != null && $row['email_verified_at'] != "") {
            $dateParts = explode('/', str_replace("-","",$row['email_verified_at']));
            if (count($dateParts) === 3) {
                $row['email_verified_at'] = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
            } else {
                $row['email_verified_at'] = null;
            }
        }
        if ($row['phone_verified_at'] != null && $row['phone_verified_at'] != "") {
            $dateParts = explode('/', str_replace("-","",$row['phone_verified_at']));
            if (count($dateParts) === 3) {
                $row['phone_verified_at'] = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
            } else {
                $row['phone_verified_at'] = null;
            }
        }
        if ($row['cdl_verified_at'] != null && $row['cdl_verified_at'] != "") {
            $dateParts = explode('/', str_replace("-","",$row['cdl_verified_at']));
            if (count($dateParts) === 3) {
                $row['cdl_verified_at'] = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
            } else {
                $row['cdl_verified_at'] = null;
            }
        }
        $row['status'] = $row['status']=="Enabled"?"enable":"disable";
        $collection = new Collection($row);
        $user = User::create(array_filter($collection->except('created_at', 'updated_at')->toArray()));
    }
}

class UserImports implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $roles = Rol::orderBy('id')->get();
        foreach($roles as $role){
            $sheets[$role->name] = new UserSheetImport($role->name);
        }
        return  $sheets;
    }
}