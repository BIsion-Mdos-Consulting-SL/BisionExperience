<?php

namespace App\Exports;

use App\Models\Evento;
use App\Models\Reserva;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

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
            'Conductor'
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
            $row['conductor'],
        ];
    }

    //Coleccion de informacion en la tabla
    private function buildRows(): Collection
    {
        $reservas = Reserva::with(['user:id,name', 'coch:id,modelo,matricula', 'parada:id,nombre'])
            ->where('evento_id', $this->evento->id)
            ->get(['id', 'user_id', 'parada_id', 'coche_id', 'tipo']);

        $grouped = $reservas->groupBy(['parada_id', 'coche_id']);

        $rows = collect();
        $norm = fn($s) => mb_strtolower($s ?? '', 'UTF-8');

        foreach ($grouped as $paradaId => $byCar) {
            foreach ($byCar as $cocheId => $items) {
                $parada = optional($items->first()->parada)->nombre ?? '';
                $coche  = $items->first()->coch;

                //Conductor.
                $conductor = optional(
                    $items->first(fn($r) => $norm($r->tipo) === 'conductor')
                )->user->name ?? '';

                $rows->push([
                    'parada'       => $parada,
                    'modelo'       => $coche->modelo ?? '',
                    'matricula'    => $coche->matricula ?? '',
                    'conductor'    => $conductor,
                ]);
            }
        }

        return $rows;
    }
}
