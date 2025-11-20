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

    /**
     * La función `store` gestiona el proceso de registro de usuarios, comprobando si un correo electrónico ya existe en la base de datos y creando un nuevo usuario si es necesario, luego iniciando sesión al usuario y redirigiéndolo al panel de control del cliente.
     
     * @param Request request La función `store` que proporcionaste parece gestionar el proceso de registro de usuarios en tu aplicación. Permíteme explicar el flujo de la función basándome en el código que compartiste:
     
     * @return RedirectResponse La función `store` devuelve un objeto `RedirectResponse`. Este redirige a la ruta 'cliente.dashboard' después de procesar la entrada del usuario y crear o actualizar un usuario en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        $conductorTable = (new Conductor)->getTable();
        $usersTable     = (new User)->getTable();

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::exists($conductorTable, 'email'),
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            //mensaje para cuando NO existe en conductor
            'email.exists' => 'El correo introducido no existe en la BD.',
        ]);

        // Normaliza el email
        $email = mb_strtolower($validated['email']);

        // ¿Ya existe un usuario con este email?
        $user = User::where('email', $email)->first();

        if ($user) {
            //CASO 1: ya habia usuario en users con este email

            // Si YA tiene contraseña, ya esta registrado
            if (!is_null($user->password)) {
                return back()
                    ->withErrors([
                        'email' => 'Este correo ya tiene una cuenta registrada. Inicia sesión en lugar de crear una nueva.',
                    ])
                    ->withInput($request->except('password', 'password_confirmation'));
            }

            // Si NO tenia contraseña → lo completamos como cliente
            $user->name     = $user->name ?? ($validated['name'] ?? null);
            $user->password = Hash::make($validated['password']);

            if (empty($user->rol)) {
                $user->rol = 'cliente';
            }

            $user->save();
        } else {
            // CASO 2: no existe en users → lo creamos normal
            $user = User::create([
                'name'     => $validated['name'] ?? null,
                'email'    => $email,
                'password' => Hash::make($validated['password']),
                'rol'      => 'cliente',
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('cliente.dashboard');
    }
}
