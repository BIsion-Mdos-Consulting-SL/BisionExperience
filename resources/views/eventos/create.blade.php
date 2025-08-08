<!--EXTENDEMOS MAIN , RECOGEMOS DE LA CARPETA-->
@extends('layouts.main')
<!--LLLAMAMOS AL YIELD (CONTENT)-->
@section('content')
<div class="py-5 mb-5">
    <h1 class="mb-3 texto text-center fw-bold mt-2">Nuevo evento</h1>
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

    <!---FORMULARIO CREAR EVENTO-->
    <form method="POST" id="form" action="{{ route('eventos.store')}}" class="m-auto mt-5 mb-5 d-block d-sm-block d-md-block" style="width: 70%;" enctype="multipart/form-data">
        @csrf
        <!-- NOMBRE -->
        <div class="mb-3">
            <label for="nombre" class="form-label fw-bold ">Nombre*</label>
            <input type="text" class="form-control validar" name="nombre" placeholder="Nombre">
        </div>

        <!-- MARCA -->
        <div class="mb-3">
            <label for="marca" class="fw-bold mb-2">Marca* (Para seleccionar más de una opción, pulsa la tecla Control)</label>
            <select class="form-select validar" name="marca[]" multiple>
                @if(isset($marcas))
                @foreach($marcas->sortBy('nombre') as $marca)
                <option value="{{$marca->id}}">{{$marca->nombre}}</option>
                @endforeach
                @endif
            </select>
        </div>

        <!-- FECHA -->
        <div class="d-block d-sm-flex flex-wrap justify-content-between gap-1">
            <div class="mb-3 col-12 col-sm-6">
                <label for="fecha" class="form-label fw-bold ">Fecha*</label>
                <input type="date" class="form-control validar" name="fecha">
            </div>

            <!-- HORA -->
            <div class="col-12 col-sm-5 mb-3">
                <label for="hora" class="form-label fw-bold ">Hora*</label>
                <select class="form-select validar" name="hora">
                    <option value="" disabled selected>Selecciona hora</option>
                    <option value="09:00">09:00</option>
                    <option value="09:30">09:30</option>
                    <option value="10:00">10:00</option>
                    <option value="10:30">10:30</option>
                    <option value="11:00">11:00</option>
                </select>
            </div>
        </div>

        <!-- LUGAR DEL EVENTO -->
        <div class="d-flex flex-wrap justify-content-between gap-1">
            <div class="mb-3 col-12 col-sm-6">
                <label for="lugar_evento" class="form-label fw-bold ">Lugar del evento*</label>
                <input type="text" class="form-control validar" name="lugar_evento" placeholder="Lugar del evento">
            </div>

            <!-- TIPO EVENTO -->
            <div class="col-12 col-sm-5 mb-3">
                <label for="tipo_evento" class="form-label fw-bold ">Tipo evento*</label>
                <select class="form-select validar" name="tipo_evento">
                    <option value="" disabled selected>Selecciona tipo evento</option>
                    @foreach($tipo_evento->sortBy('nombre') as $tipo)
                    <option value="{{ $tipo->nombre }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- COSTE EVENTO -->
        <div class="d-flex flex-wrap justify-content-between gap-1">
            <div class="mb-3 col-12  col-sm-6">
                <label for="coste_evento" class="form-label fw-bold ">Coste evento* (Los decimales deben indicarse con un punto. Ej: 23.17)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-currency-euro"></i></span>
                    <input type="text" id="coste_evento" class="form-control validar" name="coste_evento" placeholder="Coste evento" oninput="calcular_media()">
                </div>
            </div>

            <!-- AFORO MÁXIMO -->
            <div class="col-12 col-sm-5 mb-3">
                <label for="aforo" class="form-label fw-bold">Aforo máximo*</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-raised-hand"></i></span>
                    <input type="text" id="aforo_maximo" class="form-control validar" name="aforo" placeholder="Aforo máximo" oninput="calcular_media()">
                </div>
            </div>
        </div>

        <!-- COSTE UNITARIO -->
        <div class="mb-3">
            <label for="coste_unitario" class="form-label fw-bold ">Coste unitario*</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-currency-euro"></i></span>
                <input type="text" id="coste_unitario" class="form-control" name="coste_unitario" placeholder="Coste unitario">
            </div>
        </div>

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

        <div class="d-flex flex-wrap justify-content-between gap-1">
            <!-- ENLACE -->
            <div class="mb-3 col-12 col-sm-6">
                <label for="enlace" class="form-label fw-bold ">Enlace</label>
                <input type="text" class="form-control" name="enlace" placeholder="Enlace">
            </div>

            <!-- DOCUMENTACIÓN -->
            <div class="col-12 col-sm-5 mb-3">
                <label for="documentacion" class="form-label fw-bold ">Documentación (PDF, Word, JPG o PNG)</label>
                <input type="file" class="form-control" name="documentacion" accept=".pdf, .doc, .docx, .jpg, .png">
            </div>
        </div>

        <!-- IMAGEN -->
        <div class="mb-3">
            <label for="imagen" class="form-label fw-bold ">Imagen (JPG, PNG)*</label>
            <input type="file" class="form-control validar" name="imagen" accept=".jpg, .png" placeholder="Selecciona imagen">
        </div>

        <!-- TEXTO INVITACIÓN -->
        <div class="w-100">
            <label for="texto_invitacion" class="form-label fw-bold ">Texto invitación*</label>
            <br>
            <textarea name="texto_invitacion" class="form-control validar mb-3"></textarea>
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
            
            if(valido){
                form.submit();
            }
        });

        inputs.forEach(input =>{
            input.addEventListener('input' , function(){
                if(input.value.trim() !== ""){
                    input.classList.remove("validacion-mal");
                }
            })
        })
    });
</script>