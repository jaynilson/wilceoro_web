<?php
   
namespace App\Imports;

use App\Models\BankReference;
use DateTime;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class BankReferencesExamImport implements ToModel,SkipsEmptyRows, WithValidation, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if(!array_filter($row)) {
            return null;
         } 
      
            return new BankReference([
                'n'     => $row['n'],
                'agreement'    => $row['convenio'], 
                'slip'    => $row['ficha'], 
                'amount'    => $row['importe'], 
                'currency'    => $row['moneda'], 
                'registration_date'    => DateTime::createFromFormat('d/m/Y H:i', $row['fecha_registro']), 
                'status'    => $row['estatus'], 
                'concept'    => $row['concepto'], 
                'type'    => "exam"
            ]);
        
   
    }

    public function rules(): array
    {
        return [
            'n' => ['required','integer','max:150'],
            'convenio' => ['required','max:150'],
            'ficha' => ['required','max:150'],
            'importe' =>  ['required','max:150'],
            'moneda' => ['required','max:150'],
            'fecha_registro' => ['required','max:150'],
            'estatus' =>  ['required','max:150'],
            'concepto' =>  ['required','max:150'],

        ];
    }

  
}