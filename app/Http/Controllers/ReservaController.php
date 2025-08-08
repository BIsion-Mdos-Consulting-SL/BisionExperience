<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmacionReservaMail;
use App\Models\Coch;
use App\Models\Conductor;
use App\Models\Evento;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ReservaController extends Controller
{
    //FUNCION PARA MANDAR EMAIL(PARADA).
    public function enviarEmail($evento_id, $conductor_id)
    {
        //Generamos el token.
        $token = Str::random(32);

        DB::table('conductor_reserva')->updateOrInsert([
            'evento_id' => $evento_id,
            'conductor_id' => $conductor_id
        ], [
            'token' => $token
        ]);

        $conductor = Conductor::find($conductor_id);

        if (!$conductor || !$conductor->email) {
            return response()->json([
                'error' => 'Coche no encontrado',
            ]);
        }

        $url = route('reserva.confirmacion', ['token' => $token]); //Se crea url.

        //Manda email de confirmacion.
        Mail::to($conductor->email)->send(new ConfirmacionReservaMail($url));
        $evento = Evento::find($evento_id);

        //Pasamos evento que se pasara en la vista $evento.
        return view('emails.mensaje_reserva', [
            'evento' => $evento,
            'url' => $url,
        ]);
    }

    //FUNCION PARA MOSTRAR FORMULARIO.
    public function mostrarFormulario($token)
    {
        $registro = DB::table('conductor_reserva')->where('token', $token)->first(); //Verificaion que el token existe en la tabla evento_conductor.

        if (!$registro) {
            return redirect('/')->with('error', 'Token invalido o expirado');
        }

        $evento = Evento::find($registro->evento_id);
        //Mostramos todos los campos de la tabla coches.
        $coche = Coch::all();
        $conductor = Conductor::find($registro->conductor_id);

        return view('invitados.reserva', ['token' => $token, 'evento' => $evento, 'conductor' => $conductor, 'edicion' => true, 'coche' => $coche]);
    }

    public function guardarReserva(Request $request, $paradaId)
    {
        $request->validate([
            'coche_id' => 'required|exists:coches,id',
            'tipo' => 'required|in:conductor,acompañante'
        ]);

        //Se pasa toda  la informacion completa del usuario.
        $userId = Auth::id();

        //Confirmamos si el coche ya esta usdao.
        $usado = Reserva::where('user_id', $userId)
            ->where('coche_id', $request->coche_id)
            ->exists();

        //Completamos con una condicion.
        if ($usado) {
            return back()->with('error', 'Ya reservaste este coche en otra parada.');
        }

        //Evitamos duplicados con esa misma parada.
        $reservado = Reserva::where('coche_id', $request->coche_id)
            ->where('parada_id', $paradaId)
            ->exists();

        //Completamos con otra condicion.
        if ($reservado) {
            return back()->with('error', 'Este coche ya fue reservado en otra parada.');
        }

        //Creamos la reserva.
        Reserva::create([
            'user_id' => $userId,
            'coche_id' => $request->coche_id,
            'parada_id' => $paradaId,
            'tipo' => $request->tipo
        ]);

        return redirect()->route('emails.agradecimiento_reserva', $paradaId)->with('success', 'Reserva realizada');
    }

    //FUNCION PARA BUSCAR POR MARCAS.
    public function buscarMarcas(Request $request)
    {
        $eventoId = $request->input('evento_id');
        $buscador = $request->input('marca');

        $marcas = Coch::select('marca')
            ->where('evento_id', $eventoId)
            ->where('marca', 'LIKE', '%' . $buscador . '%')
            ->pluck('marca')
            ->map(function ($m) {
                // Elimina caracteres invisibles y normaliza espacios
                $m = preg_replace('/\s+/', ' ', $m);
                $m = trim($m);
                return ucfirst(strtolower($m));
            })
            ->unique()
            ->values();

        return response()->json($marcas);
    }

    //FUNCION PARA BUSCAR POR MODELOS.
    public function buscarModelos(Request $request)
    {
        $eventoId = $request->input('evento_id');
        $marca = $request->input('marca');

        $modelos = Coch::where('evento_id', $eventoId)
            ->where('marca', $marca)
            ->select('id', 'modelo', 'version')
            ->get();

        return response()->json($modelos);
    }
}
