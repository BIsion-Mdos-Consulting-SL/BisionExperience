@extends('layouts.main')
@section('content')
<x-app-layout>
    <div class="py-12 fondo_principal">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!--CONTENEDOR CARDS -->
                <div class="container py-5">
                    <div class="p-6 text-gray-900">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-3" style="font-size: x-large;">
                            @if(isset($evento))
                            <!---FORMATO FECHA PARA PODER VERLO  d/m/Y---->
                            Invitados al evento {{$evento->nombre}} del dia {{\Carbon\Carbon::parse ($evento->fecha)-> format('d/m/Y')}}
                            @endif
                        </h2>

                        <!-- CONTENEDOR DE BOTONES -->
                        <form class="d-block d-sm-flex d-md-flex flex-wrap mb-5 align-items-center mb-3" action="{{ route('invitados.show', $evento->id) }}" method="GET">
                            <div class="col-12 col-sm-6 col-md-5">
                                <input class="form-control me-2 rounded-3" id="nombre" name="buscador" type="text" placeholder="Introducir CIF, nombre, apellidos, email, teléfono, empresa...">
                            </div>
                            <div class="mx-0 mx-2 mt-3 mt-sm-0 text-start">
                                <button type="submit" class="btn_color me-2">Buscar</button>
                                <button type="submit" class="btn_secundario me-2" id="reset">Limpiar</button>
                                @if(isset($evento))
                                <a href="{{ route('invitados.create', $evento->id) }}" class="btn_color">Nueva invitación</a>
                                @endif
                                <a href="{{ route('coches.index' , $evento->id) }}" class="btn_color">
                                    <i class="bi bi-list-columns" title="Listado coches"></i>
                                </a>
                            </div>
                        </form>

                        <!--RESULTADOS - BOTON(EXPORTAR)--->
                        <div class="d-flex flex-wrap mb-3 justify-content-between">
                            <!----CUENTA NUMERO TOTAL DE INVITADOS POR PAGINACION--->
                            <div class="text-start">
                                @if(isset($total) && isset($asisten) && isset($no_asiste))
                                <h4 class="fw-bold text-start">{{$total}} invitados ({{$asisten}} / {{$no_asiste}}) </h4>
                                @endif
                            </div>
                            <div class="d-flex flex-wrap gap-2 text-end">
                                <!---BOTON PARA EXPORTAR INVITADOS DENTRO DE ESE EVENTO--->
                                <a href="{{ route('eventos.exportarInvitados', $evento->id) }}" class="btn_color">
                                    <i class="bi bi-file-earmark-excel-fill" title="Exportar"></i>
                                </a>

                                <!--FORMULARIO PARA IMPORTAR EXCEL-->
                                <form action="{{ route('invitados.importar' , $evento->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" style="display: none;" onchange="this.form.submit()" required>
                                    <button class="btn_color" type="button" onclick="document.getElementById('fileInput').click()"><i class="bi bi-file-spreadsheet-fill" title="Importar"></i></button>
                                </form>

                                <!-- BOTÓN VOLVER (alineado al final del formulario) -->
                                <div class="align-items-center m-auto btn btn-secondary">
                                    <a href="{{route('dashboard')}}"><i class="bi bi-arrow-left fw-bold" title="Volver"></i></a>
                                </div>
                            </div>
                        </div>

                        <!---CARD TAMAÑO SM - MD , se muestra al ocultar la tabla.--->
                        <div class="row d-block d-lg-none">
                            @foreach($invitados as $invitado)
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body gap-5">
                                        <p class="card-text mb-1"><strong>Nombre:</strong> {{ $invitado->nombre }}</p>
                                        <p class="card-text mb-1"><strong>Apellido:</strong> {{ $invitado->apellido }}</p>
                                        <p class="card-text mb-1"><strong>Empresa:</strong> {{ $invitado->empresa }}</p>

                                        <p class="card-text mb-1 fecha_carnet" data-fecha="{{ $invitado->carnet_caducidad }}">
                                            <strong>Fecha Carnet de conducir:</strong>
                                            {{ \Carbon\Carbon::parse($invitado->carnet_caducidad)->format('d/m/Y') }}
                                        </p>

                                        <p class="card-text mb-1"><strong>CIF:</strong> {{ $invitado->cif }}</p>
                                        <p class="card-text mb-1"><strong>DNI:</strong> {{ $invitado->dni }}</p>
                                        <p class="card-text mb-1"><strong>Teléfono:</strong> {{ $invitado->telefono }}</p>
                                        <p class="card-text mb-1 email" data-email="{{ $invitado->email }}">
                                            <strong>Email:</strong> {{ $invitado->email }}
                                        </p>
                                        <p class="card-text mb-1"><strong>KAM:</strong> {{ $invitado->kam }}</p>
                                        <p class="card-text mb-1"><strong>Observaciones:</strong> {{ $invitado->observaciones }}</p>

                                        <!---CHECKBOX ASISTE-->
                                        <div class="d-flex align-items-center mt-3">
                                            <input class="form-check-input me-2" type="checkbox"
                                                {{ $invitado->asiste ? 'checked' : '' }}
                                                onchange="actualizarAsistencia({{ $invitado->id }}, this.checked)">
                                            <label>Asiste</label>
                                        </div>

                                        <div class="mt-2 d-flex flex-wrap gap-2 justify-content-end">
                                            <!---BOTON ELIMINAR---->
                                            <form action="{{route('invitados.delete', $invitado->id)}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button name="delete" id_delete="{{$invitado->id}}" type="submit" style="background:none;border:none;color:#05072e;" title="Eliminar">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>

                                            <!---BOTON EDITAR---->
                                            <form action="{{route('invitados.edit', $invitado->id)}}" method="GET">
                                                @csrf
                                                <button type="submit" style="background:none;border:none;color:#05072e;" title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </form>

                                            <!---BOTON ENVIAR EMAIL CONFIRMACION--->
                                            <form action="{{route('invitados.enviarEmail', ['evento_id' => $evento->id , 'conductor_id' => $invitado->id])}}" method="GET">
                                                @csrf
                                                <button type="submit" style="background:none;border:none;color:#05072e;" title="Enviar Correo">
                                                    <i class="bi bi-envelope-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="table-responsive">
                            <!--TABLA INVITADOS GRANDE-->
                            <table class="table table-bordered mt-3 d-none d-lg-table">
                                <thead>
                                    <tr>
                                        <th>Empresa</th>
                                        <th>CIF</th>
                                        <th>Nombre</th>
                                        <th>Apellidos</th>
                                        <th>DNI</th>
                                        <th>Fecha Carnet de conducir</th>
                                        <th>Telefono</th>
                                        <th>Email</th>
                                        <th>KAM</th>
                                        <th>Observaciones</th>
                                        <th>Asiste</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($invitados))
                                    @foreach($invitados as $invitado)
                                    <tr style="font-size: small;">
                                        <td>{{$invitado->empresa}}</td>
                                        <td>{{$invitado->cif}}</td>
                                        <td>{{$invitado->nombre}}</td>
                                        <td>{{$invitado->apellido}}</td>
                                        <td>{{$invitado->dni}}</td>


                                        <td class="fecha_carnet" data-fecha="{{ $invitado->carnet_caducidad }}">
                                            {{ \Carbon\Carbon::parse($invitado->carnet_caducidad)->format('d/m/Y') }}
                                        </td>


                                        <td>{{$invitado->telefono}}</td>
                                        <td class="email">{{$invitado->email}}</td>
                                        <td>{{$invitado->kam}}</td>
                                        <td></td>

                                        <!---CHECKBOX ASISTENCIA---->
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" style="text-align: center;"
                                                {{ $invitado->asiste ? 'checked' : '' }}
                                                onchange="actualizarAsistencia({{ $invitado->id }}, this.checked)">
                                        </td>

                                        <!--BOTONES--->
                                        <td class="text-center d-flex flex-wrap gap-3 justify-content-center">
                                            <!--BOTON ELIMINAR--->
                                            <form action="{{route('invitados.delete', $invitado->id)}}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button name="delete" id_delete="{{$invitado->id}}" title="Eliminar" type="submit" style="background:none;border:none;color:#05072e;">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>

                                            <!---BOTON EDITAR---->
                                            <form action="{{route('invitados.edit', $invitado->id)}}" method="GET">
                                                @csrf
                                                <button type="submit" style="background:none;border:none;color:#05072e;" title="Editar">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </form>

                                            <!---BOTON ENVIAR EMAIL CONFIRMACION--->
                                            <form action="{{ route('invitados.enviarEmail', ['evento_id' => $evento->id, 'conductor_id' => $invitado->id]) }}" method="GET">
                                                <button type="submit" style="background:none;border:none;color:#05072e;" title="Enviar email">
                                                    <i class="bi bi-envelope-fill"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{$invitados->links()}}
                    </div>
                </div>
            </div>
        </div>
        <!--FOOTER--->
        <footer class="footer">
            <img class="m-auto" src="{{asset('images/footer_bision.png')}}" style="width: 200px;">
        </footer>
