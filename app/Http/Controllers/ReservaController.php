<?php

namespace App\Http\Controllers;

use App\Models\Coch;
use App\Models\Evento;
use App\Models\Parada;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    public function cargaDatos()
    {
        $userId = Auth::id();

        // 1) Evento activo (mantengo tu criterio: el último)
        $evento = Evento::latest('id')->first();
        if (!$evento) {
            return response()->json([
                'error' => 'SIN_EVENTO',
                'message' => 'No hay evento activo'
            ], 404);
        }

        // 2) Siguiente parada pendiente del usuario (no la 1 siempre)
        //    Tomamos la mayor "orden" ya reservada por el user y buscamos la siguiente.
        $ultimaOrdenHecha = Reserva::where('reservas.evento_id', $evento->id)
            ->where('reservas.user_id', $userId)
            ->join('parada', 'parada.id', '=', 'reservas.parada_id')
            ->max('parada.orden');

        $siguienteOrden = ($ultimaOrdenHecha ?? 0) + 1;

        $parada = Parada::where('evento_id', $evento->id)
            ->where('orden', $siguienteOrden)
            ->first();

        // Fallback: si ya no hay más paradas pendientes, devolvemos la última para no romper el front
        if (!$parada) {
            $parada = Parada::where('evento_id', $evento->id)
                ->orderByDesc('orden')
                ->first();

            // Si tampoco hay paradas, devolvemos 404 coherente
            if (!$parada) {
                return response()->json([
                    'error' => 'SIN_PARADA',
                    'message' => 'No hay paradas para el evento',
                    'evento_id' => $evento->id
                ], 404);
            }
        }

        // 3) Capacidad fija (igual que en storeReserva)
        $capacidadFija = 3;

        // 4) Progreso
        $totalParadas  = Parada::where('evento_id', $evento->id)->count();

        $completadas = Reserva::where('evento_id', $evento->id)
            ->where('user_id', $userId)
            ->distinct('parada_id')
            ->count('parada_id');

        $siguienteParada  = Parada::where('evento_id', $evento->id)
            ->where('orden', '>', $parada->orden)
            ->orderBy('orden')
            ->first();

        // 5) Flags para el <select>

        // Coches ya usados por este usuario en el evento (no puede repetir coche en paradas posteriores)
        $usados = Reserva::where('evento_id', $evento->id)
            ->where('user_id', $userId)
            ->pluck('coche_id')
            ->all();

        // Coches con conductor asignado en TODO el evento
        $conductores = Reserva::where('evento_id', $evento->id)
            ->where('tipo', 'conductor')
            ->pluck('coche_id')
            ->all();

        // Ocupación de acompañantes SOLO en la parada actual (la que vamos a mostrar)
        $ocupacion = Reserva::select('coche_id', DB::raw("SUM(CASE WHEN tipo = 'acompañante' THEN 1 ELSE 0 END) as acomp"))
            ->where('evento_id', $evento->id)
            ->where('parada_id', $parada->id)
            ->groupBy('coche_id')
            ->pluck('acomp', 'coche_id');

        // Construcción de lista de coches con flags
        $coches = Coch::where('evento_id', $evento->id)
            ->get(['id', 'marca', 'modelo', 'matricula'])
            ->map(function ($c) use ($usados, $conductores, $ocupacion, $capacidadFija) {
                $acompanantes = (int) ($ocupacion[$c->id] ?? 0);
                $plazasDisp   = max(0, $capacidadFija - $acompanantes);

                return [
                    'id'                 => $c->id,
                    'marca'              => $c->marca,
                    'modelo'             => $c->modelo,
                    'matricula'          => $c->matricula,
                    'usado'              => in_array($c->id, $usados, true),
                    'conductor_asignado' => in_array($c->id, $conductores, true),
                    'plazas_disponibles' => $plazasDisp,
                    'lleno'              => $plazasDisp <= 0,
                ];
            })
            ->values();

        // 6) Respuesta (misma forma que espera tu JS)
        return response()->json([
            'evento'   => $evento,
            'parada'   => $parada, // <- ahora es la siguiente pendiente
            'coches'   => $coches,
            'progreso' => [
                'total'          => $totalParadas,
                'completadas'    => $completadas,
                'actual_orden'   => $parada->orden,
                'siguiente_id'   => $siguienteParada?->id,
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

        //Capacidad maxima de plazas del coche
        $capacidadFija = 3;

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

            // Evita que el mismo usuario haga dos reservas en la misma parada
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

            // Un conductor por coche para todo el evento
            if ($data['tipo'] === 'conductor') {
                $yaTieneConductor = Reserva::where([
                    'evento_id' => $evento->id,
                    'coche_id'  => $coche->id,
                    'tipo'      => 'conductor',
                ])
                    ->lockForUpdate()
                    ->exists();

                if ($yaTieneConductor) {
                    return ['error' => 'Ese coche ya tiene conductor.'];
                }
            }

            // El usuario no puede reutilizar el mismo coche en paradas posteriores
            $cocheUsadoAntes = Reserva::where('evento_id', $evento->id)
                ->where('user_id', $userId)
                ->where('coche_id', $coche->id)
                ->whereHas('parada', fn($q) => $q->where('orden', '<', $parada->orden))
                ->lockForUpdate()
                ->exists();

            if ($cocheUsadoAntes) {
                return ['error' => 'No puedes volver a elegir ese coche en otra parada.'];
            }

            // Capacidad de acompañantes POR PARADA (con capacidad fija)
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

        if ($request->expectsJson()) {
            // Progreso (barra bootstramp)
            $total = Parada::where('evento_id', $evento->id)->count();
            $completadas = Reserva::where('evento_id', $evento->id)
                ->where('user_id', $userId)
                ->distinct('parada_id')
                ->count('parada_id');

            // Siguiente parada (orden > actual)
            $siguiente = Parada::where('evento_id', $evento->id)
                ->where('orden', '>', $parada->orden)
                ->orderBy('orden')
                ->first();

            // Parada sobre la que pintaremos la lista (la siguiente si existe; si no, la actual)
            $paradaTarget = $siguiente ?? $parada;

            // Coches ya usados por el usuario (para deshabilitar repetir coche)
            $usados = Reserva::where('evento_id', $evento->id)
                ->where('user_id', $userId)
                ->pluck('coche_id')
                ->all();

            // Coches que ya tienen conductor asignado en el evento
            $conductores = Reserva::where('evento_id', $evento->id)
                ->where('tipo', 'conductor')
                ->pluck('coche_id')
                ->all();

            // Ocupación de acompañantes POR parada target
            //SUM(CASE WHEN tipo = 'acompañante' THEN 1 ELSE 0 END) → suma 1 por cada acompañante, 0 en caso contrario → resultado: número de acompañantes por coche.
            $ocupacion = Reserva::select('coche_id', DB::raw("SUM(CASE WHEN tipo = 'acompañante' THEN 1 ELSE 0 END) as acomp"))
                ->where('evento_id', $evento->id)
                ->where('parada_id', $paradaTarget->id)
                ->groupBy('coche_id')
                ->pluck('acomp', 'coche_id');

            // Construcción de lista de coches SIN pedir columna inexistente
            $coches = Coch::where('evento_id', $evento->id)
                ->get(['id', 'marca', 'modelo', 'matricula'])
                ->map(function ($c) use ($usados, $conductores, $ocupacion, $capacidadFija) {
                    $acompanantes = (int) ($ocupacion[$c->id] ?? 0);
                    $plazasDisp   = max(0, $capacidadFija - $acompanantes);

                    return [
                        'id'                 => $c->id,
                        'marca'              => $c->marca,
                        'modelo'             => $c->modelo,
                        'matricula'          => $c->matricula,
                        'usado'              => in_array($c->id, $usados, true),
                        'conductor_asignado' => in_array($c->id, $conductores, true),
                        'plazas_disponibles' => $plazasDisp,
                        'lleno'              => $plazasDisp <= 0,
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
                // listado para repintar el select en el front
                'coches' => $coches,
            ]);
        }

        return back()->with('success', 'Reserva creada con éxito.');
    }
}
