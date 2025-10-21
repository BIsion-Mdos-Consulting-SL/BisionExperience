<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Coch;
use App\Models\Evento;
use App\Models\Parada;
use App\Models\Restaurante;
use App\Models\Timing;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AjustesController extends Controller
{
    public function index(Request $request, Evento $evento)
    {
        $evento->load('restaurante');
        $coches = $evento->coches()->orderBy('marca')->get();
        $restaurante = $evento->restaurante;
        $paradas = $evento->paradas()->orderBy('orden')->get();

        $banners = Banner::where('evento_id', $evento->id)
            ->orderBy('empresa')->get();

        $timings = $evento->timings()->latest()->get();     // colección para el <select>
        $timing  = $timings->firstWhere('id', $request->input('timing_id'))
            ?? $timings->first();

        $banner = $banners->firstWhere('id', request('banner_id'));

        /**Consulta para recoger la empresa. */
        $empresas = DB::table('conductor as c')
            ->join('evento_conductor as ec', 'ec.conductor_id', '=', 'c.id')
            ->where('ec.evento_id', $evento->id)
            ->select('c.empresa')
            ->distinct()
            ->orderBy('c.empresa')
            ->pluck('c.empresa')
            ->filter(fn($empresa) => !empty($empresa)) //Elimina null y cadenas vacias por si no hay datos en la tabla invitados.
            ->toArray();

        $parada = null;
        if ($request->filled('parada_id')) {
            $parada = $evento->paradas()->whereKey($request->parada_id)->first();
        }

        return view('admin.ajustes', compact('evento', 'coches', 'restaurante', 'paradas', 'parada', 'banners', 'banner', 'empresas', 'timing', 'timings'));
    }

    /***CREA PARADAS 1ER BUTTON */
    public function storeParadas(Request $request, Evento $evento)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1800',
            'enlace'      => 'required|string|max:1028',
            'orden'       => 'required|integer|min:1',
            'conductor'   => 'nullable',
        ]);

        if ($evento->paradas()->where('orden', $request->orden)->exists()) {
            return back()
                ->withErrors(['orden' => 'Parada Nº ' . $request->orden . ' ya existe.'], 'parada')
                ->withInput()
                ->with('showModal', 'modalParada');
        }

        $evento->paradas()->create($data);

        return back()->toast('success', 'Parada creada exitosamente');
    }

    /***EDITAR PARADAS 1ER BUTTON - 2DO MODAL */
    public function updateParadas(Request $request, Evento $evento, Parada $parada)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1800',
            'enlace'      => 'required|string|max:1028',
            'orden'       => 'required|integer|min:1',
            'conductor'   => 'nullable',
        ]);

        try {
            $parada->nombre = $request->nombre;
            $parada->descripcion = $request->descripcion;
            $parada->enlace = $request->enlace;
            $parada->orden = $request->orden;

            $parada->save();

            return redirect()->route('admin.ajustes', $evento)->toast('success', 'Parada actualizada correctamente');
        } catch (Exception $e) {
            return redirect()->route('admin.ajustes', $evento)->toast('error', 'Error al actualizar parada');
        }
    }

    /**ELIMINAR PARADAS */
    public function deleteParadas(Evento $evento, $id)
    {
        $parada = Parada::find($id);

        if (!$parada) {
            return redirect()
                ->route('admin.ajustes', ['evento' => $evento->id])
                ->toast('error', 'La parada no existe');
        }

        $parada->delete();

        return redirect()
            ->route('admin.ajustes', ['evento' => $evento->id])
            ->toast('success', 'Parada eliminada');
    }


    /***EDITA COCHES 2DO BUTTON */
    public function editCoches(Evento $evento)
    {
        $coches = $evento->coches()->orderBy('marca')->get();
        return view('admin.ajustes', compact('evento', 'coches'));
    }

    /**EDITA LOS COCHES */
    public function updateCoches(Request $request, Evento $evento, Coch $coche)
    {

        $request->validate([
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'foto_vehiculo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        try {
            $coche->marca = $request->marca;
            $coche->modelo = $request->modelo;
            $coche->version = $request->version;

            // Subir nueva foto si hay
            if ($request->hasFile('foto_vehiculo')) {
                $fotoPath = $request->file('foto_vehiculo')->store('fotos_vehiculos', 'public');
                $coche->foto_vehiculo = $fotoPath;
            }

            $coche->save();
            return redirect()->route('admin.ajustes', $evento)->toast('success', 'Coche actualizado correctamente');
        } catch (Exception $e) {
            return redirect()->route('admin.ajustes', $evento)->toast('error', 'Error al actualizar coche');
        }
    }

    /***ELIMINAR COCHE */
    public function deleteCoches(Evento $evento, $id)
    {
        $coches = Coch::find($id);

        if (!$coches) {
            return redirect()->route('admin.ajustes', ['evento' => $evento->id])
                ->toast('error', 'El coche no existe');
        }

        $coches->delete();

        return redirect()
            ->route('admin.ajustes', ['evento' => $evento->id])
            ->toast('success', 'Coche eliminado');
    }

    /***CREA RESTAURANTE Y EDITA 3ER BUTTON */
    public function storeRestaurante(Request $request, Evento $evento)
    {
        if ($evento->restaurante()->exists()) {
            return back()->toast('error', 'Este evento ya tiene un restaurante.');
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:2800',
            'enlace' => 'required|string|max:2800',
            'foto_restaurante' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        if ($request->hasFile('foto_restaurante')) {
            $data['foto_restaurante'] = $request->file('foto_restaurante')
                ->store('fotos_restaurante', 'public');
        }

        $evento->restaurante()->create($data);

        return back()->toast('success', 'Restaurante creado exitosamente');
    }

    public function updateRestaurante(Request $request, Evento $evento, Restaurante $restaurante)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:2800',
            'enlace' => 'required|string|max:2800',
            'foto_restaurante' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        try {
            $restaurante->nombre = $request->nombre;
            $restaurante->descripcion = $request->descripcion;
            $restaurante->enlace = $request->enlace;

            // Subir nueva foto si hay
            if ($request->hasFile('foto_restaurante')) {
                $fotoPath = $request->file('foto_restaurante')->store('fotos_restaurante', 'public');
                $restaurante->foto_restaurante = $fotoPath;
            }

            $restaurante->save();
            return redirect()->route('admin.ajustes', $evento)->toast('success', 'Restaurante actualizado correctamente');
        } catch (Exception $e) {
            return redirect()->route('admin.ajustes', $evento)->toast('error', 'Error al actualizar evento');
        }
    }

    public function deleteRestaurante(Evento $evento, $id)
    {
        $restaurante = Restaurante::find($id);

        if (!$restaurante) {
            return redirect()->route('admin.ajustes', ['evento' => $evento->id])
                ->toast('error', 'El restaurante no existe');
        }

        $restaurante->delete();

        return redirect()->route('admin.ajustes', ['evento' => $evento->id])
            ->toast('success', 'Restaurante eliminado');
    }

    /**CREAMOS EL BANNER*/
    public function storeBanner(Request $request, Evento $evento)
    {
        $data = $request->validate([
            'evento_id' => 'required|integer|exists:evento,id',
            'empresa' => [
                'required',
                'string',
                'max:45',
                //Evita duplicados 
                Rule::unique('banner')->where(
                    fn($q) =>
                    $q->where('evento_id', $evento->id)
                )
            ],
            'enlace' => 'nullable|url|max:255',
            'video'  => 'nullable|file|mimetypes:video/mp4,video/webm,video/ogg|max:262144',
            'imagen' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'frase' => 'nullable|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'texto' => 'nullable|string|max:255'
        ]);

        //Subimos imagen
        $data['imagen'] = $request->file('imagen')->store('banners', 'public');
        //Subimos video
        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')->store('banners/videos', 'public');
        }

        $data['evento_id'] = $evento->id;

        Banner::create($data);
        return back()->toast('success', 'Banner creado');
    }

    /** ACTUALIZAMOS EL BANNER */
    public function updateBanner(Request $request, Evento $evento, Banner $banner)
    {
        // Asegura que el banner pertenece al evento
        if ((int)$banner->evento_id !== (int)$evento->id) {
            abort(404);
        }

        $data = $request->validate([
            'empresa' => [
                'sometimes',
                'string',
                'max:45',
                Rule::unique('banner')
                    ->ignore($banner->id)
                    ->where(fn($q) => $q->where('evento_id', $evento->id)),
            ],
            'enlace' => ['nullable', 'url', 'max:255'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'video'  => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/ogg', 'max:262144'],
            'frase' => ['nullable' , 'string' , 'max:255'],
            'contacto' => ['nullable' , 'string' , 'max:255'],
            'texto' => ['nullable' , 'string' , 'max:255'],
        ]);

        // Imagen (solo si suben una nueva)
        if ($request->hasFile('imagen')) {
            if ($banner->imagen && Storage::disk('public')->exists($banner->imagen)) {
                Storage::disk('public')->delete($banner->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('banners', 'public');
        }

        // Video (solo si suben uno nuevo)
        if ($request->hasFile('video')) {
            if ($banner->video && Storage::disk('public')->exists($banner->video)) {
                Storage::disk('public')->delete($banner->video);
            }
            $data['video'] = $request->file('video')->store('banners/videos', 'public');
        }

        $banner->update($data);

        return back()->toast('success', 'Banner actualizado');
    }

    public function deleteBanner(Evento $evento, $id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return redirect()->route('admin.ajustes', ['evento' => $evento->id])
                ->toast('error', 'El banner no existe');
        }

        $banner->delete();

        return redirect()->route('admin.ajustes', ['evento' => $evento->id])
            ->toast('success', 'Banner eliminado');
    }


    /**CREAMOS EL TIMING */
    public function storeTiming(Request $request, Evento $evento)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string'
        ]);

        $data['evento_id'] = $evento->id;

        Timing::create($data);

        return back()->toast('success', 'Timing creado');
    }

    public function editTiming(Evento $evento)
    {
        $timings = $evento->timings()->get();
        return view('admin.ajustes', compact('evento', 'timings'));
    }

    public function updateTiming(Request $request, Evento $evento, Timing $timing)
    {

        // Asegura que el banner pertenece al evento
        if ((int)$timing->evento_id !== (int)$evento->id) {
            abort(404);
        }

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string'
        ]);

        $timing->update($data);

        return back()->toast('success', 'Timing actualizado');
    }

    public function deleteTiming(Evento $evento, $id)
    {
        $timing = Timing::find($id);

        if (!$timing) {
            return redirect()->route('admin.ajustes', ['evento' => $evento->id])
                ->toast('error', 'Timing no encontrado');
        }

        $timing->delete();

        return redirect()->route('admin.ajustes', ['evento' =>  $evento->id])
            ->toast('success', 'Timing eliminado');
    }
}
