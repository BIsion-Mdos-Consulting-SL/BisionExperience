@extends('layouts.cliente')
@section('content')
<x-app-layout>
    <div class="fondo_principal">
        <div class="hero">
            <div class="menu">
                <a href="#" class="fondo_botones" data-bs-toggle="modal" data-bs-target="#modalRuta">
                    <img src="{{ asset('storage/images/info.png') }}" alt="">
                    <p>Info ruta</p>
                </a>

                <a href="{{ route('cliente.info_coches', $evento) }}" class="fondo_botones">
                    <img src="{{ asset('storage/images/info_coches.png') }}" alt="">
                    <p>Info coches</p>
                </a>

                <a href="#" class="fondo_botones" data-bs-toggle="modal" data-bs-target="#modalRestaurante">
                    <img src="{{ asset('storage/images/info_restaurante.jpg') }}" alt="">
                    <p>Info restaurante</p>
                </a>
            </div>
        </div>
    </div>

    <footer class="footer">
        <img class="m-auto" src="{{ asset('images/footer_bision.png') }}" style="width: 200px;">
    </footer>
    @include('cliente.modals.modal_info_ruta' , ['evento' => $evento , 'paradas' => $paradas])
    @include('cliente.modals.modal_info_restaurante' , ['evento' => $evento , 'restaurante' => $restaurante])
</x-app-layout>
@endsection