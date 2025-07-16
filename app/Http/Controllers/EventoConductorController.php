<?php

namespace App\Http\Controllers;

use App\Mail\ClienteRegistrado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ConfirmacionEventoMail;
use App\Models\Conductor;
use App\Models\Evento;

class EventoConductorController extends Controller
{
    public function enviarEmail($evento_id, $conductor_id)
    {
        //Generamos el token.
        $token = Str::random(32);

        DB::table('evento_conductor')
            ->where('evento_id', $evento_id)
            ->where('conductor_id', $conductor_id)
            ->update(['token' => $token]); //Guarda token en la tabla.

        // Busca el conductor con el modelo Conductor
        $conductor = Conductor::find($conductor_id);

        if (!$conductor || !$conductor->email) {
            return response()->json(['error' => 'Conductor no encontrado o sin email'], 404);
        }

        $url = url('/evento-confirmacion/' . $token); //Se crea url.

        //Manda email de confirmacion.
        Mail::to($conductor->email)->send(new ConfirmacionEventoMail($url));
        $evento = Evento::find($evento_id); //Pasar el evento_id que se coghe por parametro para ver boton volver.

        //Pasamos el evento que se pasara en la vista $evento.
        return view('emails.mensaje' , ['evento' => $evento]);
    }

    public function mostrarFormulario($token)
    {
        $registro = DB::table('evento_conductor')->where('token', $token)->first(); //Aqui se verifica que el token existe en la tabla evento_conductor.

        if (!$registro) {
            return redirect('/')->with('error', 'Token invalido o expirado');
        }

        $evento = Evento::find($registro->evento_id);
        $conductor = Conductor::find($registro->conductor_id);

        return view('invitados.update', ['token' => $token, 'evento' => $evento, 'conductor' => $conductor, 'edicion' => true]);
    }

    public function enviarFormulario(Request $request, $token)
    {
        $registro = DB::table('evento_conductor')->where('token', $token)->first();

        if (!$registro) {
            return redirect('/')->with('error', 'Token inválido o expirado');
        }

        $conductor = Conductor::find($registro->conductor_id);

        if (!$conductor) {
            return redirect('/')->with('error', 'Invitado no encontrado');
        }

        if($request->hasFile('carnet')){
            $carnetPath = $request->file('carnet')->store('carnets' , 'public');
        }

        Conductor::where('email', $request->input('email'))->update([
            'cif' => $request->input('cif'),
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'telefono' => $request->input('telefono'),
            'empresa' => $request->input('empresa'),
            'vehiculo_prop' => $request->input('vehiculo_prop'),
            'vehiculo_emp' => $request->input('vehiculo_emp'),
            'intolerancia' => $request->input('intolerancia'),
            'preferencia' => $request->input('preferencia'),
            'carnet_caducidad' => $request->input('carnet_caducidad'),
            'etiqueta' => $request->input('etiqueta'),
            /* 'kam' => $request->input('kam'), */
            'asiste' => $request->input('asiste'),
            'dni' => $request->input('dni'),
            'proteccion_datos' => $request->input('proteccion_datos'),
            'carnet' => $carnetPath ?? null, // si existe
        ]);

        //Busca el evento que pasamos por parametro y recoge el evento_id.
        $evento = Evento::find($registro->evento_id);
        //Envia correo al adminstrador.
        Mail::to('gestion@bi-sion.es')->send(new ClienteRegistrado($conductor , $evento));

        // Invalidamos el token en evento_conductor , para que el usaurio pueda acceder una sola vez.
        DB::table('evento_conductor')
            ->where('token', $token)
            ->update(['token' => null]);

        return view('emails.agradecimiento');
    }
}
