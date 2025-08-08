@extends('layouts.error')
@section('content')
<div class="d-flex justify-content-center align-items-center p-3 flex-column" style="min-height: 100vh; position: relative; padding-bottom: 100px; box-sizing: border-box;">
    <div class="text-center">
        <div style="display: flex; flex-wrap: wrap; justify-content: center;">
            <img src="{{ asset('images/logo.png') }}"
                style="width: 70%; max-width: 150px; margin: 10px;"
                class="d-block d-sm-inline-block">
            <img src="{{ asset('images/footer_bision.png') }}"
                style="width: 70%; max-width: 150px; margin: 10px;"
                class="d-block d-sm-inline-block">
        </div>
        <div>
            <p class="text-white mb-4">Mensaje enviado con éxito al invitado.</p>
        </div>
        @if(isset($evento))
        <a href="{{ route('invitados.index', $evento->id) }}" class="btn_secundario text-decoration-none mt-5">Volver</a>
        @endif
    </div>
</div>

<div style="width: 100%; text-align: center; background-color: #05072E; height: auto; position: fixed; bottom: 0; left: 0; z-index: 100;">
    <img src="{{ asset('images/footer_bision.png') }}" alt="Footer Logo" style="width: 200px; max-width: 80%; padding: 10px;">
</div>
@endsection