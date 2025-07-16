@extends('layouts.error')
@section('content')
<div class="d-flex justify-content-center align-items-center p-3" style="min-height: 100vh;">
    <div class="text-center">
        <div>
            <img class="col-6 col-sm-4 col-md-3 col-lg-2" src="{{ asset('images/logo.png') }}">
            <img class="col-6 col-sm-4 col-md-3 col-lg-2" src="{{ asset('images/footer_bision.png') }}">
        </div>
        <div>
            <p class="text-white mb-4">Acceso no autorizado (Error 401)
                No tienes permiso para acceder a esta página.</p>
        </div>
        <!----REDIRECCION A RUTA LOGIN---->
        <a href="{{ route('login') }}" class="btn_secundario text-decoration-none mt-5">Iniciar sesión</a>
    </div>
</div>
<!--FOOTER--->
<footer class="footer">
    <img class="m-auto" src="{{asset('images/footer_bision.png')}}" style="width: 200px;">
</footer>
@endsection