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

        // 1) Pagina SOLO los pares (parada_id, coche_id) con orden estable
        $pares = Reserva::where('evento_id', $id)
            ->select('parada_id', 'coche_id')
            ->distinct()
            ->orderBy('parada_id')   // orden estable para paginación
            ->orderBy('coche_id')
            ->paginate(6);

        // 2) Carga reservas EXACTAMENTE de los pares de ESTA página (sin producto cartesiano)
        $reservasPagina = Reserva::where('evento_id', $id)
            ->where(function ($q) use ($pares) {
                foreach ($pares as $par) {
                    $q->orWhere(function ($qq) use ($par) {
                        $qq->where('parada_id', $par->parada_id)
                            ->where('coche_id',  $par->coche_id);
                    });
                }
            })
            ->with(['user:id,name', 'coch:id,modelo,matricula'])
            ->get(['id', 'user_id', 'parada_id', 'coche_id', 'tipo', 'hora_inicio', 'hora_fin']);

        // 3) Catálogos SOLO de lo que se usa en esta página
        $paradaIds = $pares->pluck('parada_id')->unique()->values();
        $cocheIds  = $pares->pluck('coche_id')->unique()->values();

        $paradas = Parada::whereIn('id', $paradaIds)
            ->get(['id', 'nombre'])
            ->keyBy('id');

        $coches = Coch::whereIn('id', $cocheIds)
            ->get(['id', 'modelo', 'matricula'])
            ->keyBy('id');

        // 4) Mapa por [parada_id][coche_id] => reservas (para tu Blade)
        $reservasMap = $reservasPagina->groupBy(['parada_id', 'coche_id']);

        // 5) Total: usa el total del paginador de pares (no cuentes reservas aquí)
        $total = $pares->total();
        $totalPagina = $pares->count();

        return view('coches.trazabilidad', [
            'evento'      => $evento,
            'pares'       => $pares,        // es el paginador que debes usar en links() si lo necesitas
            'paradas'     => $paradas,      // catálogo para tus bucles
            'coches'      => $coches,       // catálogo para tus bucles
            'reservasMap' => $reservasMap,  // datos para render
            'total'       => $total,        // total global de pares únicos
            'totalPagina' => $totalPagina
        ]);
    }

    public function show(Request $request, $id)
    {
        $query = trim($request->input('buscador', ''));

        $evento = Evento::findOrFail($id);

        // 1) Filtra PARADAS por: nombre, o reservas.coch, o reservas.user
        $paradasQuery = $evento->paradas()->with([
            'reservas.user:id,name,email',
            'reservas.coch:id,modelo,matricula',
        ]);

        if ($query !== '') {
            $paradasQuery->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                    ->orWhereHas('reservas.coch', function ($qq) use ($query) {
                        $qq->where('modelo', 'like', "%{$query}%")
                            ->orWhere('matricula', 'like', "%{$query}%");
                    })
                    ->orWhereHas('reservas.user', function ($qq) use ($query) {
                        $qq->where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
                    });
            });
        }

        // 2) Paginamos paradas
        $paradas = $paradasQuery
            ->paginate(6)
            ->withQueryString();

        $total       = $paradas->total();
        $totalPagina = $paradas->count();

        // 3) Detectar si el término coincide con NOMBRE DE PARADA
        $matchPorNombreParada = false;
        if ($query !== '') {
            $matchPorNombreParada = \App\Models\Parada::where('evento_id', $id)
                ->where('nombre', 'like', "%{$query}%")
                ->exists();
        }

        // 4) Traer RESERVAS de las paradas de esta página
        $paradaIds = $paradas->getCollection()->pluck('id');

        $reservasQuery = \App\Models\Reserva::with([
            'user:id,name,email',
            'coch:id,modelo,matricula',
        ])
            ->whereIn('parada_id', $paradaIds);

        //  Si el usuario buscó por nombre de parada, NO filtres por coche/user.
        //    Si no hubo match por nombre de parada, entonces sí filtramos por coche/user.
        if ($query !== '' && !$matchPorNombreParada) {
            $reservasQuery->where(function ($q) use ($query) {
                $q->whereHas('coch', function ($qq) use ($query) {
                    $qq->where('modelo', 'like', "%{$query}%")
                        ->orWhere('matricula', 'like', "%{$query}%");
                })
                    ->orWhereHas('user', function ($qq) use ($query) {
                        $qq->where('name', 'like', "%{$query}%")
                            ->orWhere('email', 'like', "%{$query}%");
                    });
            });
        }

        $reservas = $reservasQuery->get();

        // 5) Data para tu Blade
        $reservasMap = $reservas->groupBy(['parada_id', 'coche_id']);
        $cocheIds    = $reservas->pluck('coche_id')->unique()->values();
        $coches      = \App\Models\Coch::whereIn('id', $cocheIds)->get();

        return view('coches.trazabilidad', compact(
            'evento',
            'paradas',
            'total',
            'totalPagina',
            'query',
            'coches',
            'reservasMap'
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
