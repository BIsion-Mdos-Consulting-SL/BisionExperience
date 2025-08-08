@extends('layouts.main')
@section('content')
<div class="py-5 mb-5">
    <h1 class="mb-3 texto text-center fw-bold mt-2">Editar evento</h1>
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

    <form method="POST" id="form" action="{{route('eventos.update' , $evento->id)}}" class="m-auto mt-5 mb-5" style="width: 70%;" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!--NOMBRE-->
        <div class="mb-3">
            <label for="nombre" class="form-label fw-bold">Nombre*</label>
            <input type="text" class="form-control validar" name="nombre" value="{{$evento->nombre}}">
        </div>

        <!--MARCA-->
        <div class="mb-3">
            <label for="marca" class="fw-bold mb-2">Marca* (Para seleccionar mas de una opcion, pulsa la tecla Control)</label>
            <select class="form-select validar" name="marca[]" multiple>
                @if(isset($marcas))
                @foreach($marcas->sortBy('nombre') as $marca)
                <option value="{{$marca->id}}"
                    {{ $evento->marcas->pluck('id')->contains($marca->id) ? 'selected' : '' }}>
                    {{$marca->nombre}}
                </option>
                @endforeach
                @endif
            </select>
        </div>

        <!--FECHA-->
        <div class="d-flex flex-wrap justify-content-between gap-1">
            <div class="mb-3 col-12 col-sm-6">
                <label for="fecha" class="form-label fw-bold">Fecha*</label>
                <input type="date" class="form-control validar" name="fecha" value="{{$evento->fecha->format('Y-m-d')}}">
            </div>

            <!--HORA-->
            <div class="col-12 col-sm-5 mb-3">
                <label for="hora" class="form-label fw-bold">Hora*</label>
                <select class="form-select validar" name="hora">
                    <option disabled>Selecciona hora</option>
                    <option value="09:00" {{ $evento->hora->format('H:i') == '09:00' ? 'selected' : '' }}>09:00</option>
                    <option value="09:30" {{ $evento->hora->format('H:i') == '09:30' ? 'selected' : '' }}>09:30</option>
                    <option value="10:00" {{ $evento->hora->format('H:i') == '10:00' ? 'selected' : '' }}>10:00</option>
                    <option value="10:30" {{ $evento->hora->format('H:i') == '10:30' ? 'selected' : '' }}>10:30</option>
                    <option value="11:00" {{ $evento->hora->format('H:i') == '11:00' ? 'selected' : '' }}>11:00</option>
                </select>
            </div>
        </div>

        <!--LUGAR DEL EVENTO-->
        <div class="d-flex flex-wrap justify-content-between gap-1">
            <div class="mb-3 col-12 col-sm-6">
                <label for="lugar_evento" class="form-label fw-bold">Lugar del evento*</label>
                <input type="text" class="form-control validar" name="lugar_evento" value="{{$evento->lugar_evento}}">
            </div>

            <!--TIPO EVENTO-->
            <div class="col-12 col-sm-5 mb-3">
                <label for="tipo_evento" class="form-label fw-bold">Tipo evento*</label>
                <select class="form-select validar" name="tipo_evento">
                    <option value="" disabled {{old('tipo_evento' , $evento->tipo_evento ?? '')}}>Selecciona tipo evento</option>
                    @if(isset($tipo_evento))
                    @foreach($tipo_evento->sortBy('nombre') as $tipo)
                    <option value="{{ $tipo->nombre }}" {{ old('tipo_evento', $evento->tipo_evento ?? '') == $tipo->nombre ? 'selected' : '' }}>{{$tipo->nombre}}</option>
                    @endforeach
                    @endif
                </select>
            </div>
        </div>

        <!--COSTE EVENTO-->
        <div class="d-flex flex-wrap justify-content-between gap-1">
            <div class="mb-3 col-12 col-sm-6">
                <label for="coste_evento" class="form-label fw-bold">Coste evento* (Los decimales deben indicarse con un punto. Ej: 23.17)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-currency-euro"></i></span>
                    <input type="text" id="coste_evento" class="form-control validar" name="coste_evento" value="{{$evento->coste_evento}}" oninput="calcular_media()">
                </div>
            </div>

            <!--AFORO MAXIMO-->
            <div class="col-12 col-sm-5 mb-3">
                <label for="aforo" class="form-label fw-bold">Aforo maximo*</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-raised-hand"></i></span>
                    <input type="text" id="aforo_maximo" class="form-control validar" name="aforo" value="{{$evento->aforo}}" oninput="calcular_media()">
                </div>
            </div>
        </div>

        <!--COSTE UNITARIO-->
        <div class="mb-3">
            <label for="coste_unitario" class="form-label fw-bold">Coste unitario*</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-currency-euro"></i></span>
                <input type="text" id="coste_unitario" class="form-control validar" name="coste_unitario" value="{{$evento->coste_unitario}}">
            </div>
        </div>
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

        <!--ENLACE-->
        <div class="d-flex flex-wrap justify-content-between gap-1">
            <div class="mb-3 col-12 col-sm-6">
                <label for="enlace" class="form-label fw-bold">Enlace</label>
                <input type="text" class="form-control" name="enlace" value="{{$evento->enlace}}">
            </div>

            <!-- Documentación -->
            <div class="col-12 col-sm-5 mb-3">
                <label for="documentacion" class="form-label fw-bold">Documentación (PDF, Word, JPG o PNG)</label>
                <input type="file" class="form-control" name="documentacion" accept=".pdf, .doc, .docx, .jpg, .png">
                @if($evento->documentacion)
                <div class="mt-2">
                    <!-- Verificar si el archivo es una imagen -->
                    @if(in_array(pathinfo($evento->documentacion, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                    <img src="{{ asset('storage/' . $evento->documentacion) }}" alt="Documento actual" style="max-width: 200px; max-height: 200px;">
                    @else
                    <!-- Enlace para descargar o ver el archivo -->
                    <a href="{{ asset('storage/' . $evento->documentacion) }}" target="_blank" class="d-block mt-2">Ver documento actual</a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!--TEXTO INVITACION-->
        <div class="w-100">
            <label for="texto_invitacion" class="form-label fw-bold">Texto invitacion*</label>
            <br>
            <textarea name="texto_invitacion" class="form-control validar mb-3">{{$evento->texto_invitacion}}</textarea>
        </div>

        <div class="text-end">
            <!-- BOTÓN GUARDAR EVENTO -->
            <button type="submit" class="btn_color">Guardar</button>
            <!-- BOTÓN VOLVER AL DASHBOARD -->
            <a href="{{ route('eventos.index') }}" class="btn_secundario text-decoration-none">Volver</a>
        </div>
    </form>
</div>
<!--FOOTER--->
<footer class="footer">
    <img class="m-auto" src="{{asset('images/footer_bision.png')}}" style="width: 200px;">
</footer>
@endsection

<script>
    //FUNCION VALIDACION
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById("form");
        const inputs = document.querySelectorAll(".validar");

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let valido = true;
            inputs.forEach(function(input) {
                if (!input.value || input.value.trim() === "") {
                    input.classList.add("validacion-mal");
                    valido = false;
                } else {
                    input.classList.remove("validacion-mal");
                    input.classList.add("validacion-bien");
                }
            });

            if (valido) {
                form.submit();
            }
        });

        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (input.value.trim() !== "") {
                    input.classList.remove("validacion-mal");
                }
            })
        })
    });
</script>