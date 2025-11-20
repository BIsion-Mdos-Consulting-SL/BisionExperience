<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class PreReservaController extends Controller
{
    public function index($id)
    {
        $evento = Evento::find($id);
        return view('coches.pre_reserva', compact('evento'));
    }
}
