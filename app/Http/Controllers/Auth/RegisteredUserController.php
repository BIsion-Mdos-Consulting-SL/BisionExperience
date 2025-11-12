<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Conductor;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Usa el nombre real de la tabla de Conductor (evita hardcodear 'conductor')
        $conductorTable = (new Conductor)->getTable();
        $usersTable     = (new User)->getTable();

        $validated = $request->validate([
            // Déjalo nullable si tu columna users.name es nullable; si NO lo es, cambia a 'required'
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                // Debe existir en la tabla de conductores (correo autorizado)
                Rule::exists($conductorTable, 'email'),
                // Y NO debe existir ya en users (evita constraint violation)
                Rule::unique($usersTable, 'email'),
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Normaliza a minúsculas por las dudas
        $email = mb_strtolower($validated['email']);

        // Crea el usuario (usa solo lo validado)
        $user = User::create([
            'name'     => $validated['name'] ?? null,
            'email'    => $email,
            'password' => Hash::make($validated['password']),
            'rol'      => 'cliente', // rol por defecto al registrarse
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('cliente.dashboard');
    }
}
