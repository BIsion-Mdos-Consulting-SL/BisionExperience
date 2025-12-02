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
            'evento_id' => ['required', 'integer', 'exists:evento,id'],
            'parada_id' => ['required', 'integer', 'exists:parada,id'],
            'coche_id'  => ['required', 'integer', 'exists:coches,id'],
            'accion'    => ['required', 'in:inicio,fin,reset'], //Acciones que me ayudaran a dar inicio , fin y reiniciar mediante botones (accion del cliente - usuario)
        ]);

        $userId = Auth::id();
        $now = Carbon::now('Europe/Madrid');

        // IMPORTANTE: transacción + bloqueo para evitar carreras
        return DB::transaction(function () use ($data, $userId, $now) {

            $mismoCocheOtraParada = Reserva::where('evento_id', $data['evento_id'])
                ->where('user_id', $userId)
                ->where('coche_id', $data['coche_id'])
                ->where('parada_id', '!=', $data['parada_id'])
                ->exists();

            if ($mismoCocheOtraParada) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No puedes usar el mismo coche en paradas distintas del mismo evento.'
                ], 422);
            }

            // Reserva (del usuario) para esta parada
            $reserva = Reserva::firstOrCreate(
                [
                    'evento_id' => $data['evento_id'],
                    'parada_id' => $data['parada_id'],
                    'user_id'   => $userId,
                ],
                [
                    'coche_id' => $data['coche_id'],
                    'tipo'     => 'acompañante',
                ]
            );

            // Si cambió de coche, lo actualizamos
            if ((int)$reserva->coche_id !== (int)$data['coche_id']) {
                $reserva->coche_id = $data['coche_id'];
            }

            // ====== ACCIÓN: INICIO ======
            if ($data['accion'] === 'inicio') {
                $cocheEnUso = Reserva::where('evento_id', $data['evento_id'])
                    ->where('coche_id', $data['coche_id'])
                    ->whereNotNull('hora_inicio')
                    ->whereNull('hora_fin')
                    ->lockForUpdate() // bloquea filas candidatas
                    ->first();

                //Si el coche que esta usando el usuario1  lo ve el usuario2 y le quiere usar , sale la alerta , si otro usuario esta usando ese coche , bloqueamos.
                if ($cocheEnUso && $cocheEnUso->user_id !== $userId) {
                    return response()->json([
                        'ok' => false,
                        'code' => 'car_in_use',
                        'message' => 'El coche está en uso. Espera a su finalización.',
                        'en_uso_por' => $cocheEnUso->user_id, // opcional/debug
                    ], 409);
                }

                // Si el mismo usuario1 vuelve a dar "inicio" y no ha dado "fin", lo dejamos insoleto , tanto con el mismo coche , como con otro , no cambia en la BD.
                if ($reserva->hora_inicio && !$reserva->hora_fin) {
                    return response()->json([
                        'ok'          => true,
                        'message'     => 'La parada ya estaba iniciada.',
                        'reserva_id'  => $reserva->id,
                        'hora_inicio' => (string) $reserva->hora_inicio,
                        'hora_fin'    => (string) $reserva->hora_fin,
                        'avanzar'     => false,
                        'finalizado'  => false,
                    ], 200);
                }

                //Hora inicio formateada.
                $reserva->hora_inicio = $now->format('H:i:s');
                $reserva->save(); //Guardamos cambios realizados.

                //Mensaje de inicio de parada.
                return response()->json([
                    'ok'          => true,
                    'message'     => 'Parada ' . $reserva->parada_id . ' iniciada.',
                    'reserva_id'  => $reserva->id,
                    'hora_inicio' => (string) $reserva->hora_inicio,
                    'hora_fin'    => (string) $reserva->hora_fin,
                    'avanzar'     => false,
                    'finalizado'  => false,
                ], 200);
            }

            // ====== ACCION: RESET ======
            if ($data['accion'] === "reset") {
                //Si aun no ha iciado , no tiene sentido reinicar , asi que añadimos una condicion.
                if (!$reserva->hora_inicio) {
                    return response()->json([
                        'ok' => false,
                        'code' => 'no_start',
                        'message' => 'Debes iniciar la parada antes de resetearla.'
                    ], 422);
                }

                //Si ya tenia hora_fin , ya estaba parada  por lo cual se le agrega otra condicion.
                if ($reserva->hora_fin) {
                    return response()->json([
                        'ok' => true,
                        'message' => 'La parada ya estaba detenida.',
                        'reserva_id' => $reserva->id,
                        'hora_inicio' => (string) $reserva->hora_inicio,
                        'hora_fin'    => (string) $reserva->hora_fin,
                        'avanzar'     => false,
                        'finalizado'  => false,
                    ], 200);
                }

                //Se marca fin para liberar el coche y guardar trazabilidad.
                $reserva->hora_fin = $now->format('H:i:s');
                $reserva->save();

                return response()->json([
                    'ok' => true,
                    'message'     => 'Parada ' . $reserva->parada_id . ' reseteada (tiempo detenido).',
                    'reserva_id' => $reserva->id,
                    'hora_inicio' => (string) $reserva->hora_inicio,
                    'hora_fin' => (string) $reserva->hora_fin,
                    'avanzar'     => false,
                    'finalizado'  => false
                ], 200);
            }

            // ====== ACCIÓN: FIN ======
            // Validar que exista hora_inicio , muestra el alert si le das a stop primero.
            if (!$reserva->hora_inicio) {
                return response()->json([
                    'ok' => false,
                    'code' => 'no_start',
                    'message' => 'Debes iniciar la parada antes de finalizarla.',
                ], 422);
            }

            //Hora fin formateada.
            $reserva->hora_fin = $now->format('H:i:s');
            $reserva->save(); //Guardamos cambios.

            //Contamos el total de las paradas de cada evento.
            $totalParadas = Parada::where('evento_id', $data['evento_id'])->count();

            //Contamos el total de paradas finalizadas de cada evento.
            $finalizadas = Reserva::where('evento_id', $data['evento_id'])
                ->where('user_id', $userId)
                ->whereNotNull('hora_fin')
                ->distinct('parada_id')
                ->count('parada_id');

            /**Comprobamos si el usuario finalizo todas las paradas del evento */
            //Si hay paradas ($totalParadas > 0), si las finalizadas son iguales o más que las paradas totales ($finalizadas >= $totalParadas) → entonces $finalizadoTodo es true.

            $finalizadoTodo = $totalParadas > 0 && $finalizadas >= $totalParadas;

            //Devolvemos respuesta con todo lo creado anteriormente , se usan en el front.
            return response()->json([
                'ok'            => true,
                'message'       => 'Parada ' . $reserva->parada_id . ' finalizada.',
                'reserva_id'    => $reserva->id,
                'hora_inicio'   => (string) $reserva->hora_inicio,
                'hora_fin'      => (string) $reserva->hora_fin,
                'avanzar'       => true,
                'finalizado'    => $finalizadoTodo,
                'final_message' => $finalizadoTodo ? '¡Gracias por participar!' : null,
            ], 200);
        });
    }
}
