<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Evento;
use App\Models\Timing;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    //CONTROLADORES PARA PARADAS
    public function index(Evento $evento)
    {
        $paradas = $evento->paradas()->orderBy('orden')->get();
        $restaurante = $evento->restaurante;

        return view('cliente.ruta', compact('evento', 'paradas', 'restaurante'));
    }

    public function infoAuto()
    {
        $evento = Evento::latest('id')->firstOrFail();  // o tu lógica de "activo"
        return redirect()->route('cliente.eventos.show', $evento);
    }

    //CONTROLADORES PARA COCHES

    public function infoCoches(Evento $evento)
    {
        $coches = $evento->coches()->orderBy('id')->get();
        return view('cliente.info_coches', compact('evento', 'coches'));
    }

    public function infoCochesAuto()
    {
        $evento = Evento::latest('id')->firstOrFail(); // filtra por cliente si aplica
        return redirect()->route('cliente.eventos.coches', $evento);
    }

    //CONTROLADORES PARA RESTAURANTE

    public function infoRestaurante(Evento $evento)
    {
        $restaurante = $evento->restaurante;
        return view('cliente.info_restaurante', compact('evento', 'restaurante'));
    }

    public function principal()
    {
        // Busca un evento que tenga al menos imagen o video en banners
        $eventoId = Banner::where(function ($q) {
            $q->whereNotNull('imagen')->orWhereNotNull('video');
        })
            ->orderByDesc('id')   // el más reciente con media
            ->value('evento_id');

        // Si no hay ninguno, colección vacía
        $banners = $eventoId
            ? Banner::where('evento_id', $eventoId)
            ->where(function ($q) {
                $q->whereNotNull('imagen')->orWhereNotNull('video');
            })
            ->get(['imagen', 'video'])
            : collect();

        return view('principal', compact('banners'));
    }
}
