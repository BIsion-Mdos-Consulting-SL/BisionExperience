<?php

namespace App\Exports;

use App\Models\Reserva;
use App\Models\Evento;
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
            'Conductor',
            'Acompañantes',
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
            $row['conductor'],
            $row['acompanantes'],
        ];
    }

    private function buildRows(): Collection
    {
        $reservas = Reserva::with(['user:id,name', 'coch:id,modelo,matricula', 'parada:id,nombre'])
            ->where('evento_id', $this->evento->id)
            ->get(['id', 'user_id', 'parada_id', 'coche_id', 'tipo', 'hora_inicio', 'hora_fin']);

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

                //Acompañantes.
                $acomps = $items
                    ->filter(fn($r) => in_array($norm($r->tipo), ['acompanante', 'acompañante']))
                    ->pluck('user.name')
                    ->filter()
                    ->unique()
                    ->implode(', ');

                $ini = $items->pluck('hora_inicio')->filter()->min();
                $fin = $items->pluck('hora_fin')->filter()->max();
                $rango = trim(($ini ?: '') . ' - ' . ($fin ?: ''));

                $rows->push([
                    'parada'       => $parada,
                    'rango'        => $rango ?: '—',
                    'modelo'       => $coche->modelo ?? '',
                    'matricula'    => $coche->matricula ?? '',
                    'conductor'    => $conductor,
                    'acompanantes' => $acomps,
                ]);
            }
        }

        return $rows;
    }
}
