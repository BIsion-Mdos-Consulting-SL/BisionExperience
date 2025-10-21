@extends('layouts.main')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-3 texto text-center fw-bold mt-2">Registro de coche</h1>
            <!-- Aquí va tu formulario -->
            <!---FORMULARIO CREAR EVENTO-->
            <form method="POST" id="formulario" action="{{ route('coches.store', $evento->id) }}" class="row g-4 mt-4 mb-5 pb-5" enctype="multipart/form-data">
                @csrf
                <div class="col-12 col-md-6">
                    <!-- MARCA -->
                    <div class="mb-3">
                        <label for="marca" class="form-label fw-bold ">Marca*</label>
                        <input type="text" class="form-control validar" name="marca" value="{{ old('marca') }}" placeholder="Introduce marca">
                    </div>

                    <!-- MODELO -->
                    <div class="mb-3">
                        <label for="modelo" class="form-label fw-bold ">Modelo*</label>
                        <input type="text" class="form-control validar" name="modelo" value="{{ old('modelo') }}" placeholder="Introduce modelo">
                    </div>

                    <!-- VERSION -->
                    <div class="mb-3">
                        <label for="version" class="form-label fw-bold ">Version*</label>
                        <input type="text" class="form-control validar" name="version" value="{{ old('version') }}" placeholder="Introduce version">
                    </div>

                    <!-- MATRICULA -->
                    <div class="mb-3">
                        <label for="matricula" class="form-label fw-bold">Matricula*</label>
                        <input type="text" class="form-control validar" name="matricula" value="{{ old('matricula') }}" placeholder="Introduce matricula">
                    </div>

                    <!--MENSAJE DE ERROR-->
                    <div>
                        @if(session('error'))
                        <div class="alert alert-danger py-1 px-2 mb-2 small" role="alert">
                            {{ session('error') }}
                        </div>
                        @endif
                    </div>

                    <!-- KAM -->
                    <div class="mb-3">
                        <label for="kam" class="form-label fw-bold ">KAM*</label>
                        <input type="text" class="form-control validar" name="kam" value="{{ old('kam') }}" placeholder="Introduce kam">
                    </div>

                    <!---ASISTE--->
                    <div class="form-check mb-3 d-flex justify-content-start align-items-center">
                        <input type="hidden" name="asiste" value="0">
                        <input class="form-check-input ms-2" type="checkbox" name="asiste" id="asiste" value="1" {{old('asiste') ? 'checked' : ''}}>
                        <label class="form-check-label ms-2 fw-bold" for="asiste">Llave</label>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <!---SEGURO COCHE(VIGOR)--->
                    <label for="seguro" class="fw-bold mb-3">¿Seguro del coche en vigor?*</label>
                    <div class="mb-3 d-flex flex-wrap gap-3 text-center">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="seguro" id="radioDefault1" value="1">
                            <label class="form-check-label" for="radioDefault1">
                                Si
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="seguro" id="radioDefault2" value="0">
                            <label class="form-check-label" for="radioDefault2">
                                No
                            </label>
                        </div>
                    </div>

                    <!-- DOCUMENTACION SEGURO -->
                    <div class="mb-3">
                        <label for="documento_seguro" class="form-label fw-bold ">Documentacion del seguro* (pdf, jpg, jpeg, png)</label>
                        <input type="file" class="form-control validar" name="documento_seguro" value="{{ old('documento_seguro') }}" placeholder="Introduce documento_seguro">
                    </div>

                    <!-- FOTO DEL VEHÍCULO -->
                    <div class="mb-3">
                        <label for="foto_vehiculo" class="form-label fw-bold">Foto del vehículo* (pdf, jpg, jpeg, png)</label>

                        <!-- El input está oculto -->
                        <input class="form-control d-none" type="file" id="foto_vehiculo" name="foto_vehiculo" accept="image/*">

                        <!-- El icono que dispara el input -->
                        <div id="preview_container" class="text-center text-md-end" style="cursor: pointer; font-size: clamp(12rem, 20vw, 15rem);">
                            <i class="bi bi-folder-plus" id="preview_icon" title="Selecciona imagen"></i>
                        </div>
                    </div>
                </div>
                
                <div class="w-100 d-flex justify-content-end gap-2">
                    <!-- BOTÓN GUARDAR EVENTO -->
                    <button type="submit" class="btn_color">Guardar</button>
                    <!-- BOTÓN VOLVER AL DASHBOARD -->
                    <a href="{{ route('coches.index' , $evento->id) }}" class="btn_secundario text-decoration-none">Volver</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!--FOOTER--->
<footer class="footer">
    <img class="m-auto" src="{{asset('images/footer_bision.png')}}" style="width: 200px;">
</footer>
@endsection

<script>
    //VALIDACION
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById("formulario");
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

        //SUBIR IMAGEN.
        const input = document.getElementById('foto_vehiculo');
        const previewContainer = document.getElementById('preview_container');
        const previewIcon = document.getElementById('preview_icon');

        // Cuando se hace clic en el contenedor (el ícono)
        previewContainer.addEventListener('click', function() {
            input.click(); // Dispara el input file
        });

        // Cuando se selecciona un archivo
        input.addEventListener('change', function() {
            const file = this.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Reemplaza el ícono con la imagen seleccionada
                    previewContainer.innerHTML = `
                    <img src="${e.target.result}" alt="Vista previa" style="max-width: 400px; max-height: 250px; border-radius: 10px;">
                `;
                };

                reader.readAsDataURL(file);
            } else {
                alert('Por favor selecciona una imagen válida.');
            }
        });
    })
</script>