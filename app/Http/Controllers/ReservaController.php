<?php

namespace App\Http\Controllers;

use App\Exports\PreReservaExport;
use App\Models\Coch;
use App\Models\Evento;
use App\Models\Parada;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReservaController extends Controller
{
    public function index($id)
    {
        $evento = Evento::findOrFail($id);

        $reservas = Reserva::where('reservas.evento_id', $id)
            ->join('parada as p', 'reservas.parada_id', '=', 'p.id')
            ->with([
                'user:id,name',
                'coch:id,modelo,matricula',
                'parada:id,nombre,orden', // asegúrate de que 'orden' existe en la tabla parada
            ])
            ->orderBy('p.orden', 'asc')         // primero orden por número de parada
            ->orderBy('reservas.coche_id', 'asc') // luego por coche si quieres
            ->select('reservas.*')              // importante cuando haces join
            ->paginate(6);                      // 6 reservas por página

        $total       = $reservas->total();   // total de reservas del evento
        $totalPagina = $reservas->count();   // reservas en esta página

        return view('coches.pre_reserva', [
            'evento'      => $evento,
            'reservas'    => $reservas,
            'total'       => $total,
            'totalPagina' => $totalPagina,
        ]);
    }

    public function cargaDatos()
    {
        $userId = Auth::id();

        // 1) Evento activo (el último creado)
        $evento = Evento::latest('id')->first();
        if (!$evento) {
            return response()->json([
                'error'   => 'SIN_EVENTO',
                'message' => 'No hay evento activo'
            ], 404);
        }

        // 2) Siguiente parada pendiente del usuario
        $ultimaOrdenHecha = Reserva::where('reservas.evento_id', $evento->id)
            ->where('reservas.user_id', $userId)
            ->join('parada', 'parada.id', '=', 'reservas.parada_id')
            ->max('parada.orden');

        $siguienteOrden = ($ultimaOrdenHecha ?? 0) + 1;

        $parada = Parada::where('evento_id', $evento->id)
            ->where('orden', $siguienteOrden)
            ->first();

        //Si ya no hay más paradas pendientes, devolvemos la última
        if (!$parada) {
            $parada = Parada::where('evento_id', $evento->id)
                ->orderByDesc('orden')
                ->first();

            if (!$parada) {
                return response()->json([
                    'error'     => 'SIN_PARADA',
                    'message'   => 'No hay paradas para el evento',
                    'evento_id' => $evento->id
                ], 404);
            }
        }

        // 3) Capacidad fija (igual que en storeReserva)
        $capacidadFija = 3;

        // 4) Progreso
        $totalParadas = Parada::where('evento_id', $evento->id)->count();

        $completadas = Reserva::where('evento_id', $evento->id)
            ->where('user_id', $userId)
            ->distinct('parada_id')
            ->count('parada_id');

        $siguienteParada = Parada::where('evento_id', $evento->id)
            ->where('orden', '>', $parada->orden)
            ->orderBy('orden')
            ->first();

        // 5) Select

        // Coches ya usados por este usuario en el evento (no puede repetir coche en paradas posteriores)
        $usados = Reserva::where('evento_id', $evento->id)
            ->where('user_id', $userId)
            ->pluck('coche_id')
            ->all();

        // Coches con conductor asignado SOLO en esta parada
        $conductores = Reserva::where('evento_id', $evento->id)
            ->where('parada_id', $parada->id)
            ->where('tipo', 'conductor')
            ->pluck('coche_id')
            ->all();

        // Ocupación de acompañantes SOLO en la parada actual
        $ocupacion = Reserva::select('coche_id', DB::raw("SUM(CASE WHEN tipo = 'acompañante' THEN 1 ELSE 0 END) as acomp"))
            ->where('evento_id', $evento->id)
            ->where('parada_id', $parada->id)
            ->groupBy('coche_id')
            ->pluck('acomp', 'coche_id');

        // Coches reservados por OTROS usuarios en ESTA parada
        $reservadosEnParada = Reserva::where('evento_id', $evento->id)
            ->where('parada_id', $parada->id)
            ->where('user_id', '!=', $userId)
            ->pluck('coche_id')
            ->toArray();

        // Coches que ESTE usuario ya tiene reservados en ESTA parada
        $cocheIdsUsuarioEnParada = Reserva::where('evento_id', $evento->id)
            ->where('parada_id', $parada->id)
            ->where('user_id', $userId)
            ->pluck('coche_id')
            ->all();

        // Query base de coches del evento
        $cochesQuery = Coch::where('evento_id', $evento->id);

        // Si el usuario ya tiene reserva en esta parada -> solo mostramos ese/estos coches
        if ($completadas < $totalParadas && !empty($cocheIdsUsuarioEnParada)) {
            $cochesQuery->whereIn('id', $cocheIdsUsuarioEnParada);
        }

        // Construcción de lista de coches con flags
        $coches = $cochesQuery
            ->get(['id', 'marca', 'modelo', 'matricula'])
            ->map(function ($c) use ($usados, $conductores, $ocupacion, $capacidadFija, $reservadosEnParada) {
                $acompanantes = (int) ($ocupacion[$c->id] ?? 0);
                $plazasDisp   = max(0, $capacidadFija - $acompanantes);

                return [
                    'id'                 => $c->id,
                    'marca'              => $c->marca,
                    'modelo'             => $c->modelo,
                    'matricula'          => $c->matricula,
                    'usado'              => in_array($c->id, $usados, true),
                    'conductor_asignado' => in_array($c->id, $conductores, true), // ahora SOLO esta parada
                    'plazas_disponibles' => $plazasDisp,
                    'lleno'              => $plazasDisp <= 0,
                    'ocupado_en_parada'  => in_array($c->id, $reservadosEnParada, true),
                ];
            })
            ->values();

        // 6) Respuesta para el JS
        return response()->json([
            'evento'   => $evento,
            'parada'   => $parada, // siguiente pendiente o última
            'coches'   => $coches,
            'progreso' => [
                'total'           => $totalParadas,
                'completadas'     => $completadas,
                'actual_orden'    => $parada->orden,
                'siguiente_id'    => $siguienteParada?->id,
                'siguiente_orden' => $siguienteParada?->orden,
            ],
        ]);
    }



    public function storeReserva(Request $request, Evento $evento, Parada $parada)
    {
        $data = $request->validate([
            'tipo'     => 'required|in:conductor,acompañante',
            'coche_id' => 'required|exists:coches,id',
        ]);

        $capacidadFija = 3; // acompañantes por parada

        $coche  = Coch::findOrFail($data['coche_id']);
        $userId = Auth::id();

        // Debe completar paradas en orden
        if ($parada->orden > 1) {
            $tieneAnterior = Reserva::where('evento_id', $evento->id)
                ->where('user_id', $userId)
                ->whereHas('parada', fn($q) => $q->where('orden', $parada->orden - 1))
                ->exists();

            if (!$tieneAnterior) {
                return $request->expectsJson()
                    ? response()->json(['ok' => false, 'error' => 'Primero completa la parada anterior.'], 422)
                    : back()->with('error', 'Primero completa la parada anterior.');
            }
        }

        $result = DB::transaction(function () use ($evento, $parada, $coche, $data, $userId, $capacidadFija) {

            // Evitar reservas duplicadas en la misma parada
            $yaReservoEstaParada = Reserva::where([
                'evento_id' => $evento->id,
                'parada_id' => $parada->id,
                'user_id'   => $userId,
            ])
                ->lockForUpdate()
                ->exists();

            if ($yaReservoEstaParada) {
                return ['error' => 'Ya tienes una reserva en esta parada.'];
            }

            // El mismo usuario no puede volver a usar el mismo coche en paradas anteriores (< orden actual)
            $cocheUsadoAntes = Reserva::where('evento_id', $evento->id)
                ->where('user_id', $userId)
                ->where('coche_id', $coche->id)
                ->whereHas('parada', function ($q) use ($parada) {
                    $q->where('orden', '<', $parada->orden);
                })
                ->lockForUpdate()
                ->exists();

            if ($cocheUsadoAntes) {
                return [
                    'error' => 'No puedes volver a escoger este coche, ya lo escogiste en una parada anterior.'
                ];
            }

            // Capacidad acompañantes por parada
            if ($data['tipo'] === 'acompañante') {
                $ocupadosEnParada = Reserva::where([
                    'evento_id' => $evento->id,
                    'parada_id' => $parada->id,
                    'coche_id'  => $coche->id,
                    'tipo'      => 'acompañante',
                ])
                    ->lockForUpdate()
                    ->count();

                if ($ocupadosEnParada >= $capacidadFija) {
                    return ['error' => 'Este coche ya no tiene mas plazas en esta parada.'];
                }
            }

            // Crear reserva
            Reserva::create([
                'evento_id' => $evento->id,
                'parada_id' => $parada->id,
                'coche_id'  => $coche->id,
                'user_id'   => $userId,
                'tipo'      => $data['tipo'],
            ]);

            return ['ok' => true];
        });

        if (!($result['ok'] ?? false)) {
            return $request->expectsJson()
                ? response()->json(['ok' => false, 'error' => $result['error'] ?? 'No se pudo crear la reserva.'])
                : back()->with('error', $result['error'] ?? 'No se pudo crear la reserva.');
        }

        // RESPUESTA JSON PARA AJAX
        if ($request->expectsJson()) {

            // Progreso
            $total = Parada::where('evento_id', $evento->id)->count();
            $completadas = Reserva::where('evento_id', $evento->id)
                ->where('user_id', $userId)
                ->distinct('parada_id')
                ->count('parada_id');

            // siguiente parada
            $siguiente = Parada::where('evento_id', $evento->id)
                ->where('orden', '>', $parada->orden)
                ->orderBy('orden')
                ->first();

            $paradaTarget = $siguiente ?? $parada;

            // USADOS SOLO EN ESTA PARADA (paradaTarget)
            $usados = Reserva::where('evento_id', $evento->id)
                ->where('parada_id', $paradaTarget->id)
                ->where('user_id', $userId)
                ->where('tipo', 'acompañante')
                ->pluck('coche_id')
                ->toArray();

            // conductores en ESTA parada
            $conductores = Reserva::where('evento_id', $evento->id)
                ->where('parada_id', $paradaTarget->id)
                ->where('tipo', 'conductor')
                ->pluck('coche_id')
                ->toArray();

            // ocupación por paradaTarget
            $ocupacion = Reserva::select('coche_id', DB::raw("SUM(CASE WHEN tipo = 'acompañante' THEN 1 ELSE 0 END) as acomp"))
                ->where('evento_id', $evento->id)
                ->where('parada_id', $paradaTarget->id)
                ->groupBy('coche_id')
                ->pluck('acomp', 'coche_id');

            // reservados por otro usuario en ESTA PARADA
            $reservadosEnParada = Reserva::where('evento_id', $evento->id)
                ->where('parada_id', $paradaTarget->id)
                ->where('user_id', '!=', $userId)
                ->pluck('coche_id')
                ->toArray();

            //Construir lista coches
            $coches = Coch::where('evento_id', $evento->id)
                ->get(['id', 'marca', 'modelo', 'matricula'])
                ->map(function ($c) use ($usados, $conductores, $ocupacion, $capacidadFija, $reservadosEnParada) {

                    $acompanantes = (int)($ocupacion[$c->id] ?? 0);
                    $plazasDisp = max(0, $capacidadFija - $acompanantes);

                    return [
                        'id'                 => $c->id,
                        'marca'              => $c->marca,
                        'modelo'             => $c->modelo,
                        'matricula'          => $c->matricula,
                        'usado'              => in_array($c->id, $usados, true),
                        'conductor_asignado' => in_array($c->id, $conductores, true),
                        'plazas_disponibles' => $plazasDisp,
                        'lleno'              => $plazasDisp <= 0,
                        'ocupado_en_parada'  => in_array($c->id, $reservadosEnParada, true),
                    ];
                })
                ->values();

            return response()->json([
                'ok' => true,
                'progreso' => [
                    'total'           => $total,
                    'completadas'     => $completadas,
                    'siguiente_id'    => $siguiente?->id,
                    'siguiente_orden' => $siguiente?->orden,
                ],
                'coches' => $coches,
            ]);
        }

        return back()->with('success', 'Reserva creada con éxito.');
    }


    public function show(Request $request, $id)
    {
        $query  = trim($request->input('buscador', ''));
        $evento = Evento::findOrFail($id);

        //Nombre de la tabla parada
        $paradasTable = (new Parada())->getTable(); // te devolverá "parada"

        $reservasQuery = Reserva::where('reservas.evento_id', $id)
            ->join($paradasTable . ' as p', 'reservas.parada_id', '=', 'p.id')
            ->with([
                'user:id,name',
                'coch:id,modelo,matricula',
                'parada:id,nombre,orden',
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

        //Paginamos RESERVAS
        $reservas = $reservasQuery
            ->paginate(6)
            ->withQueryString(); //Guarda los filtros en la URL.

        $total       = $reservas->total();
        $totalPagina = $reservas->count();

        return view('coches.pre_reserva', compact(
            'evento',
            'reservas',
            'total',
            'totalPagina',
            'query'
        ));
    }

    public function export(Evento $evento)
    {
        $fecha = Carbon::parse($evento->fecha)->format('Y-m-d');
        $filename = 'Lista Pre - reservas ' . $evento->nombre . '-' . $fecha . '.xlsx';

        return Excel::download(new PreReservaExport($evento), $filename);
    }
}
