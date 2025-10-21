@extends('layouts.main')
@section('content')
<x-app-layout>
    <div class="py-12 fondo_principal">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!--CONTENEDOR CARDS -->
                <div class="container py-5">
                    <div class="p-6 text-gray-900">
                        <h2 class="fw-bold text-gray-800 leading-tight mb-3" style="font-size: xx-large">
                            TRAZABILIDAD DE PARADAS
                        </h2>

                        <!----FORMULARIO PARA BUSCAR---->
                        <form action="{{route('show.trazabilidad', $evento->id)}}" method="GET" class="mt-4">
                            <div class="d-flex flex-wrap col-lg-12 align-items-center">
                                <div class="col-12 col-lg-6">
                                    <input class="form-control mb-3 rounded-3" id="input" name="buscador" type="text" placeholder="Introducir nombre y otro campos">
                                </div>
                                <!---BOTONES---->
                                <div class="col-12 col-lg-3 d-flex gap-1">
                                    <button type="submit" class="btn_color txt col-4 col-sm-4 col-lg-4 mb-3 mx-lg-2">Buscar</button>
                                    <button type="submit" class="btn_color txt col-3 btn_secundario col-sm-4 col-lg-4 mb-3" id="reset">Limpiar</button>
                                </div>
                            </div>
                        </form>

                        <div class="d-flex flex-wrap mb-3 justify-content-end">
                            <!--RESULTADOS - BOTON(EXPORTAR)--->
                            <div class="d-flex flex-wrap gap-2 text-end">
                                <!---BOTON PARA EXPORTAR COCHES DENTRO DE ESE EVENTO--->
                                <a href="{{route('reservas.export' , $evento->id)}}" class="btn_color">
                                    <i class="bi bi-file-earmark-excel-fill" title="Exportar"></i>
                                </a>

                                <!-- BOTÓN VOLVER (alineado al final del formulario) -->
                                <div class="align-items-center m-auto btn btn-secondary">
                                    <a href="{{route('coches.index' , $evento->id)}}"><i class="bi bi-arrow-left fw-bold" title="Volver"></i></a>
                                </div>
                            </div>
                        </div>

                        <!----CONTADOR PARA LAS RESERVAS / POR PAGINA---->
                        <div class="fw-bold mb-3">
                            {{$totalPagina}} reservas
                        </div>

                        <!---CARD TAMAÑO SM - MD , se muestra al ocultar la tabla.--->
                        <div class="row d-block d-lg-none">
                            @isset($paradas, $coches, $reservasMap)
                            @foreach($paradas as $parada)
                            @foreach($coches as $coche)
                            @php
                            // Grupo de reservas (parada y coche)
                            $grupo = data_get($reservasMap, "{$parada->id}.{$coche->id}", collect());
                            if (!($grupo instanceof \Illuminate\Support\Collection)) {
                            $grupo = collect($grupo);
                            }
                            // Saltar si no hay reservas para esta combinación
                            if ($grupo->isEmpty()) { continue; }

                            $norm = fn($s) => mb_strtolower($s ?? '', 'UTF-8');
                            $conductor = $grupo->first(fn($r) => $norm($r->tipo) === 'conductor');

                            $acompanantesNombres = $grupo
                            ->filter(fn($r) => in_array($norm($r->tipo), ['acompanante','acompañante']))
                            ->pluck('user.name')
                            ->unique()
                            ->values()
                            ->join(', ');

                            // Horas desde reservas (rango agregado)
                            $horaInicioRaw = $grupo->pluck('hora_inicio')->filter()->min();
                            $horaFinRaw = $grupo->pluck('hora_fin')->filter()->max();

                            $fmt = function ($t) {
                            try { return $t ? \Carbon\Carbon::createFromFormat('H:i:s', $t)->format('H:i') : null; }
                            catch (\Exception $e) { return $t; }
                            };
                            $horaInicio = $fmt($horaInicioRaw);
                            $horaFin = $fmt($horaFinRaw);
                            $rango = trim(($horaInicio ?: '') . ' - ' . ($horaFin ?: ''));
                            @endphp

                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body gap-2">
                                        <p class="card-text mb-1"><strong>Parada:</strong> {{ $parada->nombre }}</p>
                                        <p class="card-text mb-1"><strong>Hora inicio - Hora fin:</strong> {{ $rango ?: '—' }}</p>
                                        <p class="card-text mb-1"><strong>Modelo:</strong> {{ $coche->modelo }}</p>
                                        <p class="card-text mb-1"><strong>Matricula:</strong> {{ $coche->matricula }}</p>
                                        <p class="card-text mb-1"><strong>Conductor:</strong> {{ optional(optional($conductor)->user)->name }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endforeach
                            @endisset
                        </div>

                        <!--TABLA INVITADOS GRANDE-->
                        <table class="table table-bordered mt-3 d-none d-lg-table">
                            <thead>
                                <tr>
                                    <th>Parada</th>
                                    <th>Hora inicio - Hora fin</th>
                                    <th>Modelo</th>
                                    <th>Matricula</th>
                                    <th>Conductor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($paradas, $coches, $reservasMap)
                                @foreach($paradas as $parada)
                                @foreach($coches as $coche)
                                @php
                                // Grupo de reservas (parada y coche)
                                $grupo = data_get($reservasMap, "{$parada->id}.{$coche->id}", collect());
                                if (!($grupo instanceof \Illuminate\Support\Collection)) {
                                $grupo = collect($grupo);
                                }

                                $norm = fn($s) => mb_strtolower($s ?? '', 'UTF-8');
                                $conductor = $grupo->first(fn($r) => $norm($r->tipo) === 'conductor');

                                $acompanantesNombres = $grupo
                                ->filter(fn($r) => in_array($norm($r->tipo), ['acompanante','acompañante']))
                                ->pluck('user.name')
                                ->unique()
                                ->values()
                                ->join(', ');

                                // Horas desde reservas (rango agregado)
                                $horaInicioRaw = $grupo->pluck('hora_inicio')->filter()->min();
                                $horaFinRaw = $grupo->pluck('hora_fin')->filter()->max();

                                $fmt = function ($t) {
                                try { return $t ? \Carbon\Carbon::createFromFormat('H:i:s', $t)->format('H:i') : null; }
                                catch (\Exception $e) { return $t; }
                                };
                                $horaInicio = $fmt($horaInicioRaw);
                                $horaFin = $fmt($horaFinRaw);
                                @endphp

                                {{-- Oculta filas sin reservas --}}
                                @continue($grupo->isEmpty())

                                <tr>
                                    <td>{{ $parada->nombre }}</td>
                                    <td>{{ trim(($horaInicio ?: '') . ' - ' . ($horaFin ?: '')) ?: '—' }}</td>
                                    <td>{{ $coche->modelo }}</td>
                                    <td>{{ $coche->matricula }}</td>
                                    <td>{{ optional(optional($conductor)->user)->name }}</td>
                                </tr>
                                @endforeach
                                @endforeach
                                @endisset
                            </tbody>
                        </table>

                        {{-- Normalizar $paradas a un paginador si vienes de index() --}}
                        @php
                        $esPaginadorParadas =
                        isset($paradas) && (
                        $paradas instanceof \Illuminate\Contracts\Pagination\Paginator
                        || $paradas instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
                        );

                        if (!$esPaginadorParadas) {
                        if (isset($pares) && (
                        $pares instanceof \Illuminate\Contracts\Pagination\Paginator
                        || $pares instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
                        )) {
                        // Reusar $paradas para que tu línea existente no falle
                        $paradas = $pares->withQueryString();
                        }
                        }
                        @endphp
                        {{ $paradas->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!--FOOTER--->
        <footer class="footer">
            <img class="m-auto" src="{{ asset('images/footer_bision.png') }}" style="width: 200px;">
        </footer>
    </div>
</x-app-layout>
@endsection

<script>
    document.addEventListener('DOMContentLoaded' , function() {
        const input = document.getElementById('input');
        const btn_reset = document.getElementById('reset');
        const resetUrl = "{{route('trazabilidad.index' , [ 'evento' => $evento->id])}}";

        function reset(){
            input.value = '';
            window.location = resetUrl;
        }

        btn_reset.addEventListener('click' , (e) =>{
            e.preventDefault();
            reset();
        })
    })
</script>