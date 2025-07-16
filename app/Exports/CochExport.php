<?php

namespace App\Exports;

use App\Models\Coch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CochExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            'Marca',
            'Modelo',
            'Version',
            'Matricula',
            'KAM',
            'Llave',
        ];
    }

    public function collection()
    {
        return Coch::all()->map(function ($coche) {
            return [
                'marca' => $coche->marca,
                'modelo' => $coche->modelo,
                'version' => $coche->version,
                'matricula' => $coche->matricula,
                'kam' => $coche->kam,
                'asiste' => $coche->asiste ? 'Si' : 'No',
            ];
        });
    }
}
