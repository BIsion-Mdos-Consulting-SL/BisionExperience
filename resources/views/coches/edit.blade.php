@extends('layouts.main')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-3 texto text-center fw-bold mt-2">Editar coche</h1>
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
            <!-- Aquí va tu formulario -->
            <!---FORMULARIO CREAR EVENTO-->
            <form method="POST" action="{{ route('coches.update' , $coches->id)}}" class="row g-4 mt-4 mb-5 pb-5" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="col-12 col-md-6">
                    <!-- MARCA -->
                    <div class="mb-3">
                        <label for="marca" class="form-label fw-bold ">Marca*</label>
                        <input type="text" class="form-control" name="marca" value="{{$coches->marca}}">
                    </div>

                    <!-- MODELO -->
                    <div class="mb-3">
                        <label for="modelo" class="form-label fw-bold ">Modelo*</label>
                        <input type="text" class="form-control" name="modelo" value="{{$coches->modelo}}">
                    </div>

                    <!-- VERSION -->
                    <div class="mb-3">
                        <label for="version" class="form-label fw-bold ">Version*</label>
                        <input type="text" class="form-control" name="version" value="{{$coches->version}}">
                    </div>

                    <!-- MATRICULA -->
                    <div class="mb-3">
                        <label for="matricula" class="form-label fw-bold ">Matricula*</label>
                        <input type="text" class="form-control" name="matricula" value="{{$coches->matricula}}">
                    </div>

                    <!-- KAM -->
                    <div class="mb-3">
                        <label for="kam" class="form-label fw-bold ">KAM*</label>
                        <input type="text" class="form-control" name="kam" value="{{$coches->kam}}">
                    </div>

                    <!---ASISTE--->
                    <div class="form-check mb-3 d-flex justify-content-start align-items-center">
                        <input type="hidden" name="asiste" value="0">
                        <input class="form-check-input ms-2" type="checkbox" name="asiste" id="asiste" value="1" {{ $coches->asiste ? 'checked' : '' }}>
                        <label class="form-check-label ms-2 fw-bold" for="asiste">Llave</label>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <!---SEGURO COCHE(VIGOR)--->
                    <label for="seguro" class="fw-bold mb-3">¿Seguro del coche en vigor?*</label>
                    <div class="mb-3 d-flex flex-wrap gap-3 text-center">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="seguro" id="radioDefault1" value="1"
                                {{ $coches->seguro == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="radioDefault1">
                                Si
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="seguro" id="radioDefault2" value="0"
                                {{ $coches->seguro == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="radioDefault2">
                                No
                            </label>
                        </div>
                    </div>

                    <!-- DOCUMENTACION SEGURO -->
                    <div class="mb-3">
                        <label for="documento_seguro" class="form-label fw-bold ">Documentacion del seguro*</label>
                        <input type="file" class="form-control validar" name="documento_seguro" value="{{ old('documento_seguro') }}" placeholder="Introduce documento_seguro">


                        @if(isset($coches) && $coches->documento_seguro)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $coches->documento_seguro) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                Ver documento actual
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- FOTO DEL VEHÍCULO -->
                    <div class="mb-3">
                        <label for="foto_vehiculo" class="form-label fw-bold">Foto del vehículo* (pdf, jpg, jpeg, png)</label>

                        <!-- El input está oculto -->
                        <input class="form-control d-none" type="file" id="foto_vehiculo" name="foto_vehiculo" accept="image/*">

                        <!-- El icono que dispara el input -->
                        <div id="preview_container" class="text-center text-md-end" style="cursor: pointer; font-size: clamp(12rem, 20vw, 15rem);">
                            @if(isset($coches) && $coches->foto_vehiculo)
                            <img src="{{ asset('storage/' . $coches->foto_vehiculo)}}" alt="" style="max-width: 350px; max-height: 350px;">
                            @else
                            <i class="bi bi-folder-plus" id="preview_icon" title="Selecciona imagen"></i>
                            @endif
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
    document.addEventListener('DOMContentLoaded', function() {
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