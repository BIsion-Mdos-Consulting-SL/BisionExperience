 # Documentación del Proyecto Laravel 

## Índice
- [Documentación del Proyecto Laravel](#documentación-del-proyecto-laravel)
  - [Índice](#índice)
- [📄 Resumen del Proyecto](#-resumen-del-proyecto)
- [🗂️ Estructura MVC](#️-estructura-mvc)
- [Cambio de idioma en el proyecto](#cambio-de-idioma-en-el-proyecto)
- [Rutas Inicio Sesion (CLIENTE , DEALER , ADMINISTRADOR)](#rutas-inicio-sesion-cliente--dealer--administrador)
  - [📘 Modelos (Models)](#-modelos-models)
  - [👁️ Vistas (Views)](#️-vistas-views)
      - [VISTAS EVENTOS](#vistas-eventos)
      - [`dashboard.blade.php`](#dashboardbladephp)
    - [🧪 Vista con código JS](#-vista-con-código-js)
      - [🧩 Código Blade + JavaScript](#-código-blade--javascript)
      - [`create.blade.php`](#createbladephp)
    - [🧪 Vista con código JS](#-vista-con-código-js-1)
      - [`edit.blade.php`](#editbladephp)
      - [VISTAS INVITADOS](#vistas-invitados)
      - [`index.blade.php`](#indexbladephp)
    - [🧪 Vista con código JS](#-vista-con-código-js-2)
      - [`create.blade.php`](#createbladephp-1)
    - [🧪 Vista con código JS](#-vista-con-código-js-3)
      - [`edit.blade.php`](#editbladephp-1)
      - [`proteccion_datos.blade.php`](#proteccion_datosbladephp)
      - [`update.blade.php`](#updatebladephp)
      - [VISTAS ERRORES](#vistas-errores)
    - [📁 Estructura de carpetas](#-estructura-de-carpetas)
    - [🧩 Vistas comunes](#-vistas-comunes)
      - [VISTAS AUTH](#vistas-auth)
    - [📁 Estructura de carpetas](#-estructura-de-carpetas-1)
    - [🧩 Vistas comunes](#-vistas-comunes-1)
    - [📄 Vistas destacadas](#-vistas-destacadas)
      - [`auth/login.blade.php`](#authloginbladephp)
    - [🧪 Vista con código JS](#-vista-con-código-js-4)
      - [✅ Objetivo](#-objetivo)
      - [🧩 Código Blade + JavaScript](#-código-blade--javascript-1)
      - [`auth/register.blade.php`](#authregisterbladephp)
      - [VISTAS ADMIN](#vistas-admin)
    - [📁 Estructura de carpetas](#-estructura-de-carpetas-2)
    - [🧩 Vistas comunes](#-vistas-comunes-2)
      - [VISTAS CLIENTE](#vistas-cliente)
    - [📁 Estructura de carpetas](#-estructura-de-carpetas-3)
    - [🧩 Vistas comunes](#-vistas-comunes-3)
      - [VISTAS LAYOUTS](#vistas-layouts)
    - [📁 Estructura de carpetas](#-estructura-de-carpetas-4)
    - [🧩 Vistas comunes](#-vistas-comunes-4)
      - [VISTAS VENDOR](#vistas-vendor)
    - [📁 Estructura de carpetas](#-estructura-de-carpetas-5)
    - [🧩 Vistas comunes](#-vistas-comunes-5)
  - [👁️ Controlador (Controllers)](#️-controlador-controllers)
    - [`EventosController`](#eventoscontroller)
    - [🧩 Métodos principales](#-métodos-principales)
      - [RUTAS EventosController](#rutas-eventoscontroller)
      - [Datos extras funcion exportarInvitados():](#datos-extras-funcion-exportarinvitados)
    - [`InvitadosController`](#invitadoscontroller)
    - [🧩 Métodos principales](#-métodos-principales-1)
      - [RUTAS InvitadosController](#rutas-invitadoscontroller)
    - [`CochesController`](#cochescontroller)
    - [🧩 Métodos principales](#-métodos-principales-2)
      - [RUTAS CochesController](#rutas-cochescontroller)
    - [`TrazabilidadController`](#trazabilidadcontroller)
    - [🧩 Métodos principales](#-métodos-principales-3)
      - [RUTAS TrazabilidadController](#rutas-trazabilidadcontroller)
    - [`AjustesController`](#ajustescontroller)
    - [🧩 Métodos principales](#-métodos-principales-4)
      - [RUTAS AjustesController](#rutas-ajustescontroller)
    - [`ClienteController`](#clientecontroller)
    - [🧩 Métodos principales](#-métodos-principales-5)
      - [RUTAS ClienteController](#rutas-clientecontroller)
    - [`PruebaDinamicaController`](#pruebadinamicacontroller)
    - [🧩 Métodos principales](#-métodos-principales-6)
      - [RUTAS ReservaController](#rutas-reservacontroller)
    - [`PruebaDinamicaController`](#pruebadinamicacontroller-1)
    - [🧩 Métodos principales](#-métodos-principales-7)
      - [RUTAS PruebaDinamicaController](#rutas-pruebadinamicacontroller)
    - [`TimingController`](#timingcontroller)
    - [🧩 Métodos principales](#-métodos-principales-8)
      - [RUTAS TimingController](#rutas-timingcontroller)
    - [`PatrocinadoresController`](#patrocinadorescontroller)
    - [🧩 Métodos principales](#-métodos-principales-9)
      - [RUTAS PatrocinadoresController](#rutas-patrocinadorescontroller)
    - [`EventoConductorController`](#eventoconductorcontroller)
    - [🧩 Métodos principales](#-métodos-principales-10)
      - [RUTAS EventoConductorController](#rutas-eventoconductorcontroller)
  - [📌 Conclusión](#-conclusión)
    - [✅ Logros principales:](#-logros-principales)
    - [🚀 Recomendaciones para futuras mejoras:](#-recomendaciones-para-futuras-mejoras)


# 📄 Resumen del Proyecto

Este es un proyecto web desarrollado con **Laravel** para la gestión de eventos en una comunidad tipo **caravana**. La aplicación permite a los usuarios:

- ✅ **Registrarse e iniciar sesión** de forma segura utilizando **Laravel Sanctum**.
- 📅 Crear, ver, editar y eliminar **eventos** mediante una interfaz CRUD completa.
- 🗺️ Cada evento incluye información como nombre, descripción, fecha y ubicación.

El objetivo principal es facilitar la organización de actividades dentro de una comunidad móvil o viajera, brindando una plataforma simple y segura para gestionar eventos.


# 🗂️ Estructura MVC

A continuación se describe cómo está estructurado el proyecto según el patrón **Modelo - Vista - Controlador (MVC)**.

---

# Cambio de idioma en el proyecto 

1. Publicar carpeta lang para ver las que tenemos por defecto (en) , se cambia por "es": 
    ```php
        php artisan lang:publish
        
2. Ejecutar este comando:

    ```php
        composer require laravel-lang/common

3. Crear carpeta "es" con la traduccion cambiada al español
   
    ```php
        php artisan lang:add es

4. Cambiar el .env para que se use la carpeta correcta , dejamos el "en" y usamos el "es":

    ```php
        APP_LOCALE=en , por: 
        APP_LOCALE=es
---


---

# Rutas Inicio Sesion (CLIENTE , DEALER , ADMINISTRADOR)

| Carpeta    | Vista             | Ruta            | Descripción       |
| ---------- | ----------------- | --------------- | ----------------- |
| `cliente/` | `login.blade.php` | `cliente/login` | Ruta para cliente |
| `dealer/`  | `login.blade.php` | `dealer/login`  | Ruta para dealer  |
| `admin/`   | `login.blade.php` | `admin.login`   | Ruta para admin   |

- Cada una son las rutas de acceso dependiendo del rol que tendra cada uno.

## 📘 Modelos (Models)

- **User.php**
  - Representa a los usuarios registrados.
  - Campos principales:
  ```php
  protected $fillable = [
		'rol',
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token'
	];

### 🔗 Relaciones en el modelo User

- **reservas()**
  - Tipo: `hasMany`
  - Un evento puede tener **muchas reservas**.

- **Evento.php**
  - Representa los eventos creados por los usuarios.
  - Campos principales: 
  ```php
  protected $fillable = [
		'nombre',
		'marca',
		'fecha',
		'hora',
		'lugar_evento',
		'tipo_evento',
		'coste_evento',
		'aforo',
		'coste_unitario',
		'enlace',
		'documentacion',
		'texto_invitacion',
		'imagen'
	];
### 🔗 Relaciones en el modelo Evento

```php
public function invitados()
{
    return $this->belongsToMany(Conductor::class, 'evento_conductor', 'evento_id', 'conductor_id');
}

public function marcas()
{
    return $this->belongsToMany(Marca::class, 'eventos_marca', 'evento_id', 'marca_id');
}

public function coches()
{
    return $this->hasMany(Coch::class, 'evento_id');
}

public function paradas()
{
    return $this->hasMany(Parada::class, 'evento_id')->orderBy('orden');
}

public function restaurante()
{
    return $this->hasOne(Restaurante::class, 'evento_id', 'id');
}

public function timings()
{
    return $this->hasMany(Timing::class, 'evento_id', 'id');
}

public function banners()
{
    return $this->hasMany(Banner::class, 'evento_id', 'id');
}
```

- **Conductor.php**
- Representa los invitados creados por los usuarios.
  - Campos principales: 
  ```php
  protected $fillable = [
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
		'carnet_caducidad'
	];

### 🔗 Relaciones en el modelo Conductor

- **eventos()**
  - Tipo: `belongsToMany`
  - Un conductor puede estar **en varios** eventos.
  - Relación con el modelo `Evento`.
  - Usa la tabla pivote `evento_conductor` con las claves: `conductor_id` y `evento_id`.

```php
public function eventos()
	{
		return $this->belongsToMany(Evento::class, 'evento_conductor', 'conductor_id', 'evento_id');
	}
```

- **EventoConductor.php**
- Representa tabla intermedia entre modelos Evento y Conductor.
  - Campos principales: 
  ```php
  protected $fillable = [
		'evento_id',
		'conductor_id',
		'confirmado',
		'token'
	];

### 🔗 Relaciones en el modelo EventoConductor

- **invitados()**
  - Tipo: `hasMany`
  - Un evento puede tener varios conductores asociados.
  - Relación con el modelo `Conductor`.

```php
public function invitados(){
		return $this->hasMany(Conductor::class);
	}
```

- **EventosMarca.php**
- Representa tabla intermedia entre modelos Evento y Marcas.
  - Campos principales: 
  ```php
  protected $fillable = [
		'evento_id',
		'marca_id'
	];

- **Marca.php**
- Representa los nombres de las marcas que tengo guardados en la BD.
  - Campos principales: 
  ```php
  protected $fillable = [
		'nombre'
	];

### 🔗 Relaciones en el modelo Evento

- **eventos()**
  - Tipo: `belongsToMany`
  - Una marca puede estar asociada cuando se crea el evento.
  - Relación con el modelo `Evento`.
  - Usa la tabla pivote `eventos_marca` con las claves: `marca_id` y `evento_id`.

```php
public function eventos(){
		return $this->belongsToMany(Evento::class , 'eventos_marca' , 'marca_id' , 'evento_id');
	}
```

- **TipoEvento.php**
- Representa tabla del tipo de evento que tengo en mi BD
  - Campos principales: 
  ```php
  protected $fillable = [
		'nombre'
	];

- **Coch.php**
- Representa la informacion de los coches que tengo guardados en la BD.
  - Campos principales: 
  ```php
  protected $fillable = [
		'marca',
		'modelo',
		'version',
		'matricula',
		'kam',
		'asiste',
		'evento_id',
		'seguro',
		'documento_seguro',
		'foto_vehiculo'
	];

### 🔗 Relaciones en el modelo Coch

```php
public function reservas()
{
    return $this->hasMany(Reserva::class, 'coche_id');
}

public function evento()
{
    return $this->belongsTo(Evento::class, 'evento_id');
}
```

- **Parada.php**
- Representa la informacion de las paradas que tengo guardados en la BD de cada evento.
  - Campos principales: 
  ```php
  protected $fillable = [
		'evento_id',
		'conductor',
		'nombre',
		'descripcion',
		'enlace',
		'orden'
	];

### 🔗 Relaciones en el modelo Parada

```php
public function evento()
{
    return $this->belongsTo(Evento::class, 'evento_id');
}

public function reservas()
{
    return $this->hasMany(Reserva::class);
}
```

- **Reserva.php**
- Representa la informacion de las reservas que tengo guardadas en la BD de cada evento , como usuario.
  - Campos principales: 
  ```php
    protected $fillable = [
		'user_id',
		'coche_id',
		'parada_id',
		'evento_id',
		'tipo'
	];

    protected $casts = [
		'user_id' => 'int',
		'coche_id' => 'int',
		'parada_id' => 'int',
		'evento_id' => 'int',
		'hora_inicio' => 'string',
		'hora_fin' => 'string'
	];

### 🔗 Relaciones en el modelo Reserva

```php
public function coch()
{
    return $this->belongsTo(Coch::class, 'coche_id');
}

public function parada()
{
    return $this->belongsTo(Parada::class , 'parada_id');
}

    public function user()
{
    return $this->belongsTo(User::class , 'user_id');
}

    public function evento(){
    return $this->belongsTo(Evento::class , 'evento_id');
}
```

- **Restaurante.php**
- Representa la informacion de los o el restaurante que tengo guardado en la BD de cada evento.
  - Campos principales: 
  ```php
    protected $casts = [
        'evento_id' => 'int'
    ];

    protected $fillable = [
        'nombre',
        'descripcion',
        'foto_restaurante',
        'enlace',
        'evento_id'
    ];

### 🔗 Relaciones en el modelo Restaurante

```php
public function evento()
{
    return $this->belongsTo(Evento::class, 'evento_id' , 'id');
}
```

- **Timing.php**
- Representa la informacion del timing que tengo guardado en la BD de cada evento.
  - Campos principales: 
  ```php
    protected $fillable = [
        'nombre',
        'descripcion',
        'evento_id'
    ];

### 🔗 Relaciones en el modelo Timing

```php
public function timings()
{
    return $this->hasMany(Timing::class);
}

    public function evento()
{
    return $this->belongsTo(Evento::class , 'evento_id', 'id');
}
```

- **Banner.php**
- Representa la informacion de cada banner(empresa) que tengo guardado en la BD de cada evento.
  - Campos principales: 
  ```php
    protected $fillable = [
        'evento_id',
        'empresa',
        'enlace',
        'video',
        'imagen',
        'frase',
        'contacto',
        'texto'
    ];

### 🔗 Relaciones en el modelo Banner

```php
public function evento(){
    return $this->belongsTo(Evento::class, 'evento_id');
}
```

## 👁️ Vistas (Views)

Esta sección describe la estructura y organización de las vistas dentro del proyecto.

#### VISTAS EVENTOS

#### `dashboard.blade.php`

- **Propósito**: Pagina de inicio al entrar.
- **Datos requeridos**: For each con distintos campos creados para mostrar todo el contenido (se pasan en el controlador).
- **Acciones**: La vista dashboard esta comentada para que puedas leer el contenido de cada una de sus partes , a continuacion te explico lo mas importante.
- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo , de tal manera que se vea mas legible.

### 🧪 Vista con código JS

A continuación, se muestra un ejemplo de cómo se puede incluir una funcion en JavaScript dentro de una vista Blade para limpiar y refrescar la pagina 

#### 🧩 Código Blade + JavaScript

```blade

<!-- CONTENEDOR DE BOTONES (FORMULARIO) -->
    <form class="d-block flex-wrap col-xl-10" action="{{ route('eventos.show') }}" method="GET">
        <div class="d-flex flex-wrap col-lg-12 align-items-center">
            <div class="col-12 col-lg-6">
            //Campo de busqueda ( se usa para el bsucador).
                <input class="form-control mb-3" id="nombre" name="buscador" type="text" placeholder="Introducir nombre y otro campos">
            </div>
            <div class="col-12 col-lg-6 d-flex gap-2 justify-content-between">
                <button type="submit" class="btn_color txt col-4 col-sm-4 col-lg-4 mb-3 mx-lg-2">Buscar</button>
                <button type="submit" class="btn_color txt col-3 btn_secundario col-sm-4 col-lg-4 mb-3" id="reset">Limpiar</button>
                <a href="{{ route('eventos.create') }}" class="btn_color col-4 col-sm-4 col-lg-4 mb-3">Crear evento</a>
            </div>
        </div>
    </form>
    
<!--LIMPIAR CAMPO-->
    <script>
    //Campos de busqueda (DOM)
        const input_nombre = document.getElementById('nombre');
        const btn_reset = document.getElementById('reset');

    //Funcion limpiar.
        function reset() {
            input_nombre.value = "";
            window.location = "/";
        }

        btn_reset.addEventListener('click', (e) => {
            e.preventDefault();
            reset();
        })
    </script>
```

#### `create.blade.php`

- **Propósito**: Pagina para crear evento.
- **Datos requeridos**: For each con distintos campos creados para mostrar todo el contenido (se pasan en el controlador).
- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo , de tal manera que se vea mas legible.

### 🧪 Vista con código JS

A continuación, se muestra un ejemplo de cómo se puede incluir una funcion en JavaScript dentro de una vista Blade para calcular la media y obtener resultado del COSTE UNITARIO..

```blade

//CAMPOS DE CADA INPUT.
<!-- COSTE EVENTO -->
<input type="text" id="coste_evento" class="form-control" name="coste_evento" placeholder="Coste evento" oninput="calcular_media()">

<!-- AFORO MÁXIMO -->
<input type="text" id="aforo_maximo" class="form-control" name="aforo" placeholder="Aforo máximo" oninput="calcular_media()">

<!-- COSTE UNITARIO -->
<input type="text" id="coste_unitario" class="form-control" name="coste_unitario" placeholder="Coste unitario">

<!---COSTE UNITARIO (FUNCION JS)-->
    <script>
        const coste_evento = document.getElementById('coste_evento');
        const aforo_maximo = document.getElementById('aforo_maximo');
        const coste_unitario = document.getElementById('coste_unitario');

        function calcular_media() {
            let coste = parseFloat(coste_evento.value);
            let aforo = parseFloat(aforo_maximo.value);
            let media = coste / aforo;

            if (!isNaN(media) && isFinite(media)) {
                coste_unitario.value = media.toFixed(2);
            } else {
                coste_unitario.value = '';
            }
        }
    </script>
```

#### `edit.blade.php`

- **Propósito**: Pagina para editar evento.
- **Datos requeridos**: For each con distintos campos creados para mostrar todo el contenido (se pasan en el controlador).
- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo , de tal manera que se vea mas legible. Suele ser igual la vista de create.blade.php pero en este caso recogemos valores que se mostraran en el formulario (GET).

``` blade

//En el formulario se pasa el "enctype" debido a que subiremos archivos e imagenes (se explican y se pasan luego en el controlador).
<form method="POST" action="{{route('eventos.update' , $evento->id)}}" class="m-auto mt-5 mb-5" style="width: 70%;" enctype="multipartform-data">


//Tener en cuenta esta seccion ya que depende del modelo Eloquent de MARCA y EVENTO.
<!--MARCA-->
    <div class="mb-3">
        <label for="marca">Marca* (Para seleccionar mas de una opcion, pulsa la tecla Control)</label>
        //Pasamos marca como array ya que en la tabla intermedia se recogen los valores.
        <select class="form-select" name="marca[]" multiple>
            @if(isset($marcas))
            //sortBy ordena los nombres en orden alfabetico.
            @foreach($marcas->sortBy('nombre') as $marca)
            //Recoge el valor del id de la tabla marcas.
            <option value="{{$marca->id}}"
                //Luego lo busca y lo selecciona en el option.
                {{ $evento->marcas->pluck('id')->contains($marca->id) ? 'selected' : '' }}>
                {{$marca->nombre}}
            </option>
            @endforeach
            @endif
        </select>
    </div>
```


#### VISTAS INVITADOS

#### `index.blade.php`

- **Propósito**: Pagina principal de invitados.
- **Datos requeridos**: For each con distintos campos creados para mostrar todo el contenido (se pasan en el controlador).
- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo , de tal manera que se vea mas legible , esta vista cuenta con dos maneras de ver el contenido de los invitados. , una CARD para tamaño SM Y MD y otra que es la tabla que se mostrara para tamaño LG Y XL (rsponsive).

### 🧪 Vista con código JS

A continuación, se muestra un ejemplo de cómo se puede seleccionar en un CHECKBOX si el invitado ASISTE o NO ASISTE , tambien te explico el JS de como poder eliminar un invitado de ese mismo evento.

``` blade

    <!--BOTON ELIMINAR--->
    <form action="{{route('invitados.delete', $invitado->id)}}" method="POST">
        @csrf
        @method('DELETE')
        //El boton recoge informacion del id(invitado) mediante un nombre que le ponemos.
        <button name="delete" id_delete="{{$invitado->id}}" type="submit" style="background:none;border:none;color:#05072e;">
            <i class="bi bi-trash3-fill"></i>
        </button>
    </form>

    //FUNCION PARA ELIMINAR
    document.addEventListener('DOMContentLoaded', function() {
        //Pasamos al DOM el boton para cada invitado mediante su nombre , con querySelectorAll seleccionamos todos.
        const btn_delete = document.querySelectorAll('[name="delete"]');

        btn_delete.forEach((element) => {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                Swal.fire({
                    title: "¿Seguro que quieres eliminar?",
                    icon: "warning",
                    iconColor: "#05072e",
                    showDenyButton: true,
                    denyButtonColor: "#05072e",
                    showCancelButton: false,
                    showCancelColor: "#05072e",
                    confirmButtonText: "Sí",
                    confirmButtonColor: "#05072e",
                    denyButtonText: `No`
                }).then((result) => {
                    if (result.isConfirmed) {
                        //Recogemos el campo id_delete mediante funcion getAttribute.
                        let id = element.getAttribute('id_delete');
                        //Pasamos en el fetch la ruta y se reemplaza el:id por id para que me reciba la peticion.
                        fetch(`{{route('invitados.delete', ':id')}}`.replace(':id', id), {
                                method: 'DELETE',
                                //Token de seguridad.
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                //Si es exitoso.
                                if (data.success) {
                                    Swal.fire({
                                            title: "¡Eliminado!",
                                            icon: "success",
                                            iconColor: "#05072e",
                                            showConfirmButton: true,
                                            confirmButtonColor: "#05072e"
                                        })
                                        .then(() => {
                                            window.location.href = "{{route('invitados.index' , $evento->id)}}";
                                        })
                                //Si falla.
                                } else {
                                    Swal.fire("Error al eliminar.", "", "error");
                                }
                            })
                            //Error
                            .catch(error => {
                                console.log("Respuesta");
                                Swal.fire("Error al eliminar.", "", "error");
                            });
                    //Si damos al no los cambios no se guardan.
                    } else if (result.isDenied) {
                        Swal.fire({
                            title: "Cambios no guardados.",
                            icon: "warning",
                            iconColor: "#05072e",
                            confirmButtonColor: "#05072e"
                        });
                    }
                });
            });
        });
    });


    //FUNCION CHECKBOX ASISTENCIA
    function actualizarAsistencia(id, valor) { //Pasaremos id como valor y recogeremos en la llamada un dato en este caso valor.
        fetch(`/invitados/${id}/asistencia`, { //Ruta para la solicitud fetch.
                //Pasamos el metodo.
                method: 'POST',
                //Recogemos datos y el token csrf del formulario.
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                //Cuerpo , pasamos el valor a string.
                body: JSON.stringify({
                    asiste: valor
                })
            })
            .then(response => response.json())
            .then(data => {
                //Si no entra en la paticion.
                if (!data.success) {
                    Swal.fire({
                        title: "Error al actualizar asistencia.",
                        icon: "warning",
                        iconColor: "#05072e",
                        confirmButtonColor: "#05072e"
                    })
                }
            })
            //Error
            .catch(() => {
                Swal.fire({
                    title: "Error de conexion.",
                    icon: "warning",
                    iconColor: "#05072e",
                    confirmButtonColor: "#05072e"
                })
            })
    }
```

#### `create.blade.php`

- **Propósito**: Pagina para crear invitados.
- **Datos requeridos**: For each con distintos campos creados para mostrar todo el contenido (se pasan en el controlador).
- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo , de tal manera que se vea mas legible.


```blade
    <!---VEHICULO PROPIO-->
    <label for="vehiculo_prop" class="form-label fw-bold" style="margin-right: 2%">¿Cuenta con un vehiculo propio?</label>
     <input id="btn_si" class="form-check-input" type="radio" value="si" name="vehiculo_prop">
     <input id="btn_no" class="form-check-input" type="radio" value="no" name="vehiculo_prop">

    <!----ETIQUETA (MOSTRAR)----->
    <div id="etiqueta-container" style="display: none;" class="mx-md-2">

    <!---VEHICULO EMPRESA--->
    <label for="vehiculo_emp" class="form-label fw-bold">¿Cuenta con un vehiculo de empresa?</label>
    <input id="btn_si_emp" class="form-check-input" type="radio" value="si" name="vehiculo_emp">
    <input id="btn_no_emp" class="form-check-input" type="radio" value="no" name="vehiculo_emp">
```

### 🧪 Vista con código JS

A continuación, se muestra un ejemplo de cómo se puede seleccionar en un CHECKBOX si el invitado cuenta con coche de vehiculo empresa o coche propio , actualmente esta parte esta hecha para que solo pueda ser si por una de sus partes ya que un invitado solo podra tener un solo coche.

```blade
    <!---JS ETIQUETA--->
    <script>
        const boton_si = document.getElementById('btn_si');//Vehiculo propio
        const boton_no = document.getElementById('btn_no');//Vehiculo propio
        const boton_si_emp = document.getElementById('btn_si_emp');//Vehiculo empresa
        const boton_no_emp = document.getElementById('btn_no_emp');//Vehiculo empresa
        const etiquetaContainer = document.getElementById('etiqueta-container');//Etiqueta contenedor

        //Funcion para mostrar y ocultar contenedor etiqueta.
        function mostrar() {
            if(boton_si.checked || boton_si_emp.checked){
                etiquetaContainer.style.display = "block";
            }else{
                etiquetaContainer.style.display = "none";
            }
        }
        
        boton_si.addEventListener('click' , () =>{
            boton_no_emp.checked = true;
            mostrar();
        })

        boton_si_emp.addEventListener('click', () =>{
            boton_no.checked = true;
            mostrar();
        })

        boton_no.addEventListener('click' , () =>{
            mostrar();
        });

        boton_no_emp.addEventListener('click' , () =>{
            mostrar();
        })
    </script>
```

#### `edit.blade.php`

- **Propósito**: Pagina para editar invitados.
- **Datos requeridos**: For each con distintos campos creados para mostrar todo el contenido (se pasan en el controlador).
- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo , de tal manera que se vea mas legible. Suele ser igual la vista de create.blade.php pero en este caso recogemos valores que se mostraran en el formulario (GET).


#### `proteccion_datos.blade.php`

- **Propósito**: Codigo en HTML que usare para aplicar al modal en el <a>Proteccion de Datos</a> en el formualrio.
  
#### `update.blade.php`

- **Propósito**: Este formulario servira para poder pooder mandar el correo de registro al invitado.

#### VISTAS ERRORES 

### 📁 Estructura de carpetas
### 🧩 Vistas comunes

| Carpeta      | Vista           | Descripción          |
| ------------ | --------------- | -------------------- |
| `errors/401` | `401.blade.php` | Acceso no autorizado |
| `errors/404` | `404.blade.php` | URL no existe.       |

- **Propósito**: Paginas principales que mostraran errores que puedan surgir.
- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo.

#### VISTAS AUTH 

### 📁 Estructura de carpetas
### 🧩 Vistas comunes

| Carpeta | Vista                        | Descripción         |
| ------- | ---------------------------- | ------------------- |
| `auth/` | `confirm-password.blade.php` | Confirma contraseña |
| `auth`  | `forgot.blade.php`           | Olvida contraseña   |
| `auth/` | `login.blade.php`            | Inicio sesion       |
| `auth/` | `register.blade.php`         | Registro            |
| `auth`  | `reset-password.blade.php`   | Cambiar contraseña  |
| `auth/` | `verify-email.blade.php`     | Verificar email     |

- **Propósito**: Paginas principales de autenticacion que serviran para acceder a la aplicacion como administrador entre otras.
- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo.


### 📄 Vistas destacadas

#### `auth/login.blade.php`

- **Propósito**: Poder loguearte al entrar en la pagina del dashboard.
- **Datos requeridos**: Marcar la casilla de politica de privacidad y normas de uso.
- **Acciones**: Permitir validacion antes de entrar.

### 🧪 Vista con código JS

A continuación, se muestra un ejemplo de cómo se puede incluir una validación en JavaScript dentro de una vista Blade para asegurarse de que el usuario acepte los términos antes de iniciar sesión.

#### ✅ Objetivo

Evitar que el formulario se envíe si el checkbox de "He leído y acepto las normas de uso" no está marcado y funcion tamnbien para mostrar y ocultar.

#### 🧩 Código Blade + JavaScript

```blade
<!-- Remember Me -->
<div class="mb-3">
    <label for="remember_me" class="inline-flex items-center">
        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
        <span class="ms-2 text-sm text-gray-600">{{ __('He leído y acepto las normas de uso y la política de privacidad.') }}</span>
    </label>
</div>

<!-- VALIDACIÓN JS PARA PODER LOGUEARTE -->
<script>
    const remember = document.getElementById('remember_me');
    const form = document.getElementById('form');

    function validarCheckbox() {
        if (!remember.checked) {
            Swal.fire({
                title: "Marque casilla para continuar.",
                icon: "warning",
                iconColor: "#05072e",
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#05072e"
            });
            return false;
        }
        return true;
    }

    form.addEventListener('submit', (e) => {
        if (!validarCheckbox()) {
            e.preventDefault();
        }
    });
</script>


<!----FUNCION PARA MOSTRAR/OCULTAR CONTRASEÑA(JS)---->

<div class="input-group">
    <span id="toggle-password" class="input-group-text"><i class="bi bi-eye-fill"></i></span>
    <x-text-input id="password" type="password" name="password" required autocomplete="current-password" class="form-control" />
</div>

<script>
    const inputPassword = document.getElementById('password'); //Campo texto de contraseña.
    const togglePassword = document.getElementById('toggle-password'); //Icono imagen contraseña para mostrar y ocultar.
    const icon = togglePassword.querySelector('i');//Icono

    function mostrar() {
        //Si el campo de texto es tipo password que me cambie el icono y me muestre la contraseña(mostrar).
        if (inputPassword.type === 'password') {
            inputPassword.type = 'text';
            icon.classList.remove('bi-eye-fill');
            icon.classList.add('bi-eye-slash-fill');

        } else {
            //Sino que me muestre todo lo contrario y lo deje como tipo password(ocultar)
            inputPassword.type = 'password';
            icon.classList.remove('bi-eye-slash-fill');
            icon.classList.add('bi-eye-fill');
        }
    }

    //Cuando le de click que me realize la funcion.
    togglePassword.addEventListener('click', () => {
        mostrar();
    })
</script>
```

#### `auth/register.blade.php`

- **Propósito**: Poder registrarte en la pagina.

```blade

@extends('layouts.login')
@section('content')
<x-guest-layout>
    <form method="POST" id="formulario" action="{{ route('register') }}" class="col-12">
        @csrf

        <!-- Name (opcional) -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" class="block mt-1 w-full validar" type="text" name="name" :value="old('name')" autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
       

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full validar"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" class="fw-bold" />

            <div class="input-group">
                <x-text-input type="password" name="password" autocomplete="new-password" class="form-control password validar" />
                <span class="input-group-text toggle-password" style="cursor: pointer;">
                    <i class="bi bi-eye-fill"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label  for="password_confirmation" :value="__('Confirmar contraseña')" />
            <div class="input-group">
                <x-text-input type="password" name="password_confirmation" autocomplete="new-password" class="form-control password validar" />
                <span class="input-group-text toggle-password" style="cursor: pointer;">
                    <i class="bi bi-eye-fill"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!---SWEETALERT PARA EL CORREO-->
        @if ($errors->has('email'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    iconColor: "#05072e",
                    title: 'Correo no existe en la BD',
                    confirmButtonColor: "#05072e"
                });
            });
        </script>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle de contraseña
                const toggles = document.querySelectorAll('.toggle-password');
                toggles.forEach(toggle => {
                    toggle.addEventListener('click', function() {
                        const input = this.previousElementSibling;
                        const icon = this.querySelector('i');

                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.remove('bi-eye-fill');
                            icon.classList.add('bi-eye-slash-fill');
                        } else {
                            input.type = 'password';
                            icon.classList.remove('bi-eye-slash-fill');
                            icon.classList.add('bi-eye-fill');
                        }
                    });
                });

                // Validación del formulario
                const form = document.getElementById('formulario');
                const inputs = document.querySelectorAll('.validar');

                form.addEventListener('submit', function(e) {
                    // validación front (rápida) para UX; el back manda de verdad
                    let valido = true;

                    inputs.forEach(function(input) {
                        if (!input.value || input.value.trim() === "") {
                            input.classList.add('validacion-mal');
                            input.classList.remove('validacion-bien');
                            valido = false;
                        } else {
                            input.classList.remove('validacion-mal');
                            input.classList.add('validacion-bien');
                        }
                    });

                    if (!valido) {
                        e.preventDefault();
                    }
                });

                // Eliminar clase de error en tiempo real
                inputs.forEach(function(input) {
                    input.addEventListener('input', function() {
                        if (input.value.trim() !== "") {
                            input.classList.remove('validacion-mal');
                        }
                    });
                });
            });
        </script>

        <div class="flex items-center justify-end mt-4 gap-2">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Ya registrado?') }}
            </a>

            <button type="submit" class="ms-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 btn_color">
                {{ __('Registrarse') }}
            </button>
        </div>
    </form>
</x-guest-layout>
@endsection
```


#### VISTAS ADMIN 

### 📁 Estructura de carpetas
### 🧩 Vistas comunes

| Carpeta        | Vista                         | Descripción                                       |
| -------------- | ----------------------------- | ------------------------------------------------- |
| `admin/modals` | `modal_banner.blade.php`      | Modal banner                                      |
| `admin/modals` | `modal_coche.blade.php`       | Modal coches                                      |
| `admin/modals` | `modal_parada.blade.php`      | Modal parada                                      |
| `admin/modals` | `modal_restaurante.blade.php` | Modal restaurante                                 |
| `admin/modals` | `modal_timing.blade.php`      | Modal timing                                      |
| `admin/`       | `ajuste.blade.php`            | Vista dashboard Ajustes (se inlucyen los modales) |

- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo.

#### VISTAS CLIENTE 

### 📁 Estructura de carpetas
### 🧩 Vistas comunes

| Carpeta          | Vista                                 | Descripción                                     |
| ---------------- | ------------------------------------- | ----------------------------------------------- |
| `cliente/modals` | `modal_info_restaurante.blade.php`    | Modal para mostrar informacion restaurante      |
| `cliente/modals` | `modal_info_ruta.blade.php`           | Modal para mostrar informacion ruta             |
| `cliente/modals` | `modal_info_patrocinadores.blade.php` | Modal para mostrar partners (patrocinadores)    |
| `cliente/modals` | `modal_pruebaDinamica.blade.php`      | Modal para realizar la prueba dinamica          |
| `cliente/modals` | `modal_reserva.blade.php`             | Modal para realizar la pre-reserva              |
| `cliente/`       | `modal_timing.blade.php`              | Modal para mostrar el timing (horario)          |
| `cliente/`       | `dashboard.blade.php`                 | Pagina principal para la vista cliente          |
| `cliente/`       | `info_coches.blade.php`               | Muestra la informacion de los coches del evento |
| `cliente/`       | `login.blade.php`                     | Formulario de login para CLIENTE                |
| `cliente/`       | `ruta.blade.php`                      | Muestra la informacion de la ruta del evento    |

- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo.

#### VISTAS LAYOUTS 

### 📁 Estructura de carpetas
### 🧩 Vistas comunes

| Carpeta    | Vista                  | Descripción                            |
| ---------- | ---------------------- | -------------------------------------- |
| `layouts`  | `admin.blade.php`      | Esqueleto vista admin  , incluye CSS   |
| `layouts`  | `cliente.blade.php`    | Esqueleto vista cliente  , incluye CSS |
| `layouts`  | `coches.blade.php`     | Esqueleto vista coches , incluye CSS   |
| `layouts`  | `error.blade.php`      | Esqueleto vista coches , incluye CSS   |
| `layouts`  | `footer.php`           | Esqueleto footer , incluye CSS         |
| `layouts/` | `login.php`            | Esqueleto login , incluye CSS          |
| `layouts/` | `main.blade.php`       | Esqueleto principal , incluye CSS      |
| `layouts/` | `navigation.blade.php` | Esqueleto navegacion , incluye CSS     |

- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo.

#### VISTAS VENDOR 

### 📁 Estructura de carpetas
### 🧩 Vistas comunes

| Carpeta  | Vista                           | Descripción           |
| -------- | ------------------------------- | --------------------- |
| `vendor` | `mail/html`                     | Esqueleto correos CSS |
| `vendor` | `mail/text`                     | Esqueleto correos CSS |
| `vendor` | `notifications/email.blade.php` | Esqueleto correos CSS |

- **Extras**: La vista cuenta con comentarios donde se especifica concretamente cada parte del codigo.


## 👁️ Controlador (Controllers)

📌 Descripción general

Los controladores son responsables de recibir las solicitudes del usuario, procesar la lógica correspondiente (generalmente delegando en modelos o servicios), y retornar una respuesta (normalmente una vista o JSON).

En este proyecto, los controladores siguen el patrón MVC proporcionado por Laravel y se encuentran en la ruta:

```blade 
    app/Http/Controllers/
```

📂 Estructura de controladores

    EventosController
    Encargado de manejar toda la lógica relacionada con la gestión de eventos: mostrar, crear, editar y eliminar eventos.

    InvitadosController 
    Encargado de manejar toda la lógica relacionada con la gestión de invitados: mostrar, crear, editar y eliminar invitados.

    AuthController
    Controla los procesos de autenticación: login, logout, registro, etc.

### `EventosController`


### 🧩 Métodos principales

| Método                           | Descripción                                                                    |
| -------------------------------- | ------------------------------------------------------------------------------ |
| `index()`                        | Muestra una vista con la lista de eventos paginados, tipos de evento y marcas. |
| `create()`                       | Muestra el formulario.                                                         |
| `store()`                        | Guarda un nuevo evento en la base de datos.                                    |
| `edit($id)`                      | Muestra el formulario para editar un evento existente.                         |
| `update(Request $request, $id)`  | Actualiza la información de un evento.                                         |
| `delete($id)`                    | Elimina un evento.                                                             |
| `show(Request $request)`         | Busca por todos los campos en el buscador.                                     |
| `filtrarFecha(Request $request)` | Filtra por fecha de inicio y fehca de fin (rango).                             |
| `exportarInvitado($evento_id)`   | Exporta en documento Excel los invitados de cada evento.                       |



#### RUTAS EventosController 
``` php
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
```

#### Datos extras funcion exportarInvitados():

📌 Descripción general

Funciones para poder exportar el archivo en Excel:

``` php
    class InvitadosExport implements FromCollection, WithHeadings
{
    protected $evento; //Protege la propiedad del evento.

    public function headings(): array
    {
        //Nombre del campos de las casillas.
        return [
            'Empresa',
            'CIF',
            'Nombre',
            'Apellidos',
            'Telefono',
            'Dni',
            'Email',
            'KAM',
            'Asistio'
        ];
    }

    public function __construct(Evento $evento)
    {
        $this->evento = $evento; //Guarda el evento para usarlo despues.
    }

    public function collection()
    {
        /***Recoge de cada evento el invitado y recorre con un bucle para que 
         * me pinte todos los que hay ahi , ya que espera un array. */
        return $this->evento->invitados->map(function ($invitado) {
            return [
                'empresa'    => $invitado->empresa,
                'cif'        => $invitado->cif,
                'nombre'     => $invitado->nombre,
                'apellidos'  => $invitado->apellido,
                'telefono'   =>  "\t" . $invitado->telefono,//Se coloca "\t" para que se veo el formato texto (numeros).
                'dni'        => "\t" . $invitado->dni,//Se coloca "\t" para que se veo el formato texto (numeros).
                'email'      => $invitado->email,
                'kam'        => $invitado->kam,
                'asistio'    => $invitado->asiste ? 'Si' : 'No' //Condicion si el invitado asiste o no asiste.
            ];
        });
    }
}
```

### `InvitadosController`

### 🧩 Métodos principales

| Método                          | Descripción                                              |
| ------------------------------- | -------------------------------------------------------- |
| `index()`                       | Muestra una vista con la lista de invitados paginados.   |
| `create()`                      | Muestra el formulario.                                   |
| `store()`                       | Guarda un nuevo invitado en la base de datos.            |
| `edit($id)`                     | Muestra el formulario para editar un invitado existente. |
| `update(Request $request, $id)` | Actualiza la información de un invitado.                 |
| `delete($id)`                   | Elimina un invitado.                                     |
| `show(Request $request)`        | Busca por todos los campos en el buscador.               |
| `actualizarAsistencia($id)`     | Marca en el chekbox y guarda si el invitado asiste o no. |
| `importarInvitados($id)`        | Importa la lista de invitados.                           |

#### RUTAS InvitadosController 

``` php
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
```

### `CochesController`

### 🧩 Métodos principales

| Método                          | Descripción                                               |
| ------------------------------- | --------------------------------------------------------- |
| `index()`                       | Muestra una vista con la lista de coches paginados.       |
| `create()`                      | Muestra el formulario.                                    |
| `store()`                       | Guarda un nuevo coche en la base de datos.                |
| `edit($id)`                     | Muestra el formulario para editar un invitado existente.  |
| `update(Request $request, $id)` | Actualiza la información de un invitado.                  |
| `delete($id)`                   | Elimina un invitado.                                      |
| `show(Request $request)`        | Busca por todos los campos en el buscador.                |
| `actualizarAsistencia($id)`     | Marca en el chekbox y guarda si el coche tiene llave o no |
| `exportarCoches($evento_id)`    | Exporta en Excel.                                         |
| `importarCoches($id)`           | Importa en Excel.                                         |

#### RUTAS CochesController 

``` php
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
```

### `TrazabilidadController`

### 🧩 Métodos principales

| Método                         | Descripción                                                   |
| ------------------------------ | ------------------------------------------------------------- |
| `index($id)`                   | Muestra la vista de toda la trazabilidad de coches (reservas) |
| `show(Request $request , $id)` | Buscador en toda la BD de la tabla trazabilidad               |
| `export()`                     | Exporta la tabla trazabilidad                                 |

#### RUTAS TrazabilidadController 

``` php
    //TRAZABILIDAD DE PARADAS
    Route::get('/trazabilidad/{evento}', [TrazabilidadController::class, 'index'])->name('trazabilidad.index');
    Route::get('/trazabilidad/show/{id}', [TrazabilidadController::class, 'show'])->name('show.trazabilidad');
    Route::get('/eventos/{evento}/reservas/export', [TrazabilidadController::class, 'export'])->name('reservas.export');
```

### `AjustesController`

### 🧩 Métodos principales

| Método                                                                            | Descripción                                     |
| --------------------------------------------------------------------------------- | ----------------------------------------------- |
| `index(Request $request , Evento $evento)`                                        | Muestra la pagina entera con los datos cargados |
| `storeParadas(Request $request , Evento $evento)`                                 | Crea parada                                     |
| `updateParadas(Request $request , Evento $evento , Parada $parada)`               | Edita parada                                    |
| `deleteParadas(Evento $evento , $id)`                                             | Elimina parada                                  |
| `editCoches(Evento $evento)`                                                      | Recoge informacion y la usa para editar coche   |
| `updateCoches(Request $request , Evento $evento , Coch $coche)`                   | Edita coche                                     |
| `deleteCoches(Evento $evento , $id)`                                              | Elimina coche                                   |
| `storeRestaurante(Request $request , Evento $evento)`                             | Crea restaurante                                |
| `updateRestaurante(Request $request , Evento $evento , Restaurante $restaurante)` | Edita restaurante                               |
| `deleteRestaurante(Evento $evento , $id)`                                         | Elimina restaurante                             |
| `storeBanner(Request $request , Evento $evento)`                                  | Crea banner                                     |
| `updateBanner(Request $request , Evento $evento , Banner $banner)`                | Edita banner                                    |
| `deleteBanner(Evento $evento , $id)`                                              | Elimina banner                                  |
| `storeTiming(Request $request , Evento $evento)`                                  | Crea timing                                     |
| `editTiming(Evento $evento)`                                                      | Recoge informacion para editar timing           |
| `updateTiming(Request $request , Evento $evento , Timing $timing)`                | Edita timing                                    |
| `deleteTiming(Evento $evento , $id)`                                              | Elimina timing                                  |

#### RUTAS AjustesController 

``` php
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
```

### `ClienteController`

### 🧩 Métodos principales

| Método                            | Descripción                                                     |
| --------------------------------- | --------------------------------------------------------------- |
| `index(Evento $evento)`           | Muestra informacion de dashboard cliente                        |
| `infoCoches(Evento $evento)`      | Muestra informacion datos coches en vista cliente , todo con id |
| `infoCochesAuto()`                | Muestra informacion coches , todo sin id                        |
| `infoAauto()`                     | Entrada a ruta , elige el ultimo evento                         |
| `infoRestaurante(Evento $evento)` | Muestra informacion datos restaurante                           |
| `principal()`                     | Pagina principal dsahboard (cliente)                            |

#### RUTAS ClienteController 

``` php
    //TRAZABILIDAD DE PARADAS
    Route::get('/', [ClienteController::class, 'principal'])->name('inicio');
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
```

### `PruebaDinamicaController`

### 🧩 Métodos principales

| Método                                                             | Descripción                                             |
| ------------------------------------------------------------------ | ------------------------------------------------------- |
| `cargaDatos()`                                                     | Carga datos para coger informacion y pìntar en el modal |
| `storeReserva(Request $request , Evento $evento , Parada $parada)` | Crea la reserva                                         |


#### RUTAS ReservaController 

``` php
    //RUTAS RESERVA
    Route::get('/cargarDatos', [ReservaController::class, 'cargaDatos'])->name('cargar.datos');
    Route::post('/reserva/store/{evento}/{parada}', [ReservaController::class, 'storeReserva'])->name('store.reserva');
```

### `PruebaDinamicaController`

### 🧩 Métodos principales

| Método                                  | Descripción                                             |
| --------------------------------------- | ------------------------------------------------------- |
| `cargaDatos()`                          | Carga datos para coger informacion y pìntar en el modal |
| `storePruebaDinamica(Request $request)` | Crea la prueba dinamica                                 |


#### RUTAS PruebaDinamicaController 

``` php
    //PRUEBA DINAMICA
    Route::get('/cargarDatos/pruebaDinamica', [PruebaDinamicaController::class, 'cargaDatos'])->name('cargarDatos.pruebaDinamica');
    Route::middleware('auth')->post('/store/pruebaDinamica', [PruebaDinamicaController::class, 'storePruebaDinamica'])->name('store.pruebaDinamica');
```

### `TimingController`

### 🧩 Métodos principales

| Método         | Descripción                                             |
| -------------- | ------------------------------------------------------- |
| `cargaDatos()` | Carga datos para coger informacion y pìntar en el modal |
|                |


#### RUTAS TimingController 

``` php
    //TIMING
    Route::get('/cargarDatos/timing', [TimingController::class, 'cargarDatos'])->name('cargarDatos.timing');
```

### `PatrocinadoresController`

### 🧩 Métodos principales

| Método                          | Descripción                                             |
| ------------------------------- | ------------------------------------------------------- |
| `cargarDatos(Request $request)` | Carga datos para coger informacion y pìntar en el modal |
|                                 |


#### RUTAS PatrocinadoresController 

``` php
    //PATROCINADORES
    Route::get('/cargarDatos/patrocinadores', [PatrocinadoresController::class, 'cargarDatos'])->name('cargarDatos.patrocinadores');
```

### `EventoConductorController`

### 🧩 Métodos principales

| Método                                        | Descripción                                                               |
| --------------------------------------------- | ------------------------------------------------------------------------- |
| `enviarEmail($evento_id , $conductor_id)`     | Manda mensaje a correo electronico que se generara con un token y una url |
| `mostrarFormulario($token)`                   | Recupera datos del token y muestra formulario                             |
| `enviarFormulario(Request $request , $token)` | Envia el formulario con los datos                                         |


#### RUTAS EventoConductorController 

``` php
    //EVENTO CONDUCTOR
    Route::get('/evento-confirmacion/{token}', [EventoConductorController::class, 'mostrarFormulario'])->name('evento.confirmacion');

    //RUTA PARA ENVIAR FORMULARIO (PROCESADO).  
    Route::post('/evento-confirmacion/{token}', [EventoConductorController::class, 'enviarFormulario'])->name('evento.enviar');

    //RUTA PARA ENVIAR EMAIL AL INVITADO.
    Route::get('/evento/{evento_id}/invitado/{conductor_id}/confirmacion', [EventoConductorController::class, 'enviarEmail'])->name('invitados.enviarEmail');
```


## 📌 Conclusión

Este proyecto ha sido desarrollado utilizando **Laravel 12** como framework principal del lado del servidor y **JavaScript** para las interacciones en el cliente. La estructura basada en el patrón **Modelo-Vista-Controlador (MVC)** permitió una clara separación de responsabilidades, facilitando tanto el desarrollo como el mantenimiento del sistema.

Gracias a Laravel, se logró una integración eficiente entre la lógica de negocio, la gestión de rutas y el acceso a la base de datos, mientras que JavaScript aportó dinamismo y una mejor experiencia para el usuario en la interfaz.

### ✅ Logros principales:
- Implementación exitosa del patrón MVC con Laravel 12.
- Código organizado, reutilizable y fácil de escalar.
- Interfaz funcional y dinámica con JavaScript.

### 🚀 Recomendaciones para futuras mejoras:
- Incorporar autenticación basada en roles (admin, usuario, etc.).
- Agregar validaciones asíncronas del lado del cliente.
- Implementar pruebas automatizadas para asegurar la calidad del código.

Este proyecto representa una base sólida para continuar creciendo en complejidad y funcionalidad en futuras versiones.
