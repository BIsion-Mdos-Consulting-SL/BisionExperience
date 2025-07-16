@extends('layouts.main')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
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
            <form method="POST" action="{{ route('coches.update' , $coches->id)}}" class="m-auto mt-5 mb-5 d-block d-sm-block d-md-block" style="width: 80%;">
                @csrf
                @method('PUT')
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