</x-app-layout>
@isset($evento)
<script>
    //FUNCION LIMPIA CAMPO DE BUSQUEDA.
    const btn_reset = document.getElementById('reset');
    const input_nombre = document.getElementById('nombre');

    function reset() {
        input_nombre.value = "";
        window.location = "{{ route('invitados.index', $evento->id) }}";
    }

    btn_reset.addEventListener('click', (e) => {
        e.preventDefault();
        reset();
    });


    //FUNCION PARA ELIMINAR
    document.addEventListener('DOMContentLoaded', function() {
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
                        let id = element.getAttribute('id_delete');
                        fetch(`{{route('invitados.delete', ':id')}}`.replace(':id', id), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
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
                                } else {
                                    Swal.fire("Error al eliminar.", "", "error");
                                }
                            })
                            .catch(error => {
                                console.log("Respuesta");
                                Swal.fire("Error al eliminar.", "", "error");
                            });
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
        fetch(`/invitados/${id}/asistencia`, {
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
                if (!data.success) {
                    Swal.fire({
                        title: "Error al actualizar asistencia.",
                        icon: "warning",
                        iconColor: "#05072e",
                        confirmButtonColor: "#05072e"
                    })
                }
            })
            .catch(() => {
                Swal.fire({
                    title: "Error de conexion.",
                    icon: "warning",
                    iconColor: "#05072e",
                    confirmButtonColor: "#05072e"
                })
            })
    }

    //FUNCION PARA COMPARAR FECHA DE CADUCIDAD DEL CARNET DE CONDUCIR CON LA ACTUAL.
    document.addEventListener('DOMContentLoaded', function() {

        //Seleccionamos el td que tiene como clase(fecha_carnet).
        const camposFecha = document.querySelectorAll('.fecha_carnet');

        //Se crea la fecha actual.
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);

        //Recorremos todos los campos fecha_carnet y colocamos el rojo si se cumple o no la condicion.
        camposFecha.forEach(input => {
            const valorFecha = new Date(input.dataset.fecha);

            if (valorFecha < hoy) {
                input.classList.add('caducado');
            } else {
                input.classList.remove('caducado');
            }
        })
    })


    //FUNCION PARA COMPROBAR EMAIL SI EXISTE O NO EXISTE.
    document.addEventListener('DOMContentLoaded', function() {
        const camposEmail = document.querySelectorAll('.email');

        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        camposEmail.forEach(input => {
            const valor = input.dataset.email || input.value || input.textContent;

            if (regex.test(valor.trim())) {
                input.classList.add('valido');
                input.classList.remove('caducado');
            } else {
                input.classList.add('caducado');
                input.classList.remove('valido');
            }
        });
    });
</script>
@endisset
@endsection