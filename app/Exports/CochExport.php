<?php

namespace App\Exports;

use App\Models\Coch;
use App\Models\Evento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CochExport implements FromCollection, WithHeadings
{
    protected $evento;
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

    public function __construct(Evento $evento){
        $this->evento = $evento;
    }

    public function collection()
    {
       /*  return Coch::all()->map(function ($coche) {
            return [
                'marca' => $coche->marca,
                'modelo' => $coche->modelo,
                'version' => $coche->version,
                'matricula' => $coche->matricula,
                'kam' => $coche->kam,
                'asiste' => $coche->asiste ? 'Si' : 'No',
            ];
        }); */

        return $this->evento->coches->map(function($coches){
            return [
                'marca' => $coches->marca,
                'modelo' => $coches->modelo,
                'version' => $coches->version,
                'matricula' => $coches->matricula,
                'kam' => $coches->kam,
                'asiste' => $coches->asiste ? 'Si' : 'No'
            ];
        });
    }
}
