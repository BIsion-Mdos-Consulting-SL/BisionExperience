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
use Illuminate\Support\Facades\Validator;
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
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::exists('conductor', 'email'), // Solo correos en tabla conductor.
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        //Si el usuario ya existe en User lo recogemos.
        $user = User::where('email', strtolower($request->email))->first();

        //Creamos usuario.
        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
            'rol' => 'cliente' //Cuando un cliente se registra  se le coloca rol = cliente.
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('cliente.dashboard'));
    }
}
