<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Timing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TimingController extends Controller
{
    public function cargarDatos()
    {
        // Recomendado: relación + select de columnas necesarias
        $evento = Evento::query()
            ->latest('id')
            ->select(['id', 'nombre']) // ajusta columnas según tu tabla
            ->with(['timings' => function ($q) {
                $q->select(['id', 'evento_id', 'nombre', 'descripcion']); // asegúrate que estas columnas existan
            }])
            ->first();

        if (!$evento) {
            return response()->json([
                'error' => 'Sin evento.',
                'message' => 'No hay evento activo.'
            ], 404);
        }

        if ($evento->timings->isEmpty()) {
            return response()->json([
                'error' => 'Sin timing',
                'message' => 'No hay timing para el evento',
                'evento_id' => $evento->id
            ], 404);
        }

        return response()->json([
            'evento' => $evento,
            'timings' => $evento->timings,
        ], 200);
    }
}
