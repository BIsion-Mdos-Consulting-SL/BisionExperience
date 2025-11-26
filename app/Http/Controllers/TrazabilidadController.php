<?php

namespace App\Http\Controllers;

use App\Exports\ReservasExport;
use App\Models\Coch;
use App\Models\Evento;
use App\Models\Parada;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;

class TrazabilidadController extends Controller
{
    public function index($id)
    {
        $evento = Evento::findOrFail($id);

        $reservas = Reserva::where('reservas.evento_id', $id)
            ->join('parada as p', 'reservas.parada_id', '=', 'p.id')
            ->with([
                'user:id,name',
                'coch:id,modelo,matricula',
                'parada:id,nombre,orden'
            ])
            ->orderBy('p.orden', 'asc')
            ->orderBy('reservas.coche_id', 'asc')
            ->select('reservas.*') //Incluye hora_inicio y hora_fin(metodo conseguido de la consulta)
            ->paginate(6);

        $total = $reservas->total();
        $totalPagina = $reservas->count();

        return view('coches.trazabilidad', [
            'evento' => $evento,
            'reservas' => $reservas,
            'total' => $total,
            'totalPagina' => $totalPagina
        ]);
    }

    public function show(Request $request, $id)
    {
        $query = trim($request->input('buscador', ''));
        $evento = Evento::findOrFail($id);

        $paradasTable = (new Parada())->getTable();

        $reservasQuery = Reserva::where('reservas.evento_id', $id)
            ->join($paradasTable . ' as p', 'reservas.parada_id', '=', 'p.id')
            ->with([
                'user:id,name',
                'coch:id,modelo,matricula',
                'parada:id,nombre,orden'
            ])
            ->select('reservas.*')
            ->orderBy('p.orden', 'asc')
            ->orderBy('reservas.coche_id', 'asc');

        if ($query !== '') {
            $reservasQuery->where(function ($q) use ($query) {
                $q->whereHas('parada', function ($qq) use ($query) {
                    $qq->where('nombre', 'like', "%{$query}%");
                })
                    ->orWhereHas('coch', function ($qq) use ($query) {
                        $qq->where('modelo', 'like', "%{$query}%")
                            ->orWhere('matricula', 'like', "%{$query}%");
                    })
                    ->orWhereHas('user', function ($qq) use ($query) {
                        $qq->where('name', 'like', "%{$query}%")
                            ->orWhere('tipo', 'like', "%{$query}%");
                    });
            });
        }

        $reservas = $reservasQuery
            ->paginate(6)
            ->withQueryString(); //Guarda los filtros en la URL.

        $total = $reservas->total();
        $totalPagina = $reservas->count();

        return view('coches.trazabilidad', compact(
            'evento',
            'reservas',
            'total',
            'totalPagina',
            'query'
        ));
    }

    public function export(Evento $evento)
    {
        //Parseamos la fecha para que salga sin los 0 del final.
        $fecha = Carbon::parse($evento->fecha)->format('Y-m-d');
        $filename = 'Reservas ' . $evento->nombre . '-' . $fecha . '.xlsx';

        return Excel::download(new ReservasExport($evento), $filename);
    }
}
