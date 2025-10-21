@extends('layouts.main')
@section('content')
<x-app-layout>
    <div class="py-12 fondo_principal">
        <img class="col-7 col-sm-6 col-md-6 col-lg-4" src="{{asset('images/logo.png')}}" style="border-radius: 10px; margin-bottom: 30px; margin: auto; margin-bottom: 40px;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!--CONTENEDOR CARDS -->
                <div class="container">
                    <div class="p-3 col-12 text-gray-900">
                        <h2 class="mt-5 font-semibold text-xl text-gray-800 leading-tight mb-3 fw-bold" style="font-size: x-large;">
                            {{ __('Eventos') }}
                        </h2>

                        <!-- CONTENEDOR DE BOTONES (FORMULARIO) -->
                        <form class="d-block flex-wrap col-xl-10" action="{{ route('eventos.show') }}" method="GET">
                            <div class="d-flex flex-wrap col-lg-12 align-items-center">
                                <div class="col-12 col-lg-6">
                                    <input class="form-control mb-3 rounded-3" id="nombre" name="buscador" type="text" placeholder="Introducir nombre y otro campos">
                                </div>
                                <div class="col-12 col-lg-6 d-flex gap-2 justify-content-between">
                                    <button type="submit" class="btn_color txt col-4 col-sm-4 col-lg-4 mb-3 mx-lg-2">Buscar</button>
                                    <button type="submit" class="btn_color txt col-3 btn_secundario col-sm-4 col-lg-4 mb-3" id="reset">Limpiar</button>
                                    <a href="{{ route('eventos.create') }}" class="btn_color col-4 col-sm-4 col-lg-4 mb-3">Crear evento</a>
                                </div>
                            </div>
                        </form>

                        <!-- FILTRO DE FECHAS -->
                        <form class="d-block flex-wrap col-xl-10 mb-3" action="{{ route('eventos.filtrar') }}" method="GET">
                            <div class="c-azul d-md-flex flex-md-wrap justify-content-column row align-items-center">
                                <div class="col-12 col-md-6 col-lg-5 mb-2">
                                    <label for="fecha_inicio" class="fw-bold mb-2">Desde: <small></small></label>
                                    <input class="form-control rounded-3" type="date" id="fecha_inicio" name="fecha_inicio">
                                </div>
                                <div class="col-12 col-md-6 col-lg-5 mb-2">
                                    <label for="fecha_fin" class="fw-bold mb-2">Hasta: <small></small></label>
                                    <input class="form-control rounded-3" type="date" id="fecha_fin" name="fecha_fin">
                                </div>
                                <div class="col-lg-2 mt-3">
                                    <button type="submit" class="btn_color col-12">Filtrar</button>
                                </div>
                            </div>
                        </form>


                        <!--MENSAJE DE EXITO-->
                        <div>
                            @if(session('success'))
                            <div class="alert alert-success w-50 mt-3">
                                {{session('success')}}
                            </div>
                            @endif
                        </div>

                        <!--MENSAJE DE ERROR-->
                        <div>
                            @if(session('error'))
                            <div class="alert alert-danger">
                                {{session('error')}}
                            </div>
                            @endif
                        </div>

                        <!--LIMPIAR CAMPO-->
                        <script>
                            const input_nombre = document.getElementById('nombre');
                            const btn_reset = document.getElementById('reset');

                            function reset() {
                                input_nombre.value = "";
                                window.location = "/";
                            }

                            btn_reset.addEventListener('click', (e) => {
                                e.preventDefault();
                                reset();
                            })
                        </script>
                        <!--CARDS-->
                        <div class="row">
                            @if(isset($total))
                            <h4 class="fw-bold mb-3 mt-3">{{ $total }} eventos</h4>
                            @endif
                            @if(isset($eventos))
                            @foreach($eventos as $evento)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body" style="margin-bottom: 30%;">
                                        <div class="d-flex justify-content-center">
                                            <img src="{{ asset('storage/' . $evento->imagen) }}" alt="Imagen del evento" class="mb-3" style="width: 100%; height: 150px;">
                                        </div>
                                        <p class="mb-3"><span class="fw-bold">Fecha evento:</span> {{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }} -
                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}</span>
                                        </p>
                                        <p><span class="fw-bold">Nombre:</span> {{ $evento->nombre }}</p>

                                        @if(isset($evento->marcas))
                                        <p><span class="fw-bold">Marca: </span>{{ $evento->marcas->pluck('nombre')->implode(' , ') }}</p>
                                        @endif

                                        <p><span class="fw-bold">Tipo evento:</span> {{ $evento->tipo_evento }}</p>
                                        <p><span class="fw-bold">Lugar evento:</span> {{ $evento->lugar_evento }}</p>
                                    </div>

                                    <!---TEXTO INVITACION (MOSTRAR)--->
                                    @if(isset($evento))
                                    <div class="tooltip-container text-center mb-3">
                                        <p class="fw-bold">
                                            Ver texto de invitación
                                        </p>
                                        <div class="tooltip-texto">
                                            {{ $evento->texto_invitacion }}
                                        </div>
                                    </div>
                                    @endif

                                    <div class="card-footer text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!--BOTON ELIMINAR--->
                                            <form action="{{ route('eventos.delete', $evento->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button name="delete" id_delete="{{ $evento->id }}" class="btn btn-secondary">Eliminar</button>
                                            </form>

                                            <!--BOTON EDITAR-->
                                            <form name="fomulario" action="{{ route('eventos.edit', $evento->id) }}" method="GET">
                                                @csrf
                                                @method('PUT')
                                                <button name="edit" id_update="{{ $evento->id }}" class="btn btn-secondary" style="background-color: #05072e;">Editar</button>
                                            </form>
                                            <!----INVITADOS LISTA---->
                                            <a href="{{ route('invitados.index', $evento->id) }}" class="btn btn-secondary">Invitados</a>

                                            <!-- AJUSTES -->
                                            <a href="{{ route('admin.ajustes', $evento) }}" class="btn btn-secondary" style="background-color:#05072e;">
                                                Ajustes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            {{ $eventos->links() }}
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--FOOTER--->
        <footer class="footer">
            <img class="m-auto" src="{{asset('images/footer_bision.png')}}" style="width: 200px;">
        </footer>
</x-app-layout>
<!--ALERT ELIMINAR-->
<script>
    //ELIMINAR
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
                        fetch(`{{route('eventos.delete', ':id')}}`.replace(':id', id), {
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
                                            window.location.href = "{{route('eventos.index')}}";
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
</script>
@endsection