@extends('layouts.main')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-3 texto text-center fw-bold mt-2">Registro de coche</h1>
            <!-- Aquí va tu formulario -->
            <!---FORMULARIO CREAR EVENTO-->
            <form method="POST" id="form" action="{{ route('coches.store' , $evento->id)}}" class="m-auto mt-5 mb-5 d-block d-sm-block d-md-block" style="width: 80%;">
                @csrf
                <!-- MARCA -->
                <div class="mb-3">
                    <label for="marca" class="form-label fw-bold ">Marca*</label>
                    <input type="text" class="form-control" name="marca" id="marca" value="{{ old('marca') }}"  placeholder="Introduce marca">
                </div>

                <!-- MODELO -->
                <div class="mb-3">
                    <label for="modelo" class="form-label fw-bold ">Modelo*</label>
                    <input type="text" class="form-control" name="modelo" id="modelo" value="{{ old('modelo') }}"  placeholder="Introduce modelo">
                </div>

                <!-- VERSION -->
                <div class="mb-3">
                    <label for="version" class="form-label fw-bold ">Version*</label>
                    <input type="text" class="form-control" name="version" id="version" value="{{ old('version') }}"  placeholder="Introduce version">
                </div>

                <!-- MATRICULA -->
                <div class="mb-3">
                    <label for="matricula" class="form-label fw-bold">Matricula*</label>
                    <input type="text" class="form-control" name="matricula" value="{{ old('matricula') }}"  placeholder="Introduce matricula" id="matricula">
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
                    <input type="text" class="form-control" name="kam" id="kam" value="{{ old('kam') }}"  placeholder="Introduce kam">
                </div>

                <!---ASISTE--->
                <div class="form-check mb-3 d-flex justify-content-start align-items-center">
                    <input type="hidden" name="asiste" value="0">
                    <input class="form-check-input ms-2" type="checkbox" name="asiste" id="asiste" value="{{ old('asiste') }}"  value="1">
                    <label class="form-check-label ms-2 fw-bold" for="asiste">Llave</label>
                </div>

                <div class="text-end">
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