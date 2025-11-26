@extends('layouts.main')
@section('content')
<x-app-layout>
    <div class="py-12 fondo_principal">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3">
            <!---MENSAJES DE EXITO Y ERROR---->
            @if(session('success') || session('error'))
            <div class="position-fixed top-0 end-0 p-3" style="margin-top: 80px; z-index:1050;">
                <div
                    class="alert shadow alert-dismissible fade show 
                {{ session('success') ? 'alert-success' : 'alert-danger' }}"
                    role="alert">
                    {{ session('success') ?? session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!--CONTENEDOR CARDS -->
                <div class="container py-5">
                    <div class="p-6 text-gray-900">
                        <h2 class="fw-bold text-xl text-gray-800 leading-tight mb-3" style="font-size: x-large;">
                            @if(isset($evento))
                            <!---FORMATO FECHA PARA PODER VERLO  d/m/Y---->
                            Listado Pre reservas de coches {{$evento->nombre}} del dia {{\Carbon\Carbon::parse ($evento->fecha)-> format('d/m/Y')}}
                            @endif
                        </h2>

                        <!-- CONTENEDOR DE BOTONES -->
                        <form action="{{route('pre_reserva.show' , $evento->id)}}" class="d-block d-sm-flex mb-5 align-items-center mb-2" method="GET">
                            <div class="col-12 col-sm-6">
                                <input class="form-control me-2 rounded-3" id="nombre" name="buscador" type="text" placeholder="Introducir nombre y otros campos">
                            </div>
                            <div class="col-12 mx-0 mx-sm-2 mt-3 mt-sm-0">
                                <button type="submit" class="btn_color me-2">Buscar</button>
                                <button type="submit" class="btn_secundario me-2" id="reset">Limpiar</button>
                            </div>
                        </form>

                        <div class="d-flex flex-wrap mb-3 justify-content-between">
                            <!----CUENTA NUMERO TOTAL DE INVITADOS POR PAGINACION--->
                            <div class="text-start">
                                <h4 class="fw-bold">{{$totalPagina}} reservas</h4>
                            </div>

                            <!--RESULTADOS - BOTON(EXPORTAR)--->
                            <div class="d-flex flex-wrap gap-2 text-end">
                                <!---BOTON PARA EXPORTAR COCHES DENTRO DE ESE EVENTO--->
                                <a href="{{route('pre_reserva.export' , $evento)}}" class="btn_color">
                                    <i class="bi bi-file-earmark-excel-fill" title="Exportar"></i>
                                </a>

                                <!-- BOTÓN VOLVER (alineado al final del formulario) -->
                                <div class="align-items-center m-auto btn btn-secondary">
                                    <a href="{{route('trazabilidad.index' , $evento->id)}}"><i class="bi bi-arrow-left fw-bold" title="Volver"></i></a>
                                </div>
                            </div>
                        </div>

                        <!---CARD TAMAÑO SM - MD , se muestra al ocultar la tabla.--->
                        <div class="row d-block d-lg-none">
                            @foreach($reservas as $reserva)
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body gap-5">
                                        <p class="card-text mb-1"><strong>Parada: </strong>{{ $reserva->parada->nombre ?? '-' }}</p>
                                        <p class="card-text mb-1"><strong>Modelo: </strong>{{ $reserva->coch->modelo ?? '-' }}</p>
                                        <p class="card-text mb-1"><strong>Matricula: </strong>{{ $reserva->coch->matricula ?? '-' }}</p>
                                        <p class="card-text mb-1"><strong>Usuario: </strong>{{ $reserva->user->name ?? '-' }}</p>
                                        <p class="card-text mb-1"><strong>Tipo: </strong>{{ ucfirst($reserva->tipo) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!--TABLA INVITADOS GRANDE-->
                        <table class="table table-bordered mt-3 d-none d-lg-table">
                            <thead>
                                <tr>
                                    <th>Parada</th>
                                    <th>Modelo</th>
                                    <th>Matricula</th>
                                    <th>Usuario</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservas as $reserva)
                                <tr>
                                    <td>{{ $reserva->parada->nombre ?? '-' }}</td>
                                    <td>{{ $reserva->coch->modelo ?? '-' }}</td>
                                    <td>{{ $reserva->coch->matricula ?? '-' }}</td>
                                    <td>{{ $reserva->user->name ?? '-' }}</td>
                                    <td>{{ ucfirst($reserva->tipo) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $reservas->links() }}
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
@endsection