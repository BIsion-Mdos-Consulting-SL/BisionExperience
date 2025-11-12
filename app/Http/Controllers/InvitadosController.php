<?php

namespace App\Http\Controllers;

use App\Imports\InvitadosImport;
use App\Models\Conductor;
use App\Models\Evento;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class InvitadosController extends Controller
{
    //FUNCION MUESTRA INVITADOS TABLA.
    public function index(Evento $evento)
    {
        $invitados = $evento->invitados()->paginate(50);

        $rel   = $evento->invitados();
        $pivot = $rel->getTable(); //Recoge la informacion de la tabla junto a su relacion.

        $total   = $rel->count();

        // Fuera de closure podrías usar wherePivot, pero por consistencia usamos $pivot.col
        $asisten = $evento->invitados()
            ->where("$pivot.asiste", 1)
            ->count();

        $no_asiste = $evento->invitados()
            ->where(function ($q) use ($pivot) {
                $q->where("$pivot.asiste", 0)
                    ->orWhereNull("$pivot.asiste"); // por si viene NULL
            })
            ->count();

        return view('invitados.index', compact('evento', 'invitados', 'total', 'asisten', 'no_asiste'));
    }

    //FUNCION MUESTRA FORMULARIO CREAR.
    public function create(Evento $evento)
    {
        return view('invitados.create', compact('evento'));
    }

    //FUNCION PARA CREAR INVITADO
    public function store(Request $request, $id)
    {
        // --- Normaliza DNI: quita espacios y pasa a MAYÚSCULAS ---
        $dniNormalizado = $request->filled('dni')
            ? strtoupper(preg_replace('/\s+/', '', $request->dni))
            : null;

        // Para que la validación y el guardado usen el normalizado
        $request->merge(['dni' => $dniNormalizado]);

        $request->validate([
            'cif' => 'nullable',
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
            'telefono' => 'nullable',
            'empresa' => 'nullable',
            'vehiculo_prop' => 'nullable|in:si,no',
            'vehiculo_emp' => 'nullable|in:si,no',
            'intolerancia' => 'nullable',
            'preferencia' => 'nullable',
            'carnet_caducidad' => 'required|date',
            'kam' => 'nullable',
            'dni' => [
                'required',
                'string',
                'max:20',
                // ÚNICO dentro del mismo evento en la tabla pivot
                Rule::unique('evento_conductor', 'dni')
                    ->where(fn($q) => $q->where('evento_id', $id)),
            ],
            'etiqueta'   => 'nullable|required_if:vehiculo_prop,si',
            'etiqueta_2' => 'nullable|required_if:vehiculo_emp,si',
            'proteccion_datos' => 'accepted'
        ]);

        try {
            $evento = Evento::findOrFail($id);

            // (Opcional) Comprobación rápida con el normalizado, por si acaso
            if ($dniNormalizado) {
                $pivot = $evento->invitados()->getTable();
                $yaEnEvento = $evento->invitados()
                    ->wherePivot('dni', $dniNormalizado) // usar el DNI normalizado
                    ->exists();

                if ($yaEnEvento) {
                    return back()->withInput()->toast('error', 'Este DNI ya existe en este evento.');
                }
            }

            // 1) Conductor base (reusar por DNI normalizado o crear)
            $datosBase = $request->only([
                'cif',
                'nombre',
                'apellido',
                'email',
                'telefono',
                'empresa',
                'dni',
                'carnet_caducidad',
                'vehiculo_prop',
                'vehiculo_emp',
                'intolerancia',
                'preferencia',
                'kam',
                'etiqueta',
                'etiqueta_2',
                'proteccion_datos'
            ]);

            $datosBase['dni'] = $dniNormalizado;

            if ($request->hasFile('carnet')) {
                $datosBase['carnet'] = $request->file('carnet')->store('carnets', 'public');
            }

            $datosBase['etiqueta'] = $request->vehiculo_prop === 'si' ? $request->etiqueta : null;

            $datosBase['etiqueta_2'] = $request->vehiculo_emp === 'si' ? $request->etiqueta_2 : null;

            $invitado = $dniNormalizado
                ? Conductor::firstOrNew(['dni' => $dniNormalizado])
                : new Conductor();

            $invitado->fill($datosBase)->save();

            // 2) Snapshot COMPLETO al pivot (usa el DNI normalizado)
            $pivotData = [
                'cif'               => $request->cif,
                'nombre'            => $request->nombre,
                'apellido'          => $request->apellido,
                'email'             => $request->email,
                'telefono'          => $request->telefono,
                'empresa'           => $request->empresa,
                'vehiculo_prop'     => $request->vehiculo_prop,
                'vehiculo_emp'      => $request->vehiculo_emp,
                'intolerancia'      => $request->intolerancia,
                'preferencia'       => $request->preferencia,
                'carnet'            => $request->hasFile('carnet') ? $datosBase['carnet'] : null,
                'etiqueta'         => $request->vehiculo_prop === 'si' ? $request->etiqueta   : null,
                'etiqueta_2'       => $request->vehiculo_emp  === 'si' ? $request->etiqueta_2 : null,
                'kam'               => $request->kam,
                'asiste'            => $request->boolean('asiste'),
                'dni'               => $dniNormalizado, // <- aquí el normalizado
                'proteccion_datos'  => $request->boolean('proteccion_datos'),
                'carnet_caducidad'  => $request->carnet_caducidad,
            ];

            // 3) Relaciona sin duplicar y setea el pivot
            $evento->invitados()->sync([
                $invitado->id => $pivotData
            ], false);

            return redirect()->route('invitados.index', $evento->id)
                ->toast('success', 'Invitado creado con exito.');
        } catch (\Exception $e) {
            return redirect()->route('invitados.index', $id)
                ->toast('error', 'Error al crear invitado.');
        }
    }

    //FUNCION PARA ELIMINAR INVITADO.
    public function delete(int $id)
    {
        //Busca invitado por id.
        $invitados = Conductor::find($id);

        //Recoge la respuesta en formato JSON (SweetAlert)
        if (!$invitados) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar invitado'
            ], 404);
        } else {
            $invitados->delete();
            return response()->json([
                'success' => true,
                'message' => 'Invitado eliminado'
            ], 200);
        }
    }

    //FUNCION PARA MOSTRAR EL FORMULARIO DE EDITAR.
    public function edit(Evento $evento, Conductor $invitado)
    {
        $invitado = $evento->invitados()
            ->withPivot([
                'cif',
                'nombre',
                'apellido',
                'email',
                'telefono',
                'empresa',
                'vehiculo_prop',
                'vehiculo_emp',
                'intolerancia',
                'preferencia',
                'carnet',
                'etiqueta',
                'kam',
                'asiste',
                'dni',
                'proteccion_datos',
                'carnet_caducidad',
                'confirmado',
                'etiqueta_2',
                'token'
            ])
            ->whereKey($invitado->getKey())
            ->firstOrFail();

        return view('invitados.edit', [
            'eventoId' => $evento->id,
            'invitados' => $invitado,   // mantengo tu nombre de variable para la vista
            'pivot'    => $invitado->pivot,
        ]);
    }

    /**
     * Actualiza un invitado dentro de un evento específico.
     */
    public function update(Request $request, Evento $evento, Conductor $invitado)
    {
        // 1) Validación
        $request->validate([
            'cif'               => 'nullable|string|max:50',
            'nombre'            => 'required|string|max:255',
            'apellido'          => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'telefono'          => 'nullable|string|max:50',
            'empresa'           => 'nullable|string|max:255',
            'vehiculo_prop'     => 'nullable|in:si,no',
            'vehiculo_emp'      => 'nullable|in:si,no',
            'intolerancia'      => 'nullable|string|max:255',
            'preferencia'       => 'nullable|string|max:255',
            'carnet_caducidad'  => 'required|date',
            'kam'               => 'nullable|string|max:255',
            'dni'               => 'nullable|string|max:20',
            'etiqueta'          => 'required_if:vehiculo_prop,si|required_if:vehiculo_emp,si',
            'proteccion_datos'  => 'nullable|boolean',
            'asiste'            => 'nullable|boolean',
            'carnet'            => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        try {
            // 2) Verifica que el invitado pertenece a este evento (y carga el pivot correcto)
            $invitadoEvento = $evento->invitados()
                ->whereKey($invitado->getKey())
                ->firstOrFail();

            // 3) Alias de relación y nombre de tabla pivot (evita escribir literal)
            $rel   = $evento->invitados();
            $pivot = $rel->getTable(); // debería ser "evento_conductor"

            // 4) Evitar duplicado de DNI dentro del mismo evento (si viene DNI)
            if ($request->filled('dni')) {
                $dniDuplicado = $evento->invitados()
                    ->wherePivot('dni', $request->dni)
                    ->where("$pivot.conductor_id", '<>', $invitado->id) // apunta al pivot, no al modelo
                    ->exists();

                if ($dniDuplicado) {
                    return redirect()
                        ->route('invitados.index', $evento)
                        ->toast('error', 'Ya existe este DNI en este evento.');
                }
            }

            // 5) Actualiza datos base del modelo Conductor (fuera del pivot)
            $invitado->fill($request->only([
                'cif',
                'nombre',
                'apellido',
                'email',
                'telefono',
                'empresa',
                'dni',
                'carnet_caducidad',
                'vehiculo_prop',
                'vehiculo_emp',
                'intolerancia',
                'preferencia',
                'kam',
                'etiqueta',
                'etiqueta_2',
                'proteccion_datos'
            ]))->save();

            // 6) Prepara payload de actualización del PIVOT
            $pivotData = [
                'cif'               => $request->cif,
                'nombre'            => $request->nombre,
                'apellido'          => $request->apellido,
                'email'             => $request->email,
                'telefono'          => $request->telefono,
                'empresa'           => $request->empresa,
                'vehiculo_prop'     => $request->vehiculo_prop,
                'vehiculo_emp'      => $request->vehiculo_emp,
                'intolerancia'      => $request->intolerancia,
                'preferencia'       => $request->preferencia,
                'etiqueta'         => ($request->vehiculo_prop === 'si') ? $request->etiqueta   : null,
                'etiqueta_2'       => ($request->vehiculo_emp  === 'si') ? $request->etiqueta_2 : null,
                'kam'               => $request->kam,
                'asiste'            => $request->boolean('asiste'),           // true/false -> 1/0 al guardar
                'dni'               => $request->dni,
                'proteccion_datos'  => $request->boolean('proteccion_datos'), // true/false -> 1/0
                'carnet_caducidad'  => $request->carnet_caducidad,
            ];

            // 7) Si suben carnet, súbelo y guarda la ruta en el pivot
            if ($request->hasFile('carnet')) {
                $pivotData['carnet'] = $request->file('carnet')->store('carnets', 'public');
            }

            // 8) Actualiza la fila del PIVOT para este invitado en este evento
            $evento->invitados()->updateExistingPivot($invitado->id, $pivotData);

            // 9) Vuelve al listado con éxito
            return redirect()
                ->route('invitados.index', $evento)
                ->toast('success', 'Invitado actualizado con exito.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar invitado', [
                'error'       => $e->getMessage(),
                'invitado_id' => $invitado->id ?? null,
                'evento_id'   => $evento->id ?? null,
            ]);

            return redirect()
                ->route('invitados.index', $evento)
                ->toast('error', 'Error al actualizar el invitado.');
        }
    }

    //FUNCION PARA FILTRAR BUSQUEDA
    public function show(Request $request, Evento $evento)
    {
        $query = trim((string) $request->input('buscador', ''));

        $rel   = $evento->invitados();
        $pivot = $rel->getTable(); //Tabla pivot.
        $related = $rel->getRelated()->getTable();//Coge datos de la relacion y la tabla invitados.

        $filtro = function ($q) use ($pivot, $related, $query) {
            $q->where(function ($qq) use ($pivot, $related, $query) {
                //Columnas en la TABLA PIVOT ---
                $qq->where("$pivot.empresa",   'like', "%{$query}%")
                    ->orWhere("$pivot.cif",      'like', "%{$query}%")
                    ->orWhere("$pivot.nombre",   'like', "%{$query}%")
                    ->orWhere("$pivot.apellido", 'like', "%{$query}%")
                    ->orWhere("$pivot.email",    'like', "%{$query}%")
                    ->orWhere("$pivot.telefono", 'like', "%{$query}%")
                    ->orWhere("$pivot.kam",      'like', "%{$query}%");

                //Columnas en la TABLA INVITADO ---
                $qq->orWhere("$related.empresa",     'like', "%{$query}%")
                    ->orWhere("$related.cif",  'like', "%{$query}%")
                    ->orWhere("$related.nombre",      'like', "%{$query}%")
                    ->orWhere("$related.apellido",   'like', "%{$query}%")
                    ->orWhere("$related.email",  'like', "%{$query}%")
                    ->orWhere("$related.telefono", 'like', "%{$query}%")
                    ->orWhere("$related.kam",      'like', "%{$query}%");
            });
        };

        $invitados = $rel
            ->where($filtro)
            ->paginate(6)
            ->appends(['buscador' => $query]);

        $total = $evento->invitados()->where($filtro)->count();

        $asisten = $evento->invitados()
            ->where($filtro)
            ->where("$pivot.asiste", 1)
            ->count();

        $no_asiste = $evento->invitados()
            ->where($filtro)
            ->where(function ($q) use ($pivot) {
                $q->where("$pivot.asiste", 0)
                    ->orWhereNull("$pivot.asiste");
            })
            ->count();

        return view('invitados.index', compact('invitados', 'total', 'evento', 'asisten', 'no_asiste'));
    }

    public function actualizarAsistencia(Request $request, Evento $evento, Conductor $invitado)
    {
        $evento->invitados()->updateExistingPivot($invitado->id, [
            'asiste' => $request->boolean('asiste') ? 1 : 0,
        ]);

        $rel   = $evento->invitados();
        $pivot = $rel->getTable(); // "evento_conductor"

        $total    = $rel->count();
        $asisten  = $evento->invitados()->where("$pivot.asiste", 1)->count();
        $no_asiste = $evento->invitados()
            ->where(function ($q) use ($pivot) {
                $q->where("$pivot.asiste", 0)
                    ->orWhereNull("$pivot.asiste");
            })
            ->count();

        return response()->json([
            'success' => true,
            'totals'  => compact('total', 'asisten', 'no_asiste'),
        ]);
    }

    //FUNCION PARA IMPORTAR EXCEL
    public function importarInvitados(Request $request, $id)
    {
        $evento = Evento::find($id);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new InvitadosImport($evento->id), $request->file('file'));

            return redirect()
                ->route('invitados.index', $evento->id)
                ->toast('success', 'Invitados importados correctamente');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Hubo un error al importar los invitados: ' . $e->getMessage()]);
        }
    }
}
