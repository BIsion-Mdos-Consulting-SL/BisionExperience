<?php

use App\Http\Controllers\AjustesController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClienteVerificationController;
use App\Http\Middleware\CheckRole; //Añadimos el middlware para poder separar los roles (admin , cliente).
use App\Http\Controllers\CochesController;
use App\Http\Controllers\EventoConductorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\InvitadosController;
use App\Http\Controllers\PatrocinadoresController;
use App\Http\Controllers\PruebaDinamicaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\TimingController;
use App\Http\Controllers\TrazabilidadController;
use App\Http\Middleware\ClienteAuthenticate;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;


//RUTA PARA EL INICIO DE PANTALLA
/* Route::get('/', function () {
    return view('principal');
})->name('inicio'); */

Route::get('/', [ClienteController::class, 'principal'])->name('inicio');

//PANTALLA LOGIN CLIENTE.
Route::get('/cliente/login', function () {
    return view('cliente.login');
})->name('cliente.login');

//PANTALLA LOGIN DEALER.
Route::get('/dealer/login' , function() {
    return view('dealer.login');
})->name('dealer.login');

//RUTA DE DASHBOARD
Route::get('/eventos', [EventosController::class, 'index'])->name('eventos.index');


//RUTAS PARA ADMINISTRADORES.
Route::middleware(['auth',  CheckRole::class . ':admin'])->group(function () {
    Route::middleware(['auth',  CheckRole::class . ':admin'])->group(function () {
        Route::get('/admin/dashboard', [EventosController::class, 'index'])
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

    /************************************TRAZABILIDAD DE PARADAS (TABLA ADMIN)*************************************** */
    Route::get('/trazabilidad/{evento}', [TrazabilidadController::class, 'index'])->name('trazabilidad.index');
    Route::get('/trazabilidad/show/{id}', [TrazabilidadController::class, 'show'])->name('show.trazabilidad');
    Route::get('/eventos/{evento}/reservas/export', [TrazabilidadController::class, 'export'])->name('reservas.export');

    /***** RUTAS PARA AJUSTE (BOTON) */

    // Mostrar página de ajustes de un evento concreto
    Route::get('/ajustes/{evento}', [AjustesController::class, 'index'])
        ->name('admin.ajustes');

    // CREA , EDITA y ELIMINA PARADA
    Route::post('/ajustes/{evento}/paradas', [AjustesController::class, 'storeParadas'])->name('store.paradas');
    Route::put('/ajustes/{evento}/paradas/{parada}', [AjustesController::class, 'updateParadas'])->name('evento.parada.update');
    Route::delete('/ajustes/{evento}/paradas/delete/{id}', [AjustesController::class, 'deleteParadas'])
        ->name('eliminarParada');

    //EDITA COCHES Y ELIMINA
    Route::get('ajustes/{evento}/coches/', [AjustesController::class, 'editCoches'])->name('evento.coche.edit');
    Route::put('ajustes/{evento}/coches/{coche}', [AjustesController::class, 'updateCoches'])->name('evento.coche.update');
    Route::delete('ajustes/{evento}/coches/delete/{id}', [AjustesController::class, 'deleteCoches'])
        ->name('eliminarCoches');

    //CREA Y EDITA RESTAURANTE
    Route::post('ajustes/{evento}/restaurante', [AjustesController::class, 'storeRestaurante'])->name('store.restaurantes');
    Route::put('ajustes/{evento}/restaurante/{restaurante}', [AjustesController::class, 'updateRestaurante'])->name('evento.restaurante.update');
    Route::delete('ajustes/{evento}/restaurante/{id}', [AjustesController::class, 'deleteRestaurante'])->name('eliminarRestaurante');

    //CREA , EDITA Y ELIMINA BANNER
    Route::post('ajustes/{evento}/banner', [AjustesController::class, 'storeBanner'])->name('store.banner');
    Route::put('ajustes/{evento}/banner/{banner}', [AjustesController::class, 'updateBanner'])->name('evento.banner.update');
    Route::delete('ajustes/{evento}/banner/{id}', [AjustesController::class, 'deleteBanner'])->name('eliminarBanner');

    //CREA , EDITA Y ELIMINA TIMING
    Route::get('ajustes/{evento}/edit', [AjustesController::class, 'editTiming'])->name('evento.timing.edit');
    Route::post('ajustes/{evento}/timing', [AjustesController::class, 'storeTiming'])->name('store.timing');
    Route::put('ajustes/{evento}/timing/{timing}', [AjustesController::class, 'updateTiming'])->name('evento.timing.update');
    Route::delete('ajustes/{evento}/timing/{id}', [AjustesController::class, 'deleteTiming'])->name('eliminarTiming');
});

// RUTAS PARA CLIENTES
Route::middleware(['auth', CheckRole::class . ':cliente'])->group(function () {

    // Dashboard
    Route::get('/cliente/dashboard', function () {
        return view('cliente.dashboard');
    })->name('cliente.dashboard');

    // ENTRADA a Ruta (elige el último evento y REDIRIGE a /cliente/eventos/{evento})
    Route::get('/cliente/ruta', [ClienteController::class, 'infoAuto'])
        ->name('cliente.ruta');

    // Muestra la ruta de un evento concreto (renderiza la vista con $evento y $paradas)
    Route::get('/cliente/eventos/{evento}', [ClienteController::class, 'index'])
        ->name('cliente.eventos.show');

    // Coches: entrada sin id (elige último y redirige)
    Route::get('/cliente/coches', [ClienteController::class, 'infoCochesAuto'])
        ->name('cliente.info_coches');

    // Coches: con id pinta la vista con datos
    Route::get('/cliente/coches/{evento}', [ClienteController::class, 'infoCoches'])
        ->name('cliente.eventos.coches');

    Route::get('/cliente/restaurante/{evento}', [ClienteController::class, 'infoRestaurante'])->name('cliente.info_restaurante');

    /***-----------------RESERVA DE PARADAS------------------------------ */
    Route::get('/cargarDatos', [ReservaController::class, 'cargaDatos'])->name('cargar.datos');
    Route::post('/reserva/store/{evento}/{parada}', [ReservaController::class, 'storeReserva'])->name('store.reserva');

    /***-----------------PRUEBAS DINAMICAS------------------------------ */
    Route::get('/cargarDatos/pruebaDinamica', [PruebaDinamicaController::class, 'cargaDatos'])->name('cargarDatos.pruebaDinamica');
    Route::middleware('auth')->post('/store/pruebaDinamica', [PruebaDinamicaController::class, 'storePruebaDinamica'])->name('store.pruebaDinamica');

    /***-----------------PRUEBAS TIMING------------------------------ */
    Route::get('/cargarDatos/timing', [TimingController::class, 'cargarDatos'])->name('cargarDatos.timing');

    /***-----------------PRUEBAS TIMING------------------------------ */
    Route::get('/cargarDatos/patrocinadores', [PatrocinadoresController::class, 'cargarDatos'])->name('cargarDatos.patrocinadores');
});

Route::middleware(['auth' , CheckRole::class . ':dealer'])->group(function () {

});


/**** -------------------------------------ENVIO DE CORREOS---------------------------------------- */

//RUTAS PARA EL ENVIO DE URL.
Route::get('/evento-confirmacion/{token}', [EventoConductorController::class, 'mostrarFormulario'])->name('evento.confirmacion');

//RUTA PARA ENVIAR FORMULARIO (PROCESADO).  
Route::post('/evento-confirmacion/{token}', [EventoConductorController::class, 'enviarFormulario'])->name('evento.enviar');

//RUTA PARA ENVIAR EMAIL AL INVITADO.
Route::get('/evento/{evento_id}/invitado/{conductor_id}/confirmacion', [EventoConductorController::class, 'enviarEmail'])->name('invitados.enviarEmail');


/**** --------------------RUTAS DE AGRADECIMIENTO(PUBLICA)----------------------- */

Route::get('/gracias', function () {
    return view('emails.agradecimiento');
})->name('emails.agradecimiento');

//RUTA PARA BUSCAR MARCAS.
Route::get('/clientes/marcas', [ReservaController::class, 'buscarMarcas']);

//RUTA PARA BUSCAR MODELOS.
Route::get('/clientes/modelos', [ReservaController::class, 'buscarModelos']);

/**** --------------------------------------------------------------------------------------------- */

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
require __DIR__ . '/artisan.php';
