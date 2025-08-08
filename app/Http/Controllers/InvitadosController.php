<?php

namespace App\Http\Controllers;

use App\Imports\InvitadosImport;
use App\Models\Conductor;
use App\Models\Evento;
use App\Models\EventoConductor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class InvitadosController extends Controller
{
    //FUNCION MUESTRA INVITADOS TABLA.
    public function index($id)
    {
        //Pasamos el id para buscar y recoger la informacion de cada evento.
        $evento = Evento::find($id);
        if (!$evento) {
            return redirect()->back()->with('error', 'Evento no encontrado');
        }
        //Paginacion de 50.
        $invitados = $evento->invitados()->paginate(50);
        //Me cuenta el total de invitados dentro de cada evento.
        $total = $evento->invitados()->count();

        //Contador de asistencia.
        $asisten = $evento->invitados()->where('asiste', 1)->count();
        $no_asiste = $evento->invitados()->where(function ($query) {
            $query->where('asiste', 0)->orWhereNull('asiste');
        })->count();
        //Pasamos en el compact todo lo que vamos a mostrar.
        return view('invitados.index', ['id' => $evento], compact('evento', 'invitados', 'total', 'asisten', 'no_asiste'));
    }

    //FUNCION MUESTRA FORMULARIO CREAR.
    public function create($id)
    {
        //Muestra formulario de creacion para cada evento (crea un nuevo invitado).
        $evento = Evento::find($id);
        return view('invitados.create', compact('evento'));
    }

    //FUNCION PARA CREAR INVITADO
    public function store(Request $request, $id)
    {
        if (Conductor::where('dni', $request->dni)->exists()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Invitado con DNI ' . $request->dni . ' ya existe.');
        }

        // VALIDACIÓN
        $request->validate([
            'cif' => 'nullable',
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
            'telefono' => 'nullable',
            'empresa' => 'nullable',
            'vehiculo_prop' => 'nullable',
            'vehiculo_emp' => 'nullable',
            'intolerancia' => 'nullable',
            'preferencia' => 'nullable',
            'carnet_caducidad' => 'required|date',
            'kam' => 'nullable',
            'dni' => 'nullable|unique:conductor,dni',
            'etiqueta' => 'required_if:vehiculo_prop,si',
            'proteccion_datos' => 'accepted'
        ]);

        try {
            $evento = Evento::findOrFail($id);
            $invitado = new Conductor();

            $invitado->fill($request->except('carnet'));

            // Guardar carnet si se envía archivo
            if ($request->hasFile('carnet')) {
                $invitado->carnet = $request->file('carnet')->store('carnets', 'public');
            }

            // Si tiene coche propio o empresa, guardar etiqueta
            if ($request->vehiculo_prop === 'si' || $request->vehiculo_emp === 'si') {
                $invitado->etiqueta = $request->etiqueta;
            }

            $invitado->save();

            // Asociar al evento
            EventoConductor::create([
                'evento_id' => $evento->id,
                'conductor_id' => $invitado->id,
            ]);

            return redirect()->route('invitados.index', $evento->id)->with('success', 'Invitado creado con éxito.');
        } catch (\Exception $e) {
            return redirect()->route('invitados.index', $id)->with('error', 'Error al crear invitado.');
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
    public function edit(int $id)
    {
        //Busca el invitado dentro de cada evento creado.
        $invitados = Conductor::find($id);
        //Obtiene el evento asociado en la tabla evento conductor.
        $eventoConductor = EventoConductor::where('conductor_id', $invitados->id)->first();
        //Obtiene el id del evento si existe.
        $eventoId = $eventoConductor ? $eventoConductor->evento_id : null;

        if (!$invitados) {
            return redirect()->route('invitados.index')->with('error', 'No se encontro el invitado');
        }
        return view('invitados.edit', compact('invitados', 'eventoId'));
    }

    //FUNCION PARA EDITAR
    public function update(Request $request, int $id)
    {
        //Busca el invitado mediante su id
        $invitados = Conductor::find($id);

        if (!$invitados) {
            return redirect()->route('invitados.index', ['id' => $id])->with('error', 'No se encontró el invitado.');
        }

        //Asocia el id del conductor al evento y lo recoge.
        $eventoConductor = EventoConductor::where('conductor_id', $invitados->id)->first();

        if (!$eventoConductor) {
            return redirect()->route('invitados.index', ['id' => $id])->with('error', 'No se encontró el evento asociado al invitado.');
        }

        $eventoId = $eventoConductor->evento_id;

        //Validacion
        $request->validate([
            'cif' => 'nullable',
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required',
            'telefono' => 'nullable',
            'empresa' => 'nullable',
            'vehiculo_prop' => 'nullable',
            'vehiculo_emp' => 'nullable',
            'intolerancia' => 'nullable',
            'preferencia' => 'nullable',
            'carnet_caducidad' => 'required',
            'kam' => 'nullable',
            'dni' => 'nullable',
            'etiqueta' => 'required_if:vehiculo_prop,si',
        ]);

        try {
            $invitados->cif = $request->cif;
            $invitados->nombre = $request->nombre;
            $invitados->apellido = $request->apellido;
            $invitados->email = $request->email;
            $invitados->telefono = $request->telefono;
            $invitados->empresa = $request->empresa;
            $invitados->vehiculo_prop = $request->vehiculo_prop;
            $invitados->vehiculo_emp = $request->vehiculo_emp;
            $invitados->intolerancia = $request->intolerancia;
            $invitados->preferencia = $request->preferencia;
            $invitados->carnet_caducidad = $request->carnet_caducidad;
            $invitados->kam = $request->kam;
            $invitados->dni = $request->dni;

            //Guarda el valor de uno o del otro por si esta marcado.
            if ($request->vehiculo_prop === 'si' || $request->vehiculo_emp === 'si') {
                $invitados->etiqueta = $request->etiqueta;
            }

            $invitados->save();

            return redirect()->route('invitados.index', ['id' => $eventoId])->with('success', 'Invitado actualizado con éxito.');
        } catch (Exception $e) {
            return redirect()->route('invitados.index', ['id' => $eventoId])->with('error', 'Error al actualizar el invitado.');
        }
    }

    //FUNCION PARA FILTRAR BUSQUEDA
    public function show(Request $request, int $id)
    {
        //Recoge informacion del input
        $query = $request->input('buscador');
        //Buscamos y recogemos datos del evento.
        $evento = Evento::find($id);

        $invitados = $evento->invitados()->where(function ($q) use ($query) {
            $q->where('empresa', 'like', "%{$query}%")
                ->orWhere('cif', 'like', "%{$query}%")
                ->orWhere('nombre', 'like', "%{$query}%")
                ->orWhere('apellido', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('telefono', 'like', "%{$query}%")
                ->orWhere('kam', 'like', "%{$query}%");
        })->paginate(6) //Paginacion de 6 por pantalla (busqueda)
            //Mantiene la paginacion aun realizando el filtrado.
            ->appends(['buscador' => $query]);

        $total = $invitados->total(); //Total de invitados por evento.
        return view('invitados.index', compact('invitados', 'total', 'evento'));
    }

    //FUNCION PARA MARCAR LA ASISTENCIA
    public function actualizarAsistencia(Request $request, $id)
    {
        //Busca el invitado por su id y recoge la asitencia.
        $invitado = Conductor::find($id);
        //Si se marca estara en la posicion 1 (true) o 0 (false)
        $invitado->asiste = $request->asiste ? 1 : 0;
        //Guarda el invitado y su asistencia.
        $invitado->save();

        return response()->json([
            'success'  => true
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
                ->with('success', 'Invitados importados correctamente');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'Hubo un error al importar los invitados: ' . $e->getMessage()]);
        }
    }
}
