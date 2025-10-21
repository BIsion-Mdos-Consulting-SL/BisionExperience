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
                            Listado coches del evento {{$evento->nombre}} del dia {{\Carbon\Carbon::parse ($evento->fecha)-> format('d/m/Y')}}
                            @endif
                        </h2>

                        <!-- CONTENEDOR DE BOTONES -->
                        <form class="d-block d-sm-flex mb-5 align-items-center mb-2" action="{{ route('coches.show', $evento->id) }}" method="GET">
                            <div class="col-12 col-sm-6">
                                <input class="form-control me-2 rounded-3" id="nombre" name="buscador" type="text" placeholder="Introducir marca , modelo , version , matricula , KAM">
                            </div>
                            <div class="col-12 mx-0 mx-sm-2 mt-3 mt-sm-0">
                                <button type="submit" class="btn_color me-2">Buscar</button>
                                <button type="submit" class="btn_secundario me-2" id="reset">Limpiar</button>

                                <!--MODAL--->
                                @if(isset($evento))
                                <a href="{{ route('coches.create', $evento->id) }}" class="btn_color">Registro coche</a>
                                @endif
                                
                                <a href="{{route('trazabilidad.index' , $evento->id)}}" class="btn_color">Trazabilidad Paradas</a>
                            </div>
                        </form>

                        <div class="d-flex flex-wrap mb-3 justify-content-between">
                            <!----CUENTA NUMERO TOTAL DE INVITADOS POR PAGINACION--->
                            <div class="text-start">
                                @if(isset($total) && isset($llaves) && isset($no_llaves))
                                <h4 class="fw-bold text-start">{{$total}} coches ({{$llaves}} / {{$no_llaves}}) </h4>
                                @endif
                            </div>

                            <!--RESULTADOS - BOTON(EXPORTAR)--->
                            <div class="d-flex flex-wrap gap-2 text-end">
                                <!---BOTON PARA EXPORTAR COCHES DENTRO DE ESE EVENTO--->
                                <a href="{{route('coches.exportarCoches' , $evento->id)}}" class="btn_color">
                                    <i class="bi bi-file-earmark-excel-fill" title="Exportar"></i>
                                </a>

                                <!--FORMULARIO PARA IMPORTAR EXCEL-->
                                <form action="{{ route('coches.importarCoches' , $evento->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="file" id="fileInput" accept=".xlsx,.xls" style="display: none;" onchange="this.form.submit()" required>
                                    <button class="btn_color" type="button" onclick="document.getElementById('fileInput').click()"><i class="bi bi-file-spreadsheet-fill" title="Importar"></i></button>
                                </form>

                                <!-- BOTÓN VOLVER (alineado al final del formulario) -->
                                <div class="align-items-center m-auto btn btn-secondary">
                                    <a href="{{route('invitados.index' , $evento->id)}}"><i class="bi bi-arrow-left fw-bold" title="Volver"></i></a>
                                </div>
                            </div>
                        </div>

                        <!---CARD TAMAÑO SM - MD , se muestra al ocultar la tabla.--->
                        <div class="row d-block d-lg-none">
                            @if(isset($coches))
                            @foreach($coches as $coche)
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body gap-5">
                                        <p class="card-text mb-1"><strong>Marca:</strong> {{ $coche->marca }}</p>
                                        <p class="card-text mb-1"><strong>Modelo:</strong> {{ $coche->modelo }}</p>
                                        <p class="card-text mb-1"><strong>Version:</strong> {{ $coche->version }}</p>
                                        <p class="card-text mb-1"><strong>Matricula:</strong> {{ $coche->matricula }}</p>
                                        <p class="card-text mb-1"><strong>KAM:</strong> {{ $coche->kam }}</p>

                                        <!---CHECKBOX ASISTE-->
                                        <div class="d-flex align-items-center mt-3">
                                            <input class="form-check-input me-2" type="checkbox"
                                                {{ $coche->asiste ? 'checked' : '' }}
                                                onchange="actualizarAsistencia({{ $coche->id }}, this.checked)">
                                            <label>Llave</label>
                                        </div>

                                        <div class="mt-2 d-flex flex-wrap gap-2 justify-content-end">
                                            <!---BOTON ELIMINAR---->
                                            <form method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button name="delete" id_delete="{{$coche->id}}" type="submit" style="background:none;border:none;color:#05072e;">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>

                                            <!---BOTON EDITAR---->
                                            <form method="GET">
                                                @csrf
                                                <button type="submit" style="background:none;border:none;color:#05072e;">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>

                        <!--TABLA INVITADOS GRANDE-->
                        <table class="table table-bordered mt-3 d-none d-lg-table">
                            <thead>
                                <tr>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Version</th>
                                    <th>Matricula</th>
                                    <th>KAM</th>
                                    <th class="text-center">Llave</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($coches))
                                @foreach($coches as $coche)
                                <tr>
                                    <td>{{$coche->marca}}</td>
                                    <td>{{$coche->modelo}}</td>
                                    <td>{{$coche->version}}</td>
                                    <td>{{$coche->matricula}}</td>
                                    <td>{{$coche->kam}}</td>
                                    <td class="text-center">
                                        <input class="form-check-input" type="checkbox" style="text-align: center;"
                                            {{ $coche->asiste ? 'checked' : '' }}
                                            onchange="actualizarAsistencia({{ $coche->id }}, this.checked)">
                                    </td>
                                    <td class="text-center d-flex flex-wrap gap-3 justify-content-center">
                                        <form method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button name="delete" id_delete="{{$coche->id}}" type="submit" style="background:none;border:none;color:#05072e;">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                        <form method="GET" action="{{route('coches.edit' , $coche->id)}}">
                                            <button type="submit" style="background:none;border:none;color:#05072e;">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{$coches->links()}}
                    </div>
                </div>
            </div>
        </div>
        <!--FOOTER--->
        <footer class="footer">
            <img class="m-auto" src="{{asset('images/footer_bision.png')}}" style="width: 200px;">
        </footer>
</x-app-layout>

<script>
    //FUNCION LIMPIA CAMPO DE BUSQUEDA.
    const btn_reset = document.getElementById('reset');
    const input_nombre = document.getElementById('nombre');

    function reset() {
        input_nombre.value = "";
        window.location = "{{ route('coches.index', $evento->id) }}";
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
                        fetch(`{{route('coches.delete', ':id')}}`.replace(':id', id), {
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
                                            window.location.href = "{{route('coches.index' , $evento->id)}}";
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
    function actualizarAsistencia(id, asiste) { //Pasaremos id como valor y recogeremos en la llamada un dato en este caso valor.
        fetch(`/coches/${id}/actualizar`, {
                //Pasamos el metodo.
                method: 'POST',
                //Recogemos datos y el token csrf del formulario.
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                //Cuerpo , pasamos el valor a string.
                body: JSON.stringify({
                    asiste: asiste
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
</script>
@endsection