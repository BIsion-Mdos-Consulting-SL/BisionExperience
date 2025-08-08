@extends('layouts.main')
@section('content')
<div class="py-5 mb-5">
    <h1 class="mb-3 text-center mt-2 texto fw-bold">Nuevo invitado</h1>
    <!--MENSAJE DE EXITO-->
    <div>
        @if(session('success'))
        <div class="alert alert-success w-50 mt-3">
            {{session('success')}}
        </div>
        @endif
    </div>

    <!--FORMULARIO NUEVO INVITADO-->
    <form method="POST" action="{{route('invitados.store' , $evento->id)}}" enctype="multipart/form-data"
        class="m-auto mt-5 mb-5 d-flex flex-wrap justify-content-between gap-3" style="width: 70%;" id="formulario">
        @csrf
        <div class="col-12 col-sm-5">
            <!--NOMBRE-->
            <div class="mb-3">
                <label for="nombre" class="form-label fw-bold">Nombre*</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="{{ old('nombre')}}">
            </div>

            <!--APELLIDO-->
            <div class="mb-3">
                <label for="apellido" class="form-label fw-bold">Apellidos*</label>
                <input type="text" class="form-control" name="apellido" id="apellido" value="{{ old('apellido')}}">
            </div>

            <!--EMAIL-->
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email*</label>
                <input type="email" class="form-control" name="email" aria-describedby="emailHelp" id="email" value="{{ old('email')}}">
            </div>

            <!--TELEFONO-->
            <div class="mb-3">
                <label for="telefono" class="form-label fw-bold">Telefono</label>
                <input type="text" class="form-control" name="telefono" value="{{ old('telefono')}}">
            </div>

            <!--EMPRESA-->
            <div class="mb-3">
                <label for="empresa" class="form-label fw-bold">Empresa</label>
                <input type="text" class="form-control" name="empresa" value="{{ old('empresa')}}">
            </div>

            <!--CIF-->
            <div class="mb-3">
                <label for="cif" class="form-label fw-bold">CIF</label>
                <input type="text" class="form-control" name="cif" value="{{ old('cif')}}">
            </div>

            <!--DNI-->
            <div class="mb-3">
                <label for="dni" class="form-label fw-bold">DNI*</label>
                <input type="text" class="form-control" name="dni" id="dni" value="{{ old('dni')}}">
            </div>

            <!--MENSAJE DE ERROR-->
            <div>
                @if(session('error'))
                <div class="alert alert-danger py-1 px-2 mb-2 small" role="alert">
                    {{ session('error') }}
                </div>
                @endif
            </div>
        </div>


        <div class="col-12 col-sm-6">
            <!--CONTENEDOR VEHICULO-PROPIO Y ETIQUETA (MOSTRAR)--->
            <div class="d-flex flex-wrap">
                <!---VEHICULO PROPIO--->
                <div class="mb-5 col-md-8">
                    <label for="vehiculo_prop" class="form-label fw-bold" style="margin-right: 2%">¿Cuenta con un vehiculo propio?</label>
                    <div class="form-check">
                        <input id="btn_si" class="form-check-input" type="radio" value="si" name="vehiculo_prop" {{ old('vehiculo_prop') == 'si' ? 'checked' : '' }}>
                        <label class="form-check-label" for="vehiculo_prop">Sí</label>
                    </div>
                    <div class="form-check">
                        <input id="btn_no" class="form-check-input" type="radio" value="no" name="vehiculo_prop" {{ old('vehiculo_prop') == 'no' ? 'checked' : '' }}>
                        <label class="form-check-label" for="vehiculo_prop">No</label>
                    </div>
                </div>

                <!----ETIQUETA (MOSTRAR)----->
                <div id="etiqueta-container" style="display: none;" class="mx-md-2">
                    <label for="etiqueta" class="form-label fw-bold">Etiqueta</label>

                    <div class="div_etiqueta form-check">
                        <input class="form-check-input" type="radio" value="B" name="etiqueta" {{ old('etiqueta') == 'B' ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta">B</label>
                    </div>

                    <div class="div_etiqueta form-check">
                        <input class="form-check-input" type="radio" value="C" name="etiqueta" {{ old('etiqueta') == 'C' ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta">C</label>
                    </div>

                    <div class="div_etiqueta form-check">
                        <input class="form-check-input" type="radio" value="ECO" name="etiqueta" {{ old('etiqueta') == 'ECO' ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta">ECO</label>
                    </div>

                    <div class="div_etiqueta form-check">
                        <input class="form-check-input" type="radio" value="0" name="etiqueta" {{ old('etiqueta') == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta">0</label>
                    </div>
                </div>
            </div>

            <!---VEHICULO EMPRESA--->
            <div class="mb-3">
                <label for="vehiculo_emp" class="form-label fw-bold">¿Cuenta con un vehiculo de empresa?</label>
                <div class="form-check">
                    <input id="btn_si_emp" class="form-check-input" type="radio" value="si" name="vehiculo_emp" {{ old('vehiculo_emp') == 'si' ? 'checked' : '' }}>
                    <label class="form-check-label" for="vehiculo_emp">Sí</label>
                </div>
                <div class="form-check">
                    <input id="btn_no_emp" class="form-check-input" type="radio" value="no" name="vehiculo_emp" {{ old('vehiculo_emp') == 'no' ? 'checked' : '' }}>
                    <label class="form-check-label" for="vehiculo_emp">No</label>
                </div>
            </div>

            <!---JS ETIQUETA--->
            <script>
                const boton_si = document.getElementById('btn_si');
                const boton_no = document.getElementById('btn_no');
                const boton_si_emp = document.getElementById('btn_si_emp');
                const boton_no_emp = document.getElementById('btn_no_emp');
                const etiquetaContainer = document.getElementById('etiqueta-container');

                function mostrar() {
                    if (boton_si.checked || boton_si_emp.checked) {
                        etiquetaContainer.style.display = "block";
                    } else {
                        etiquetaContainer.style.display = "none";
                    }
                }

                boton_si.addEventListener('click', () => {
                    boton_no_emp.checked = true;
                    mostrar();
                })

                boton_si_emp.addEventListener('click', () => {
                    boton_no.checked = true;
                    mostrar();
                })

                boton_no.addEventListener('click', () => {
                    mostrar();
                });

                boton_no_emp.addEventListener('click', () => {
                    mostrar();
                })
            </script>

            <!---FECHA CARNET DE CONDUCIR--->
            <div class="mb-3">
                <label for="carnet_caducidad" class="form-label fw-bold">Fecha caducidad carnet conducir*</label>
                <input type="date" class="form-control" name="carnet_caducidad" id="carnet" value="{{ old('carnet_caducidad')}}">
            </div>

            <!---KAM--->
            <div class="mb-3">
                <label for="kam" class="form-label fw-bold">KAM</label>
                <input type="text" class="form-control" name="kam" value="{{ old('kam')}}">
            </div>

            <!---INTOLERANCIA ALIMENTARIA--->
            <div class="mb-3">
                <label for="intolerancia" class="form-label fw-bold">¿Cuenta con alguna intolerancia alimentaria?</label>
                <input type="text" class="form-control" name="intolerancia" value="{{ old('intolerancia')}}">
            </div>

            <!--PREFERENCIAS-->
            <div class="mb-5">
                <label for="preferencia" class="form-label fw-bold">¿Cuál es su preferencia?</label>
                <div class="d-flex flex-wrap gap-5 justify-content-center">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="carne" name="preferencia" {{ old('preferencia') == 'carne' ? 'checked' : '' }}>
                        <label class="form-check-label" for="preferencia">Carne</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="pescado" name="preferencia" {{ old('preferencia') == 'pescado' ? 'checked' : '' }}>
                        <label class="form-check-label" for="preferencia">Pescado</label>
                    </div>
                </div>
            </div>

            <!---PROTECCION DE DATOS--->
            <div class="form-check mb-3 gap-2" style="display: flex; justify-content: flex-end;">
                <input class="form-check-input" type="checkbox" value="1" name="proteccion_datos" id="proteccion_datos" {{ old('proteccion_datos') ? 'checked' : '' }} required>
                <label class="form-check-label" for="proteccion_datos">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalDatos">Protección de Datos</a>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end;" class="gap-2">
                <!--BOTON GUARDA EVENTO-->
                <button type="submit" id="btn_enviar" class="btn_color">Guardar</button>
                <!--BOTON VUELVE DASHBOARD-->
                <a href="{{route('invitados.index' , $evento->id)}}" class="btn_secundario text-decoration-none">Volver</a>
            </div>
        </div>
    </form>
</div>

<!--FOOTER--->
<footer class="footer">
    <img class="m-auto" src="{{asset('images/footer_bision.png')}}" style="width: 200px;">
</footer>
@include('invitados.proteccion_datos')
@endsection

<!---VALIDACION FORMULARIO--->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById("formulario");
        const nombre = document.getElementById("nombre");
        const apellido = document.getElementById("apellido");
        const email = document.getElementById("email");
        const dni = document.getElementById("dni");
        const carnet = document.getElementById("carnet");

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            let valido = true;

            //NOMBRE
            if (nombre.value.trim() === "") {
                nombre.classList.add("validacion-mal")
                valido = false;
            } else {
                nombre.classList.remove("validacion-mal");
                nombre.classList.add('validacion-bien')
            }

            //APELLIDO
            if(apellido.value.trim() === ""){
                apellido.classList.add("validacion-mal");
                valido = false;
            } else{
                apellido.classList.remove("validacion-mal");
                apellido.classList.add("validacion-bien");
            }

            //EMAIL
            if(email.value.trim() === ""){
                email.classList.add("validacion-mal");
                valido = false;
            } else{
                email.classList.remove("validacion-mal");
                email.classList.add("validacion-bien");
            }

            //DNI
            if(dni.value.trim() === ""){
                dni.classList.add("validacion-mal");
                valido = false;
            } else{
                dni.classList.remove("validacion-mal");
                dni.classList.add("validacion-bien");
            }

            //CARNET DE CONDUCIR
            if(carnet.value.trim() === ""){
                carnet.classList.add("validacion-mal");
                valido = false;
            } else{
                carnet.classList.remove("validacion-mal");
                carnet.classList.add("validacion-bien");
            }

            if(valido){
                form.submit();
            }
        });

        [nombre , apellido , email , dni , carnet].forEach(input =>{
            input.addEventListener('input' , function(){
                if(input.value.trim() !== ""){
                    input.classList.remove("validacion-mal");
                }
            })
        });
    })
</script>