<?php

namespace App\Http\Controllers;

use App\Exports\InvitadosExport;
use App\Models\Evento;
use App\Models\EventosMarca;
use App\Models\Marca;
use App\Models\TipoEvento;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class EventosController extends Controller
{
    //MOSTRAR EVENTOS 
    public function index()
    {
        if (Auth::check()) {
            //Pagina todos los eventos a 3 por pagina.
            $eventos = Evento::paginate(3);
            //Cuenta el total de eventos que se tiene.
            $total = $eventos->count();
            //Recoge todos los tipos de evento de la BD.
            $tipo_evento = TipoEvento::all();
            //Recoge todas las marcas de ls BD.
            $marcas = Marca::all();

            // Obtiene las marcas para cada evento
            foreach ($eventos as $evento) {
                $selectString = $evento->marca;
                $selectIds = explode(',', $selectString); // Convierte la cadena en un array
                $evento->marcasSeleccionadas = Marca::whereIn('id', $selectIds)->get(); // Filtrar las marcas por idy las asocia.
            }
            
            //Retorna a la vista dashboard con los datos que se han recopilado.
            return view('dashboard', compact('eventos', 'tipo_evento', 'total', 'marcas'));
        } else {
            //Sino redirige al login.
            return view('./auth/login');
        }
    }


    //MOSTRAR VENTANA CREAR EVENTO
    public function create()
    {
        //Recoge las marcas de la BD tabla marcas.
        $marcas = Marca::all();
        //Recoge los tipos d eevento de la BD tabla tipo_evento.
        $tipo_evento = TipoEvento::all();

        return view('eventos.create', compact('marcas', 'tipo_evento'));
    }

    public function store(Request $request)
    {
        // VALIDAR CAMPOS
        $request->validate([
            'nombre' => 'required',
            'marca' => 'required|array',
            'fecha' => 'required',
            'hora' => 'required',
            'lugar_evento' => 'required',
            'tipo_evento' => 'required',
            'coste_evento' => 'required',
            'aforo' => 'required',
            'coste_unitario' => 'required',
            'enlace' => 'nullable',
            'documentacion' => 'nullable',
            'texto_invitacion' => 'required',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $evento = new Evento();
            $evento->nombre = $request->nombre;
            //Une el array de marcas colocandole una , entre medias.
            $evento->marca = implode(' , ', $request->marca);
            $evento->fecha = $request->fecha;
            $evento->hora = $request->hora;
            $evento->lugar_evento = $request->lugar_evento;
            $evento->tipo_evento = $request->tipo_evento;
            $evento->coste_evento = $request->coste_evento;
            $evento->aforo = $request->aforo;
            $evento->coste_unitario = $request->coste_unitario;
            $evento->texto_invitacion = $request->texto_invitacion;
            $evento->imagen = $request->imagen;

            //Verificacion de la imagen , para poder subirla.
            if ($request->hasFile('imagen')) {
                $path = $request->file('imagen')->store('images/eventos', 'public');
                $evento->imagen = $path;
            }

            //Verificacion de la documentacion , para poder subirla.
            if ($request->hasFile('documentacion')) {
                $path = $request->file('documentacion')->store('images/eventos_documentacion', 'public');
                $evento->documentacion = $path;
            }

            //Guarda nuevo evento.
            $evento->save();

            //Recoge con un foreach todas las marcas que tenemos en la BD para poder seleccionarla.
            foreach ($request->marca as $marca_id) {
                $eventos_marca = new EventosMarca();
                $eventos_marca->evento_id = $evento->id;
                $eventos_marca->marca_id = $marca_id;
                $eventos_marca->save();
            }

            return redirect()->route('eventos.index')->with('success', 'Evento creado con éxito');
        } catch (Exception $e) {
            return redirect()->route('eventos.index')->with('error', 'Error al crear evento');
        }
    }


    //ELIMINAR EVENTO
    public function delete(int $id)
    {
        //Busca el evento por el id.
        $evento = Evento::find($id);

        if (!$evento) {
            /**Estamos usando SweetAlert por lo cual no debemos confundirnos ya que este espera
             * un respuesta en formato JSON.
             */
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar'
            ], 404);
        } else {
            $evento->delete();
            return response()->json([
                'success' => true,
                'message' => 'Evento eliminado'
            ], 200);
        }
    }

    //MOSTRAR VENTANA EDITAR.
    public function edit(int $id)
    {
        $evento = Evento::with('marcas')->findOrFail($id); // Carga el evento con sus marcas asociadas
        $marcas = Marca::all(); //Obtiene todas las marcas.
        $tipo_evento = TipoEvento::all(); //Obtiene todos los tipos de eventos.

        if (!$evento) {
            return redirect()->route('eventos.index')->with('error', 'Evento no encontrado');
        } else {
            return view('eventos.edit', compact('evento', 'marcas', 'tipo_evento'));
        }
    }

    //ACTUALIZAR EVENTO
    public function update(Request $request, int $id)
    {
        //Busca evento por id.
        $evento = Evento::find($id);

        if (!$evento) {
            return redirect()->route('eventos.index')->with('error', 'No se encontró el evento');
        }

        // VALIDAR CAMPOS
        $request->validate([
            'nombre' => 'required',
            'marca' => 'required|array',
            'fecha' => 'required',
            'hora' => 'required',
            'lugar_evento' => 'required',
            'tipo_evento' => 'required',
            'coste_evento' => 'required',
            'aforo' => 'required',
            'coste_unitario' => 'required',
            'enlace' => 'nullable',
            'documentacion' => 'nullable',
            'texto_invitacion' => 'required',
        ]);

        try {
            // Actualizar los campos del evento
            $evento->nombre = $request->nombre;
            $evento->fecha = $request->fecha;
            $evento->hora = $request->hora;
            $evento->lugar_evento = $request->lugar_evento;
            $evento->tipo_evento = $request->tipo_evento;
            $evento->coste_evento = $request->coste_evento;
            $evento->aforo = $request->aforo;
            $evento->coste_unitario = $request->coste_unitario;
            $evento->texto_invitacion = $request->texto_invitacion;

            // Manejamos la documentación con la siguiente condicion.
            if ($request->hasFile('documentacion')) {
                // Elimina la documentación anterior si existe
                if ($evento->documentacion && Storage::exists('public/' . $evento->documentacion)) {
                    Storage::delete('public/' . $evento->documentacion);
                }

                // Guardar la nueva documentación en "images/eventos_documentacion"
                $pathDocumentacion = $request->file('documentacion')->store('images/eventos_documentacion', 'public');
                $evento->documentacion = $pathDocumentacion; // Guarda la ruta relativa en la base de datos
            }

            $evento->save();

            // Sincroniza las marcas seleccionadas
            $evento->marcas()->sync($request->marca);

            return redirect()->route('eventos.index')->with('success', 'Evento actualizado con éxito');
        } catch (Exception $e) {
            return redirect()->route('eventos.index')->with('error', 'Error al actualizar el evento');
        }
    }

    public function show(Request $request)
    {
        //Input llamado buscador.
        $query = $request->input('buscador');
        //Recogemos el array de marcas con una variable.
        $marcaId = $request->input('marca');

        //Carga los eventos con su marca relacionada.
        $eventos = Evento::with('marcas')
        //Si existe el buscador $query se realiza el filtrado.
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQ) use ($query) {
                    $subQ->where('nombre', 'like', "%{$query}%")
                        ->orWhere('tipo_evento', 'like', "%{$query}%")
                        ->orWhere('lugar_evento', 'like', "%{$query}%")
                        ->orWhereHas('marcas', function ($q3) use ($query) {
                            $q3->where('nombre', 'like', "%{$query}%");
                        });
                });
            })
            //Si se proporciona marcaId se filtran los eventos con esa marca.
            ->when($marcaId, function ($q) use ($marcaId) {
                $q->whereHas('marcas', function ($q2) use ($marcaId) {
                    $q2->where('id', $marcaId);
                });
            })
            //Paginacion  de 3 maximo en pantalla.
            ->paginate(3)
            //Mantiene filtros en la paginacion.
            ->appends(['buscador' => $query, 'marca' => $marcaId]);
        //Total de eventos.
        $total = $eventos->total();

        return view('dashboard', compact('eventos', 'total'));
    }


    //FUNCION PARA FILTRAR FECHA
    public function filtrarFecha(Request $request)
    {
        //Input de fecha inicio.
        $fecha_inicio = $request->fecha_inicio;
        //Input de fecha fin.
        $fecha_fin = $request->fecha_fin;

        if ($fecha_inicio && $fecha_fin) {
            //Filtrar los eventos por la fecha inicio y fecha fin.
            $eventos = Evento::where('fecha', '>=', $fecha_inicio)
                ->whereDate('fecha', '<=', $fecha_fin)
                //Paginacion total 3.
                ->paginate(3)
                //Mantiene filtro en la paginacion.
                ->appends(['fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin]);

            //Cuenta el total de eventos.
            $total = $eventos->count();
        } else {
            //Mantiene paginacion.
            $eventos = Evento::paginate(3);
            //Mantiene numero de eventos.
            $total = $eventos->count();
        }

        return view('dashboard', compact('eventos', 'total'));
    }

    public function exportarInvitados($evento_id)
    {
        //Busca el evento por el id.
        $evento = Evento::find($evento_id);
        //Devuelve un enlace de descarga en formato Excel para poder ver los invitados de ese evento.
        return Excel::download(new InvitadosExport($evento), 'invitados_evento_' . $evento_id . '.xlsx');
    }
}
