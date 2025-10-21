<?php

namespace App\Http\Controllers;

use App\Mail\ClienteRegistrado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ConfirmacionEventoMail;
use App\Models\Conductor;
use App\Models\Evento;

class EventoConductorController extends Controller
{
    /**
     * La función `enviarEmail` genera un token, actualiza un registro en la base de datos, envía un correo electrónico
     * a un conductor con un enlace de confirmación y devuelve una vista con los detalles del evento.
     * 
     * @param evento_id El parámetro `evento_id` de la función `enviarEmail` representa el ID del
     * evento para el que se desea enviar una confirmación por correo electrónico a un conductor específico (conductor).
     * Esta función genera un token único, lo actualiza en la base de datos para el evento y conductor especificados, y recupera el conductor.
     * @param conductor_id El parámetro `conductor_id` de la función `enviarEmail` representa el
     * identificador único del conductor al que se enviará el correo electrónico. Se utiliza para recuperar la información del conductor de la base de datos y enviarle un correo electrónico de confirmación sobre un
     * evento específico. * 
     * @return La función `enviarEmail` devuelve una vista llamada 'emails.mensaje' con los datos del evento
     * que se le pasaron. Esta vista probablemente contiene un mensaje o una plantilla relacionada con el evento que se confirmó por correo electrónico.
     */
    public function enviarEmail($evento_id, $conductor_id)
    {
        //Generamos el token.
        $token = Str::random(32);

        DB::table('evento_conductor')
            ->where('evento_id', $evento_id)
            ->where('conductor_id', $conductor_id)
            ->update(['token' => $token]); //Guarda token en la tabla.

        // Busca el conductor con el modelo Conductor
        $conductor = Conductor::find($conductor_id);

        if (!$conductor || !$conductor->email) {
            return response()->json(['error' => 'Conductor no encontrado o sin email'], 404);
        }

        $url = url('/evento-confirmacion/' . $token); //Se crea url.

        //Manda email de confirmacion.
        $evento = Evento::find($evento_id); //Pasar el evento_id que se coghe por parametro para ver boton volver.
        Mail::to($conductor->email)->send(new ConfirmacionEventoMail($url, $evento)); // Pasar el evento para coger el nombre y demas datos.


        //Pasamos el evento que se pasara en la vista $evento.
        return view('emails.mensaje', ['evento' => $evento]);
    }

    /**
     * La función "mostrarFormulario" recupera datos basados ​​en un token, verifica su validez y luego
     * muestra un formulario con información relacionada para su actualización.
     * 
     * @param token La función `mostrarFormulario` que proporcionaste es una función PHP que recupera datos
     * de la base de datos basados ​​en un token, verifica si el token existe en la tabla `evento_conductor` y luego muestra un formulario con los datos recuperados para un evento y un conductor específicos.
     * 
     * @return La función `mostrarFormulario` devuelve una vista llamada 'invitados.update' con los
     * siguientes datos: 
     * - 'token' => el token pasado como parámetro a la función
     * - 'evento' => la instancia del modelo Evento recuperada según el evento_id de la tabla
     * 'evento_conductor'
     * - 'conductor' => la instancia del modelo Conductor recuperada según
     */

    public function mostrarFormulario($token)
    {
        $registro = DB::table('evento_conductor')->where('token', $token)->first(); //Aqui se verifica que el token existe en la tabla evento_conductor.

        if (!$registro) {
            return redirect('/')->with('error', 'Token invalido o expirado');
        }

        $evento = Evento::find($registro->evento_id);
        $conductor = Conductor::find($registro->conductor_id);

        return view('invitados.update', ['token' => $token, 'evento' => $evento, 'conductor' => $conductor, 'edicion' => true]);
    }

    /**
     * La función `enviarFormulario` procesa el envío de un formulario, valida los datos de entrada, actualiza un registro del conductor en la base de datos, envía una notificación por correo electrónico al administrador e invalida un token para un conductor de eventos específico.
     * 
     * @param Request request El parámetro `Request` de la función `enviarFormulario`
     * representa la solicitud HTTP que se envía al servidor. Contiene todos los datos que se envían a través de un formulario o cualquier otro método.
     * @param token La función `enviarFormulario` que proporcionó parece gestionar el envío de formularios para un conductor de eventos específico basado en un token. El token se utiliza para recuperar el registro correspondiente de la tabla `evento_conductor`.
     * * 
     * @return La función `enviarFormulario` devuelve una vista llamada 'emails.agradecimiento' después de
     * procesar los datos del formulario, actualizar la información del Conductor en la base de datos, enviar un correo electrónico al
     * administrador e invalidar el token en la tabla 'evento_conductor'.
     */
    public function enviarFormulario(Request $request, $token)
    {
        $registro = DB::table('evento_conductor')->where('token', $token)->first();

        if (!$registro) {
            return redirect('/')->with('error', 'Token inválido o expirado');
        }

        $conductor = Conductor::find($registro->conductor_id);

        if (!$conductor) {
            return redirect('/')->with('error', 'Invitado no encontrado');
        }

        if (Conductor::where('dni', $request->dni)->exists()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'El dni ' . $request->dni . ' ya existe. ');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'required|string|max:9',
            'empresa' => 'required|string|max:255',
            'cif' => 'required|string|max:20',
            'dni' => 'required|string|max:9',
            'vehiculo_prop' => 'nullable|in:si,no',
            'vehiculo_emp' => 'nullable|in:si,no',
            'etiqueta' => 'nullable|in:B,C,ECO,0',
            /* 'carnet' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048', */
            'carnet_caducidad' => 'required|date',
            'intolerancia' => 'nullable|string|max:255',
            'preferencia' => 'required|in:carne,pescado',
            'proteccion_datos' => 'required|in:1'
        ]);

        if ($request->hasFile('carnet')) {
            $carnetPath = $request->file('carnet')->store('carnets', 'public');
        }

        Conductor::where('email', $request->input('email'))->update([
            'cif' => $request->input('cif'),
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'telefono' => $request->input('telefono'),
            'empresa' => $request->input('empresa'),
            'vehiculo_prop' => $request->input('vehiculo_prop'),
            'vehiculo_emp' => $request->input('vehiculo_emp'),
            'intolerancia' => $request->input('intolerancia'),
            'preferencia' => $request->input('preferencia'),
            'carnet_caducidad' => $request->input('carnet_caducidad'),
            'etiqueta' => $request->input('etiqueta'),
            /* 'kam' => $request->input('kam'), */
            'asiste' => $request->input('asiste'),
            'dni' => $request->input('dni'),
            'proteccion_datos' => $request->input('proteccion_datos'),
            /* 'carnet' => $carnetPath ?? null, // si existe */
        ]);

        //Busca el evento que pasamos por parametro y recoge el evento_id.
        $evento = Evento::find($registro->evento_id);
        //Envia correo al adminstrador.
        Mail::to('gestion@bi-sion.es')->send(new ClienteRegistrado($conductor, $evento));

        // Invalidamos el token en evento_conductor , para que el usaurio pueda acceder una sola vez.
        DB::table('evento_conductor')
            ->where('token', $token)
            ->update(['token' => null]);

        return view('emails.agradecimiento');
    }
}
