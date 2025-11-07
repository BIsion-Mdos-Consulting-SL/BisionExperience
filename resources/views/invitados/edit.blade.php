@extends('layouts.main')
@section('content')
<div class="py-5 mb-5">
    <h1 class="mb-3 text-center mt-3 texto fw-bold">Editar invitado</h1>
    <!--MENSAJE DE EXITO-->
    <div>
        @if(session('success'))
        <div class="alert alert-success w-50 mt-3">
            {{session('success')}}
        </div>
        @endif
    </div>

    @php
    $vehiculoProp = old('vehiculo_prop' , $pivot->vehiculo_prop ?? $invitados->vehiculo_prop ?? null);
    $vehiculoEmp = old('vehiculo_emp' , $pivot->vehiculo_emp ?? $invitados->vehiculo_emp ?? null);
    $etiqueta = old('etiqueta' , $pivot->etiqueta ?? $invitados->etiqueta ?? null);
    $etiqueta2 = old('etiqueta_2' , $pivot->etiqueta_2 ?? $invitados->etiqueta_2 ?? null);
    $proteccion_datos = old('proteccion_datos' , $pivot->proteccion_datos ?? $invitados->proteccion_datos ?? null);
    $kam = old('kam' , $pivot->kam ?? $invitados->kam ?? null);
    @endphp

    <!--FORMULARIO NUEVO INVITADO-->
    <!---ENCTYPE SE PASA PORQUE RECOGEREMOS ARCHIVOS COMO IMAGENES , ETC.---->
    <form method="POST"
        id="formulario"
        action="{{ route('invitados.update', [$eventoId, $invitados->id]) }}"
        enctype="multipart/form-data"
        class="m-auto mt-5 mb-5 d-flex flex-wrap justify-content-between gap-3" style="width: 70%;">
        @php($p = $pivot ?? null)
        @csrf
        @method('PUT')
        <div class="col-12 col-sm-5">
            <!--NOMBRE-->
            <div class="mb-3">
                <label for="nombre" class="form-label fw-bold">Nombre*</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $p->nombre ?? $invitados->nombre) }}">
            </div>

            <!--APELLIDO-->
            <div class="mb-3">
                <label for="apellido" class="form-label fw-bold">Apellidos*</label>
                <input type="text" class="form-control" id="apellido" name="apellido" value="{{ old('apellido', $p->apellido ?? $invitados->apellido) }}">
            </div>

            <!--EMAIL-->
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email*</label>
                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" value="{{ old('email', $p->email ?? $invitados->email) }}">
            </div>

            <!--TELEFONO-->
            <div class="mb-3">
                <label for="telefono" class="form-label fw-bold">Telefono</label>
                <input type="text" class="form-control" name="telefono" value="{{ old('telefono', $p->telefono ?? $invitados->telefono) }}">
            </div>

            <!--EMPRESA-->
            <div class="mb-3">
                <label for="empresa" class="form-label fw-bold">Empresa</label>
                <input type="text" class="form-control" name="empresa" value="{{ old('empresa', $p->empresa ?? $invitados->empresa) }}">
            </div>

            <!--CIF-->
            <div class="mb-3">
                <label for="cif" class="form-label fw-bold">CIF</label>
                <input type="text" class="form-control" name="cif" value="{{ old('cif', $p->cif ?? $invitados->cif) }}">
            </div>

            <!--DNI-->
            <div class="mb-3">
                <label for="dni" class="form-label fw-bold">DNI*</label>
                <input type="text" class="form-control" id="dni" name="dni" value="{{ old('dni', $p->dni ?? $invitados->dni) }}">
            </div>

            <!--MENSAJE DE ERROR-->
            <div>
                @if(session('error'))
                <div class="alert alert-danger">
                    {{session('error')}}
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
                        <input id="btn_si_prop" class="form-check-input" type="radio" value="si" name="vehiculo_prop"
                            {{ $vehiculoProp === 'si' ? 'checked' : '' }}>
                        <label class="form-check-label" for="vehiculo_prop">Sí</label>
                    </div>
                    <div class="form-check">
                        <input id="btn_no_prop" class="form-check-input" type="radio" value="no" name="vehiculo_prop"
                            {{ $vehiculoProp === 'no' ? 'checked' : '' }}>
                        <label class="form-check-label" for="vehiculo_prop">No</label>
                    </div>
                </div>

                <!----ETIQUETA (MOSTRAR)----->
                <!----PASAMOS UNA CONDICION TERNARIA AL DISPLAY , SI ES SI EN CULQUIERA DE LOS DOS CAMPOS RECOGIDOS SE MUESTRA Y SINO SE COULTA EL CAMPO.---->
                <div id="etiqueta-container_prop"
                    style="display: {{ $vehiculoProp === 'si' ? 'block' : 'none' }};" class="mx-md-2">
                    <label class="form-label fw-bold d-block">Etiqueta</label>

                    <div class="div_etiqueta form-check">
                        <!---RECOGEMOS EL VALOR , SI ES UNA DE LAS OPCIONES QUE ME RECOGA EL VALOR Y SINO QUE LO DEJE EN VACIO EL CHECKED--->
                        <input class="form-check-input" type="radio" value="B" name="etiqueta" {{ $etiqueta === 'B'   ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta">B</label>
                    </div>

                    <div class="div_etiqueta form-check">
                        <input class="form-check-input" type="radio" value="C" name="etiqueta" {{ $etiqueta === 'C'   ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta">C</label>
                    </div>

                    <div class="div_etiqueta form-check">
                        <input class="form-check-input" type="radio" value="ECO" name="etiqueta" {{ $etiqueta === 'ECO' ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta">ECO</label>
                    </div>

                    <div class="div_etiqueta form-check">
                        <input class="form-check-input" type="radio" value="0" name="etiqueta" {{ $etiqueta === '0'   ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta">0</label>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap mt-3">
                <!---VEHICULO EMPRESA--->
                <div class="mb-5 col-md-8">
                    <label for="vehiculo_emp" style="margin-right: 2%;" class="form-label fw-bold">¿Cuenta con un vehiculo de empresa?</label>
                    <div class="form-check">
                        <!---RECOGEMOS EL VALOR , SI ES UNA DE LAS OPCIONES QUE ME RECOGA EL VALOR Y SINO QUE LO DEJE EN VACIO EL CHECKED--->
                        <input id="btn_si_emp" class="form-check-input" type="radio" value="si" name="vehiculo_emp"
                            {{ $vehiculoEmp === 'si' ? 'checked' : '' }}>
                        <label class="form-check-label" for="vehiculo_emp">Sí</label>
                    </div>
                    <div class="form-check">
                        <input id="btn_no_emp" class="form-check-input" type="radio" value="no" name="vehiculo_emp"
                            {{ $vehiculoEmp === 'no' ? 'checked' : '' }}>
                        <label class="form-check-label" for="vehiculo_emp">No</label>
                    </div>
                </div>

                <!----PASAMOS UNA CONDICION TERNARIA AL DISPLAY , SI ES SI EN CULQUIERA DE LOS DOS CAMPOS RECOGIDOS SE MUESTRA Y SINO SE COULTA EL CAMPO.---->
                <div id="etiqueta-container_emp"
                    style="display: {{ $vehiculoEmp === 'si' ? 'block' : 'none' }};" class="mx-md-2">
                    <label class="form-label fw-bold d-block">Etiqueta</label>

                    <div class="div_etiqueta_2 form-check">
                        <!---RECOGEMOS EL VALOR , SI ES UNA DE LAS OPCIONES QUE ME RECOGA EL VALOR Y SINO QUE LO DEJE EN VACIO EL CHECKED--->
                        <input class="form-check-input" type="radio" value="B" name="etiqueta_2" {{ $etiqueta2 === 'B'   ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta_2">B</label>
                    </div>

                    <div class="div_etiqueta_2 form-check">
                        <input class="form-check-input" type="radio" value="C" name="etiqueta_2" {{ $etiqueta2 === 'C'   ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta_2">C</label>
                    </div>

                    <div class="div_etiqueta_2 form-check">
                        <input class="form-check-input" type="radio" value="ECO" name="etiqueta_2" {{ $etiqueta2 === 'ECO' ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta_2">ECO</label>
                    </div>

                    <div class="div_etiqueta_2 form-check">
                        <input class="form-check-input" type="radio" value="0" name="etiqueta_2" {{ $etiqueta2 === '0'   ? 'checked' : '' }}>
                        <label class="form-check-label" for="etiqueta_2">0</label>
                    </div>
                </div>
            </div>

            <!---FECHA CARNET DE CONDUCIR--->
            <div class="mb-3">
                <label for="carnet_caducidad" class="form-label fw-bold">Fecha caducidad carnet conducir*</label>
                <input type="date" class="form-control" id="carnet" name="carnet_caducidad" value="{{ old('carnet_caducidad', optional(\Carbon\Carbon::parse($p->carnet_caducidad ?? $invitados->carnet_caducidad))->format('Y-m-d')) }}">
            </div>

            <!---KAM--->
            <div class="mb-3">
                <label for="kam" class="form-label fw-bold">KAM</label>
                <input type="text" class="form-control" name="kam" value="{{ $kam }}">
            </div>

            <!---INTOLERANCIA ALIMENTARIA--->
            <div class="mb-3">
                <label for="intolerancia" class="form-label fw-bold">¿Cuenta con alguna intolerancia alimentaria?</label>
                <input type="text" class="form-control" name="intolerancia" value="{{ old('intolerancia', $p->intolerancia ?? $invitados->intolerancia) }}">
            </div>

            <!----PREFERENCIA--->
            <div class="mb-5">
                <label for="preferencia" class="form-label fw-bold">¿Cuál es su preferencia?</label>
                <div class="d-flex flex-wrap gap-5 justify-content-center">
                    <div class="form-check">
                        <!---RECOGEMOS EL VALOR , SI ES UNA DE LAS OPCIONES QUE ME RECOGA EL VALOR Y SINO QUE LO DEJE EN VACIO EL CHECKED--->
                        <input class="form-check-input" type="radio" value="carne" name="preferencia" {{ old('preferencia', $p->preferencia ?? $invitados->preferencia) === 'carne'   ? 'checked' : '' }}>
                        <label class="form-check-label" for="preferencia">Carne</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="pescado" name="preferencia" {{ old('preferencia', $p->preferencia ?? $invitados->preferencia) === 'pescado' ? 'checked' : '' }}>
                        <label class="form-check-label" for="preferencia">Pescado</label>
                    </div>
                </div>
            </div>

            <!---PROTECCION DE DATOS--->
            <div class="form-check mb-3 gap-2" style="display: flex; justify-content: flex-end;">
                <input type="checkbox" value="1" name="proteccion_datos" id="proteccion_datos"
                    {{ $proteccion_datos ? 'checked' : '' }}>
                <label class="form-check-label" for="proteccion_datos">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalDatos">Protección de Datos</a>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end;" class="gap-2">
                <!--BOTON GUARDA EVENTO-->
                <button type="submit" class="btn_color">Guardar</button>
                <!--BOTON VUELVE DASHBOARD-->
                <a href="{{ route('invitados.index', $eventoId) }}" class="btn_secundario text-decoration-none">Volver</a>
            </div>
        </div>
    </form>
</div>

<!--FOOTER--->
<footer class="footer">
    <img class="m-auto" src="{{asset('images/footer_bision.png')}}" style="width: 200px;">
</footer>
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
            if (apellido.value.trim() === "") {
                apellido.classList.add("validacion-mal");
                valido = false;
            } else {
                apellido.classList.remove("validacion-mal");
                apellido.classList.add("validacion-bien");
            }

            //EMAIL
            if (email.value.trim() === "") {
                email.classList.add("validacion-mal");
                valido = false;
            } else {
                email.classList.remove("validacion-mal");
                email.classList.add("validacion-bien");
            }

            //DNI
            if (dni.value.trim() === "") {
                dni.classList.add("validacion-mal");
                valido = false;
            } else {
                dni.classList.remove("validacion-mal");
                dni.classList.add("validacion-bien");
            }

            //CARNET DE CONDUCIR
            if (carnet.value.trim() === "") {
                carnet.classList.add("validacion-mal");
                valido = false;
            } else {
                carnet.classList.remove("validacion-mal");
                carnet.classList.add("validacion-bien");
            }

            if (valido) {
                form.submit();
            }
        });

        [nombre, apellido, email, dni, carnet].forEach(input => {
            input.addEventListener('input', function() {
                if (input.value.trim() !== "") {
                    input.classList.remove("validacion-mal");
                }
            })
        });


        const boton_si_prop = document.getElementById('btn_si_prop');
        const boton_no_prop = document.getElementById('btn_no_prop');
        const etiquetaContainerProp = document.getElementById('etiqueta-container_prop');

        function mostrar() {
            if (boton_si_prop.checked) {
                etiquetaContainerProp.style.display = "block";
            } else {
                etiquetaContainerProp.style.display = "none";
            }
        }

        boton_si_prop.addEventListener('click', () => {
            boton_no_prop.checked = false;
            mostrar();
        })

        boton_no_prop.addEventListener('click', () => {
            mostrar();
        })


        const boton_si_emp = document.getElementById('btn_si_emp');
        const boton_no_emp = document.getElementById('btn_no_emp');
        const etiquetaContainerEmp = document.getElementById('etiqueta-container_emp');

        function mostrarEmp() {
            if (boton_si_emp.checked) {
                etiquetaContainerEmp.style.display = "block";
            } else {
                etiquetaContainerEmp.style.display = "none";
            }
        }

        boton_si_emp.addEventListener('click', () => {
            boton_no_emp.checked = false;
            mostrarEmp();
        })

        boton_no_emp.addEventListener('click', () => {
            mostrarEmp();
        })
    })
</script>