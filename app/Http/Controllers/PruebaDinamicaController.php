<?php

namespace App\Http\Controllers;

use App\Models\Coch;
use App\Models\Evento;
use App\Models\Parada;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PruebaDinamicaController extends Controller
{
    //Cargamos los datos que queremos que aparezcan en el modal.
    public function cargaDatos()
    {
        //Mostramos el evento.
        $evento = Evento::latest('id')->first();
        if (!$evento) {
            return response()->json([
                'error' => 'Sin evento',
                'message' => 'No hay evento activo.'
            ], 404);
        }

        //Mostramos las paradas.
        $paradas = Parada::where('evento_id', $evento->id)
            ->orderBy('orden')
            ->get([
                'id',
                'evento_id',
                'nombre'
            ]);

        if (!$paradas) {
            return response()->json([
                'error' => 'Sin parada',
                'message' => 'No hay paradas para el evento.',
                'evento_id' => $evento->id
            ], 404);
        }

        $coches = Coch::where('evento_id', $evento->id)
            ->get(['id', 'marca', 'modelo', 'matricula']);

        $reservasUsuario = Reserva::where('evento_id', $evento->id)
            ->where('user_id', Auth::id())
            ->get(['id', 'evento_id', 'parada_id', 'coche_id', 'hora_inicio', 'hora_fin'])
            ->keyBy('parada_id');

        return response()->json(compact('evento', 'paradas', 'coches') + ['reservas' => $reservasUsuario]);
    }

    public function storePruebaDinamica(Request $request)
    {
        $data = $request->validate([
            // Lo dejo nullable porque tu JS no envía 'tipo'. Si luego lo mandas, puedes ponerlo required.
            'tipo'      => ['nullable', 'in:conductor,acompañante'],
            'evento_id' => ['required', 'integer', 'exists:evento,id'],
            'parada_id' => ['required', 'integer', 'exists:parada,id'],
            'coche_id'  => ['required', 'integer', 'exists:coches,id'],
            'accion'    => ['required', 'in:inicio,fin,reset'],
        ]);

        // Cargamos los modelos por ID (no usamos inyección de Evento $evento, Parada $parada)
        $evento = Evento::findOrFail($data['evento_id']);
        $parada = Parada::where('evento_id', $evento->id)
            ->where('id', $data['parada_id'])
            ->firstOrFail();

        $coche = Coch::findOrFail($data['coche_id']);
        $userId = Auth::id();
        $capacidadFija = 3;  // plazas acompañantes
        $now = Carbon::now('Europe/Madrid');

        $result = DB::transaction(function () use ($data, $userId, $now, $evento, $parada, $coche, $capacidadFija) {

            // Reserva más reciente de este usuario para esta parada
            $reserva = Reserva::where('evento_id', $evento->id)
                ->where('parada_id', $parada->id)
                ->where('user_id', $userId)
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            // El mismo coche no se puede usar en otra parada del mismo evento
            $cocheUsadoEnOtraParada = Reserva::where('evento_id', $evento->id)
                ->where('user_id', $userId)
                ->where('parada_id', '!=', $parada->id)
                ->where('coche_id', $coche->id)
                ->lockForUpdate()
                ->exists();

            if ($cocheUsadoEnOtraParada) {
                return [
                    'ok'    => false,
                    'code'  => 'same_car_other_stop',
                    'message' => 'No puedes usar este coche en otra parada diferente.',
                ];
            }

            // =====================
            // ACCIÓN: RESET
            // =====================
            if ($data['accion'] === 'reset') {

                if (!$reserva) {
                    return [
                        'ok'    => false,
                        'code'  => 'no_reserva',
                        'message' => 'No tienes una reserva en esta parada para resetear.',
                    ];
                }

                if (!$reserva->hora_inicio) {
                    return [
                        'ok'    => false,
                        'code'  => 'no_start',
                        'message' => 'Debes iniciar la parada antes de resetearla.',
                    ];
                }

                if ($reserva->hora_fin) {
                    return [
                        'ok'         => true,
                        'message'    => 'La parada ya estaba detenida.',
                        'reserva_id' => $reserva->id,
                        'hora_inicio' => (string) $reserva->hora_inicio,
                        'hora_fin'   => (string) $reserva->hora_fin,
                        'motivo_fin' => $reserva->motivo_fin,
                        'avanzar'    => false,
                        'finalizado' => false,
                    ];
                }

                $reserva->hora_fin   = $now->format('H:i:s');
                $reserva->motivo_fin = 'reset';
                $reserva->save();

                return [
                    'ok'          => true,
                    'message'     => 'Parada ' . $reserva->parada_id . ' reseteada (tiempo detenido).',
                    'reserva_id'  => $reserva->id,
                    'hora_inicio' => (string) $reserva->hora_inicio,
                    'hora_fin'    => (string) $reserva->hora_fin,
                    'motivo_fin'  => $reserva->motivo_fin,
                    'avanzar'     => false,
                    'finalizado'  => false,
                ];
            }

            // =====================
            // ACCIÓN: INICIO
            // =====================
            if ($data['accion'] === 'inicio') {

                // Comprobamos si el coche ya está en uso en este evento
                $cocheEnUso = Reserva::where('evento_id', $evento->id)
                    ->where('coche_id', $coche->id)
                    ->whereNotNull('hora_inicio')
                    ->whereNull('hora_fin')
                    ->lockForUpdate()
                    ->first();

                // Otro usuario lo está usando -> bloqueo
                if ($cocheEnUso && $cocheEnUso->user_id !== $userId) {
                    return [
                        'ok'      => false,
                        'code'    => 'car_in_use',
                        'message' => 'El coche está en uso. Espera a su finalización.',
                    ];
                }

                // Si ya está iniciada y sin fin, no hacemos nada más
                if ($reserva && $reserva->hora_inicio && !$reserva->hora_fin) {
                    return [
                        'ok'          => true,
                        'message'     => 'La parada ya estaba iniciada.',
                        'reserva_id'  => $reserva->id,
                        'hora_inicio' => (string) $reserva->hora_inicio,
                        'hora_fin'    => (string) $reserva->hora_fin,
                        'avanzar'     => false,
                        'finalizado'  => false,
                    ];
                }

                $tipo = $data['tipo'] ?? 'conductor';

                // Si es acompañante, comprobamos plazas en el coche en esta parada
                if ($tipo === 'acompañante') {
                    $ocupadosEnParada = Reserva::where('evento_id', $evento->id)
                        ->where('parada_id', $parada->id)
                        ->where('coche_id', $coche->id)
                        ->where('tipo', 'acompañante')
                        ->whereNull('hora_fin')
                        ->lockForUpdate()
                        ->count();

                    if ($ocupadosEnParada >= $capacidadFija) {
                        return [
                            'ok'      => false,
                            'code'    => 'no_places',
                            'message' => 'Este coche ya no tiene más plazas en esta parada.',
                        ];
                    }
                }

                // Creamos o actualizamos la reserva
                if (!$reserva) {
                    $reserva = new Reserva();
                    $reserva->evento_id = $evento->id;
                    $reserva->parada_id = $parada->id;
                    $reserva->user_id   = $userId;
                }

                $reserva->coche_id = $coche->id;
                $reserva->tipo     = $tipo;

                // Si estaba finalizada, limpiamos fin para nuevo flujo
                if ($reserva->hora_fin) {
                    $reserva->hora_fin   = null;
                    $reserva->motivo_fin = null;
                }

                $reserva->hora_inicio = $now->format('H:i:s');
                $reserva->save();

                return [
                    'ok'          => true,
                    'message'     => 'Parada ' . $reserva->parada_id . ' iniciada.',
                    'reserva_id'  => $reserva->id,
                    'hora_inicio' => (string) $reserva->hora_inicio,
                    'hora_fin'    => (string) $reserva->hora_fin,
                    'avanzar'     => false,
                    'finalizado'  => false,
                ];
            }

            // =====================
            // ACCIÓN: FIN
            // =====================
            if ($data['accion'] === 'fin') {

                if (!$reserva || !$reserva->hora_inicio) {
                    return [
                        'ok'      => false,
                        'code'    => 'no_start',
                        'message' => 'Debes iniciar la parada antes de finalizarla.',
                    ];
                }

                if ($reserva->hora_fin) {
                    return [
                        'ok'          => true,
                        'message'     => 'La parada ya estaba finalizada.',
                        'reserva_id'  => $reserva->id,
                        'hora_inicio' => (string) $reserva->hora_inicio,
                        'hora_fin'    => (string) $reserva->hora_fin,
                        'motivo_fin'  => $reserva->motivo_fin,
                        'avanzar'     => false,
                        'finalizado'  => false,
                    ];
                }

                // Hora fin formateada
                $reserva->hora_fin   = $now->format('H:i:s');
                $reserva->motivo_fin = 'fin';
                $reserva->save();

                // Total de paradas del evento
                $totalParadas = Parada::where('evento_id', $evento->id)->count();

                // Paradas finalizadas por este usuario
                $finalizadas = Reserva::where('evento_id', $evento->id)
                    ->where('user_id', $userId)
                    ->whereNotNull('hora_fin')
                    ->distinct('parada_id')
                    ->count('parada_id');

                $finalizadoTodo = $totalParadas > 0 && $finalizadas >= $totalParadas;

                return [
                    'ok'            => true,
                    'message'       => 'Parada ' . $reserva->parada_id . ' finalizada.',
                    'reserva_id'    => $reserva->id,
                    'hora_inicio'   => (string) $reserva->hora_inicio,
                    'hora_fin'      => (string) $reserva->hora_fin,
                    'motivo_fin'    => $reserva->motivo_fin,
                    'avanzar'       => true,
                    'finalizado'    => $finalizadoTodo,
                    'final_message' => $finalizadoTodo ? '¡Gracias por participar!' : null,
                ];
            }

            // Acción inválida (por si acaso)
            return [
                'ok'      => false,
                'code'    => 'invalid_action',
                'message' => 'Acción no válida.',
            ];
        });

        // Respuesta HTTP según el resultado
        if (!($result['ok'] ?? false)) {
            $status = 422;

            if (($result['code'] ?? null) === 'car_in_use') {
                $status = 409;
            }

            // devolvemos el JSON tal como lo espera tu JS (json.code, json.message, json.ok)
            return response()->json($result, $status);
        }

        return response()->json($result, 200);
    }
}
