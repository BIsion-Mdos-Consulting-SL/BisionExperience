<?php

namespace App\Imports;

use App\Models\Coch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none'); //Evita que los encabezados no se acepten por mayusuculas o min usculas

class CochImport implements ToModel, WithHeadingRow, WithValidation
{
    private $evento_id;

    public function __construct($evento_id)
    {
        $this->evento_id = $evento_id;
    }

    public function model(array $row)
    {
        return new Coch([
            'evento_id' => $this->evento_id,
            'marca' => $row['marca'],
            'modelo' => $row['modelo'],
            'version' => $row['version'],
            'matricula' => $row['matricula'],
            'kam' => $row['kam'],
            'asiste' => strtolower($row['llave']) === 'si' ? 1 : 0 //Acepta mayusculas y minusculas.
        ]);
    }


    public function rules(): array
    {
        return [
            'marca' => 'required',
            'modelo' => 'required',
            'version' => 'required',
            'matricula' => 'required',
            'kam' => 'required',
            'llave' => 'nullable|in:Si,No'
        ];
    }
}
