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
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;

class EventoConductorController extends Controller
{
    public function mostrarFormulario(Request $request, $token = null)
    {
        if ($token) {
            $registro = DB::table('evento_conductor')->where('token', $token)->first();

            if (!$registro) {
                return redirect('/')->with('error', 'Token invalido o expirado');
            }

            $evento = Evento::find($registro->evento_id);
            $conductor = Conductor::find($registro->conductor_id);

            return view('invitados.update', [
                'token' => $token,
                'evento' => $evento,
                'conductor' => $conductor,
                'edicion' => true
            ]);
        } else {
            //FIRMA PUBLICA 
            if (!URL::hasValidSignature($request)) {
                abort(403, 'Link no valido');
            }

            $key = 'inv-form:' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 50)) {
                abort(429, 'Demasiadas solicitudes , intenta en un minuto.');
            }

            RateLimiter::hit($key, 60);

            $evento = null;
            if ($slug = $request->query('evento')) {
                $evento = Evento::where('slug', $slug)->first();
            }

            return view('invitados.update', [
                'token' => null,
                'evento' => $evento,
                'conductor' => null,
                'edicion' => false
            ]);
        }
    }

    public function enviarFormulario(Request $request, $token = null)
    {
        $registro = null;
        $conductor = null;
        $evento = null;

        if ($token) {
            $registro = DB::table('evento_conductor')->where('token', $token)->first();

            if (!$registro) {
                return redirect('/')->with('error', 'Token inválido o expirado');
            }

            $conductor = Conductor::find($registro->conductor_id);

            if (!$conductor) {
                return redirect('/')->with('error', 'Invitado no encontrado');
            }

            //Busca el evento que pasamos por parametro y recoge el evento_id.
            $evento = Evento::find($registro->evento_id);
        } else {
            if (!URL::hasValidSignature($request)) {
                abort(403, 'Link no valido');
            }

            $key = 'inv-submit' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 50)) {
                return back()->withInput()->with('error', 'Demasiados intentos , espera un minuto.');
            }

            RateLimiter::hit($key, 60);
        }

        if ($request->filled('website')) {
            abort(403, 'Acceso denegado.');
        }

        $rules = [
            'nombre'            => 'required|string|max:255',
            'apellido'          => 'required|string|max:255',
            'email'             => ['required', 'email'],
            'telefono'          => 'required|string|max:9',
            'empresa'           => 'required|string|max:255',
            'cif'               => 'required|string|max:20',
            'dni'               => ['required', 'string', 'max:9'],
            'vehiculo_prop'     => 'nullable|in:si,no',
            'vehiculo_emp'      => 'nullable|in:si,no',
            'etiqueta'          => 'nullable|in:B,C,ECO,0',
            'carnet_caducidad'  => 'required|date',
            'intolerancia'      => 'nullable|string|max:255',
            'preferencia'       => 'required|in:carne,pescado',
            'proteccion_datos'  => 'required|in:1',
            'website'           => 'nullable|size:0', // honeypot
        ];

        if ($token) {
            $conductor->update([
                'cif'               => $request->input('cif'),
                'nombre'            => $request->input('nombre'),
                'apellido'          => $request->input('apellido'),
                'telefono'          => $request->input('telefono'),
                'empresa'           => $request->input('empresa'),
                'vehiculo_prop'     => $request->input('vehiculo_prop'),
                'vehiculo_emp'      => $request->input('vehiculo_emp'),
                'intolerancia'      => $request->input('intolerancia'),
                'preferencia'       => $request->input('preferencia'),
                'carnet_caducidad'  => $request->input('carnet_caducidad'),
                'etiqueta'          => $request->input('etiqueta'),
                'asiste'            => $request->input('asiste'),
                'dni'               => $request->input('dni'),
                'proteccion_datos'  => $request->input('proteccion_datos'),
                'email'             => $request->input('email'),
            ]);

            // Invalida el token (una sola vez)
            DB::table('evento_conductor')->where('token', $token)->update(['token' => null]);
        } else {
            $conductor = Conductor::create([
                'cif'               => $request->input('cif'),
                'nombre'            => $request->input('nombre'),
                'apellido'          => $request->input('apellido'),
                'telefono'          => $request->input('telefono'),
                'empresa'           => $request->input('empresa'),
                'vehiculo_prop'     => $request->input('vehiculo_prop'),
                'vehiculo_emp'      => $request->input('vehiculo_emp'),
                'intolerancia'      => $request->input('intolerancia'),
                'preferencia'       => $request->input('preferencia'),
                'carnet_caducidad'  => $request->input('carnet_caducidad'),
                'etiqueta'          => $request->input('etiqueta'),
                'asiste'            => $request->input('asiste'),
                'dni'               => $request->input('dni'),
                'proteccion_datos'  => $request->input('proteccion_datos'),
                'email'             => $request->input('email'),
            ]);

            // Asociación a evento si vino en el form (slug)
            if ($slug = $request->input('evento')) {
                $evento = Evento::where('slug', $slug)->first();
            }

            if (!isset($evento) || !$evento) {
                // Opción A (por fecha de creación, sencilla y segura):
                $evento = Evento::orderBy('fecha', 'desc')->first();
            }


            if ($evento && method_exists($conductor, 'eventos')) {
                $conductor->eventos()->syncWithoutDetaching([$evento->id]);
            }
        }

        Mail::to('gestion@bi-sion.es')->send(new ClienteRegistrado($conductor, $evento));

        return view('emails.agradecimiento');
    }
}
