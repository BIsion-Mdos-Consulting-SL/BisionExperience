<?php

namespace App\Http\Controllers;

use App\Exports\CochExport;
use App\Imports\CochImport;
use App\Models\Coch;
use App\Models\Coche;
use App\Models\Evento;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CochesController extends Controller
{
    public function index($id)
    {
        //Busca el evento por el id para mostrarlo.
        $evento = Evento::find($id);
        if (!$evento) {
            return redirect()->back()->with('error', 'Coche no encontrado');
        }
        // Filtra los coches por evento_id
        $coches = Coch::where('evento_id', $evento->id)->paginate(10);
        //Realiza el conteo de los coches en la misma pagina.
        $total = $coches->count();

        //Despues de crear la relacion eloquent lo implementamos en el compact y le damos el conteo.
        $llaves = $coches->filter(fn($coche) => $coche->asiste == 1)->count();
        $no_llaves = $coches->filter(fn($coche) => $coche->asiste == 0 || is_null($coche->asiste))->count();

        return view('coches.index', compact('coches', 'evento', 'total', 'llaves', 'no_llaves'));
    }

    public function create($id)
    {
        //Busca el evento por el id y luego mostrara el formulario.
        $evento = Evento::find($id);
        return view('coches.create', compact('evento'));
    }

    public function store(Request $request, $evento_id)
    {
        // Validacion si ya existe esa matrícula
        if (Coch::where('matricula', $request->matricula)->exists()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'La matrícula ' . $request->matricula . ' ya existe. ');
        }

        $request->validate([
            'matricula' => 'required|unique:coches,matricula',
            'marca' => 'required',
            'modelo' => 'required',
            'version' => 'required',
            'kam' => 'required',
            'asiste' => 'nullable|in:0,1',
            'seguro' => 'required|in:0,1',
            'documento_seguro' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'foto_vehiculo' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ], [
            'matricula.unique' => 'La matrícula ya existe',
            'matricula.required' => 'La matrícula es obligatoria',
        ]);

        //Subida de archivos
        $documentoSeguroPath = null;
        $fotoVehiculoPath = null;

        if ($request->hasFile('documento_seguro')) {
            $documentoSeguroPath = $request->file('documento_seguro')->store('documentos_seguros', 'public');
        }

        if ($request->hasFile('foto_vehiculo')) {
            $fotoVehiculoPath = $request->file('foto_vehiculo')->store('fotos_vehiculos', 'public');
        }

        Coch::create([
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'version' => $request->version,
            'matricula' => $request->matricula,
            'kam' => $request->kam,
            'asiste' => $request->input('asiste', 0),
            'evento_id' => $evento_id,
            'seguro' => $request->input('seguro', 0),
            'documento_seguro' => $documentoSeguroPath,
            'foto_vehiculo' => $fotoVehiculoPath
        ]);

        return redirect()
            ->route('coches.index', $evento_id)
            ->with('success', 'Coche creado con éxito');
    }

    public function delete(int $id)
    {
        //Busca el coche creado por el id que tiene.
        $coches = Coch::find($id);

        //Manejamos con una condicion para un JSON para el sweetAlert , sino no elimina.
        if (!$coches) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar coche'
            ], 404);
        } else {
            $coches->delete();
            return response()->json([
                'success' => true,
                'message' => 'Coche eliminado'
            ], 200);
        }
    }

    public function edit(int $id)
    {
        //Busca el coche poor el id y muestra el formulario de editar.
        $coches = Coch::find($id);

        if (!$coches) {
            return redirect()->route('coches.index')->with('error', 'No se encontro el invitado');
        }
        //Maneja la relacion eloquent para que solo traiga y pinte los coches de ese evento.
        $evento = $coches->evento;
        return view('coches.edit', compact('coches', 'evento'));
    }

    public function update(Request $request, int $id)
    {
        $coche = Coch::find($id);

        if (!$coche) {
            return redirect()->route('coches.index')->with('error', 'No se encontró el coche');
        }

        $evento = $coche->evento; //Obtiene evento desde la relacion.

        $request->validate([
            'marca' => 'required',
            'modelo' => 'required',
            'version' => 'required',
            'matricula' => 'required',
            'kam' => 'required',
            'asiste' => 'nullable|in:0,1',
            'seguro' => 'required|in:0,1',
            'documento_seguro' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'foto_vehiculo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        try {
            $coche->marca = $request->marca;
            $coche->modelo = $request->modelo;
            $coche->version = $request->version;
            $coche->matricula = $request->matricula;
            $coche->kam = $request->kam;
            $coche->asiste = $request->input('asiste', 0);
            $coche->seguro = $request->input('seguro', 0);

            // Subir documento del seguro si hay nuevo
            if ($request->hasFile('documento_seguro')) {
                $documentoPath = $request->file('documento_seguro')->store('documentos_seguros', 'public');
                $coche->documento_seguro = $documentoPath;
            }

            // Subir nueva foto si hay
            if ($request->hasFile('foto_vehiculo')) {
                $fotoPath = $request->file('foto_vehiculo')->store('fotos_vehiculos', 'public');
                $coche->foto_vehiculo = $fotoPath;
            }

            $coche->save();
            //Realizamos la condicion ternaria para que no nos salga el error de null al actualizar (muy importante).
            return redirect()->route('coches.index', $evento ? $evento->id : null)->with('success', 'Coche actualizado correctamente');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el coche: ');
        }
    }

    public function actualizarAsistencia(Request $request, $id)
    {
        //Busca el coche por su id.
        $coche = Coch::find($id);
        //Si se marca el checkbox mediante el JS creado esto recoge la informacion , por si es true (boolean).
        $coche->asiste = $request->asiste ? 1 : 0;
        //Guarda y actualiza el registro.
        $coche->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function show(Request $request, $id)
    {
        //Busca el coche por su id.
        $evento = Evento::find($id);
        //Recoge el valor del input llamado buscador.
        $buscador = $request->input('buscador');

        $coches = Coch::where('evento_id', $evento->id)
            ->where(function ($query) use ($buscador) {
                $query->where('marca', 'like', '%' . $buscador . '%')
                    ->orWhere('modelo', 'like', '%' . $buscador . '%')
                    ->orWhere('version', 'like', '%' . $buscador . '%')
                    ->orWhere('matricula', 'like', '%' . $buscador . '%')
                    ->orWhere('kam', 'like', '%' . $buscador . '%');
            })
            ->paginate(6);

        //Realiza el conteo de los coches en la vista.
        $total = Coch::where('evento_id', $evento->id)->count();
        return view('coches.index', compact('coches', 'total', 'evento'));
    }

    public function exportarCoches($evento_id)
    {
        //Busca el evento por el id.
        $evento = Evento::find($evento_id);

        //Devuelve la decarga con el excel creado.
        return Excel::download(new CochExport($evento), 'coches_evento' . $evento_id . '.xlsx');
    }

    public function importarCoches(Request $request, $id)
    {
        $evento = Evento::find($id);

        try {
            $import = new CochImport($evento->id);
            Excel::import($import, $request->file('file'));

            $duplicados = $import->getDuplicados();

            return redirect()
                ->route('coches.show', $evento->id)
                ->with('success', 'Coches importados correctamente. Duplicados ignorados: ' . $duplicados);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Hubo un error al importar los coches: ' . $e->getMessage()]);
        }
    }
}
