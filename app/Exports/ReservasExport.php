<?php

namespace App\Exports;

use App\Models\Reserva;
use App\Models\Evento;
use App\Models\Parada;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReservasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Evento $evento;
    protected Collection $rows;

    public function __construct(Evento $evento)
    {
        $this->evento = $evento;
        $this->rows = $this->buildRows();
    }

    public function headings(): array
    {
        return [
            'Parada',
            'Hora inicio - Hora fin',
            'Modelo',
            'Matrícula',
            'Usuario',
            'Tipo'
        ];
    }

    public function collection()
    {
        return $this->rows;
    }

    public function map($row): array
    {
        return [
            $row['parada'],
            $row['rango'],
            $row['modelo'],
            $row['matricula'],
            $row['usuario'],
            $row['tipo']
        ];
    }

    private function buildRows(): Collection
    {
        $paradasTable = (new Parada())->getTable();

        $reservas = Reserva::where('reservas.evento_id', $this->evento->id)
            ->whereNotNull('reservas.hora_inicio')
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

            //Construimos el rango para poder visualizarlo.
            $inicio = $reserva->hora_inicio;
            $fin = $reserva->hora_fin;

            $rango = trim("$inicio - $fin" ?? '-');

            $rows->push([
                'parada'    => $reserva->parada->nombre,
                'rango' => $rango ?? '',
                'modelo'    => $reserva->coch->modelo,
                'matricula' => $reserva->coch->matricula,
                'usuario'   => $reserva->user->name,
                'tipo'      => ucfirst($reserva->tipo),
            ]);
        }
        return $rows;
    }
}
