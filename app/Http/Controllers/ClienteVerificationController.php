<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteVerificationController extends Controller
{
    public function enviar(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Si estás logueado como NO-cliente (admin), no hagas nada (respuesta neutra)
        if (Auth::check() && Auth::user()->rol !== 'cliente') {
            return back()->with('status', 'Si el correo es válido, te hemos enviado un email.');
        }

        // Buscar por email
        $user = User::where('email', $request->email)->first();

        // Crear como cliente si no existe
        if (!$user) {
            $user = User::create([
                'email' => $request->email,
                'rol'   => 'cliente',
            ]);
        }

        //Si rol = cliente se enviara del modelo usuario la funcion para enviar la notificacion.
        if ($user->rol === 'cliente') {
            $user->enviarEmailNotificacion();
        }

        return back()->with('status', 'Si el correo es válido, te hemos enviado un email.');
    }

    public function verificacion(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        abort_unless($user->rol === 'cliente', 403);
        abort_unless(hash_equals($hash, sha1($user->getEmailForVerification())), 403);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        //Login + regenerar sesión (importante para fijar la cookie)
        Auth::login($user, true);
        $request->session()->regenerate();

        //Evitar 'intended' para no caer en rutas de admin
        return redirect()->route('cliente.dashboard');
    }
}
