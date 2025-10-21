<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PatrocinadoresController extends Controller
{
    public function cargarDatos(Request $request)
    {
        $eventoId = $request->integer('evento_id');

        $evento = Evento::query()
            ->select(['id', 'nombre'])
            ->when($eventoId, fn($q) => $q->where('id', $eventoId), fn($q) => $q->latest('id'))
            ->with([
                'banners:id,evento_id,empresa,enlace,video,imagen,frase,contacto,texto'
            ])
            ->first();

        if (!$evento) {
            return response()->json([
                'error' => 'Sin banner',
                'message' => 'No hay evento activo o el evento no existe.'
            ], 404);
        }

        $evento->banners->transform(function ($b) {
            // si no empieza por http(s), generamos /storage/...
            if ($b->imagen && !Str::startsWith($b->imagen, ['http://', 'https://', '//'])) {
                $path = ltrim($b->imagen, '/');
                $b->imagen = asset('storage/' . $path);
            }
            if ($b->video && !Str::startsWith($b->video, ['http://', 'https://', '//'])) {
                $path = ltrim($b->video, '/');
                $b->video = asset('storage/' . $path);
            }
            return $b;
        });

        return response()->json([
            'evento'  => $evento->only(['id', 'nombre', 'banners']),
            'timings' => $evento->timings,
        ], 200);
    }
}
