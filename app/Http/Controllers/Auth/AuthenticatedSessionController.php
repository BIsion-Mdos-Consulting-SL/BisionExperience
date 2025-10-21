<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Conductor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::user()->rol === 'admin') {
            return redirect()->intended(route('dashboard', absolute: true));
        } elseif (Auth::user()->rol === 'cliente') {
            return redirect()->intended(route('cliente.dashboard', absolute: true));
        }elseif(Auth::user()->rol === 'dealer'){
            return redirect()->intended(route('dealer.dashboard' , absolute:true));
        } else {
            return redirect("/");
        }

        //prueba
        //return redirect()->intended(route('dashboard', absolute: true));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }

    public function createAdmin()
    {
        // Si hay un usuario logueado y NO es admin → lo sacamos
        if (Auth::check() && Auth::user()->rol !== 'admin') {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        }

        // Si ya hay un admin logueado → directo al dashboard
        if (Auth::check() && Auth::user()->rol === 'admin') {
            return redirect()->route('dashboard');
        }
        
        // En cualquier otro caso → mostrar formulario de login
        return view('auth.login');
    }
}
