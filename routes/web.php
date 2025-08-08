<?php

use App\Http\Middleware\CheckRole; //Añadimos el middlware para poder separar los roles (admin , cliente).
use App\Http\Controllers\CochesController;
use App\Http\Controllers\EventoConductorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\InvitadosController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

// RUTAS PARA ADMINISTRADORES.
Route::middleware(['auth',  CheckRole::class . ':admin'])->group(function () {
    Route::middleware(['auth',  CheckRole::class . ':admin'])->group(function () {
        Route::get('/dashboard', [EventosController::class, 'index'])
            ->middleware(['verified'])
            ->name('dashboard');
    });

    /**------------------------------------------------------------------------------------------------*/
    /**CRUD EVENTOS */
    Route::get('/eventos/create', [EventosController::class, 'create'])->name('eventos.create');
    Route::post('/eventos/store', [EventosController::class, 'store'])->name('eventos.store');
    Route::delete('/eventos/delete/{id}', [EventosController::class, 'delete'])->name('eventos.delete');
    Route::get('/eventos/edit/{id}', [EventosController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/update/{id}', [EventosController::class, 'update'])->name('eventos.update');

    Route::get('/eventos/show', [EventosController::class, 'show'])->name('eventos.show');
    Route::get('/eventos/filtrar', [EventosController::class, 'filtrarFecha'])->name('eventos.filtrar');

    //EXPORTAR EXCEL
    Route::get('/eventos/{evento_id}/exportar-invitados', [EventosController::class, 'exportarInvitados'])->name('eventos.exportarInvitados');

    /**------------------------------------------------------------------------------------------------*/

    /***CRUD INVITADOS(CONDUCTORES) */
    Route::get('/invitados/{id}', [InvitadosController::class, 'index'])->name('invitados.index');
    Route::get('/invitados/create/{id}', [InvitadosController::class, 'create'])->name('invitados.create');
    Route::post('/invitados/store/{id}', [InvitadosController::class, 'store'])->name('invitados.store');
    Route::delete('/invitados/delete/{id}', [InvitadosController::class, 'delete'])->name('invitados.delete');
    Route::get('/invitados/edit/{id}', [InvitadosController::class, 'edit'])->name('invitados.edit');
    Route::put('/invitados/update/{id}', [InvitadosController::class, 'update'])->name('invitados.update');

    Route::get('/invitados/show/{id}', [InvitadosController::class, 'show'])->name('invitados.show');
    Route::post('/invitados/{id}/asistencia', [InvitadosController::class, 'actualizarAsistencia']);

    //IMPORTAR INVITADOS 
    Route::post('/invitados/importar/{id}', [InvitadosController::class, 'importarInvitados'])->name('invitados.importar');

    /***--------------------------------------------------------------------------------------------------------- */

    //CRUD COCHES
    Route::get('/coches/{id}', [CochesController::class, 'index'])->name('coches.index');
    Route::get('/coches/create/{id}', [CochesController::class, 'create'])->name('coches.create');
    Route::post('/coches/{id}', [CochesController::class, 'store'])->name('coches.store');
    Route::delete('/coches/delete/{id}', [CochesController::class, 'delete'])->name('coches.delete');
    Route::get('/coches/edit/{id}', [CochesController::class, 'edit'])->name('coches.edit');
    Route::put('/coches/update/{id}', [CochesController::class, 'update'])->name('coches.update');

    Route::get('/coches/show/{id}', [CochesController::class, 'show'])->name('coches.show');
    Route::post('/coches/{id}/actualizar', [CochesController::class, 'actualizarAsistencia']);

    //EXPORTAR EXCEL
    Route::get('/coches/{evento_id}/exportar-coches', [CochesController::class, 'exportarCoches'])->name('coches.exportarCoches');
    //IMPORTAR EXCEL
    Route::post('/coches/importar/{id}', [CochesController::class, 'importarCoches'])->name('coches.importarCoches');

    /***-------------------------------------------------------------------------------------------------------- */
});


// RUTAS PARA CLIENTES
Route::middleware(['auth', CheckRole::class . ':cliente'])->group(function () {
    Route::get('/cliente/dashboard', function () {
        return view('cliente.dashboard');
    })->name('cliente.dashboard');
});

//RUTA DE DASHBOARD
Route::get('/', [EventosController::class, 'index'])->name('eventos.index');

//RUTA DE AGRADECIMIENTO
Route::get('/gracias', function () {
    return view('emails.agradecimiento');
})->name('emails.agradecimiento');


//RUTA PARA BUSCAR MARCAS.
Route::get('/clientes/marcas', [ReservaController::class, 'buscarMarcas']);

//RUTA PARA BUSCAR MODELOS.
Route::get('/clientes/modelos', [ReservaController::class, 'buscarModelos']);

/**** -------------------------------------ENVIO DE CORREOS---------------------------------------- */

//RUTAS PARA EL ENVIO DE URL.
Route::get('/evento-confirmacion/{token}', [EventoConductorController::class, 'mostrarFormulario'])->name('evento.confirmacion');

//RUTA PARA ENVIAR FORMULARIO (PROCESADO).  
Route::post('/evento-confirmacion/{token}', [EventoConductorController::class, 'enviarFormulario'])->name('evento.enviar');

//RUTA PARA ENVIAR EMAIL AL INVITADO.
Route::get('/evento/{evento_id}/invitado/{conductor_id}/confirmacion', [EventoConductorController::class, 'enviarEmail'])->name('invitados.enviarEmail');

/***-----------------ENVIO DE PARADAS------------------------------ */

//RUTAS PARA EL ENVIO DE URL (RESERVA-PARADAS).
Route::get('/reservas-confirmacion/{token}', [ReservaController::class, 'mostrarFormulario'])->name('reserva.confirmacion');

Route::post('/reserva-confirmacion/{paradaId}', [ReservaController::class, 'guardarReserva'])->name('reserva.guardar');


//RUTA PARA ENVIAR EMAIL AL INVITADO.
Route::get('/reserva/{evento_id}/invitado/{conductor_id}/confirmacion', [ReservaController::class, 'enviarEmail'])
    ->name('reserva.enviarEmail');

/* Mail::raw('Correo de prueba', function ($message) {
    $message->to('aarongutierrez@bision.es')->subject('Correo de prueba');
}); */

/**** --------------------------------------------------------------------------------------------- */

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
require __DIR__ . '/artisan.php';
