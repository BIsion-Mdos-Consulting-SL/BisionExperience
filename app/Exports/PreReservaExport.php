<?php

namespace App\Exports;

use App\Models\Evento;
use App\Models\Parada;
use App\Models\Reserva;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;
use Laravel\Prompts\Table;

class PreReservaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Evento $evento;
    protected Collection $rows;

    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
        $this->rows = $this->buildRows();
    }

    //Cabeceras
    public function headings(): array
    {
        return [
            'Parada',
            'Modelo',
            'Matricula',
            'Usuario',
            'Tipo'
        ];
    }

    //Coleccion completa (muestra)
    public function collection()
    {
        return $this->rows;
    }

    public function map($row): array
    {
        return [
            $row['parada'],
            $row['modelo'],
            $row['matricula'],
            $row['usuario'],
            $row['tipo']
        ];
    }

    /**
     * La función crea una colección de filas con información sobre las reservas para un evento específico, ordenadas por orden de parada e ID del vehículo.
     * 
     * @return Collection Se devuelve una colección de filas. Cada fila contiene información sobre una reserva, incluyendo el nombre de la parada, el modelo del vehículo, la matrícula, el nombre de usuario y el tipo de reserva.
     */


    private function buildRows(): Collection
    {
        //Llamamos a la tabla de paradas.
        $paradasTable = (new Parada())->getTable();

        $reservas = Reserva::where('reservas.evento_id', $this->evento->id)
            ->join($paradasTable . ' as p', 'reservas.parada_id', '=', 'p.id')
            ->with([
                'user:id,name',
                'coch:id,modelo,matricula',
                'parada:id,nombre,orden',
            ])
            ->select('reservas.*')
            ->orderBy('p.orden', 'asc')
            ->orderBy('reservas.coche_id', 'asc')
            ->get();

        $rows = collect();

        foreach ($reservas as $reserva) {
            $rows->push([
                'parada'    => $reserva->parada->nombre,
                'modelo'    => $reserva->coch->modelo,
                'matricula' => $reserva->coch->matricula,
                'usuario'   => $reserva->user->name,
                'tipo'      => ucfirst($reserva->tipo),
            ]);
        }
        return $rows;
    }
}
