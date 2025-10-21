@extends('layouts.cliente')
@section('content')
<x-app-layout>
    <div class="fondo_principal">
        <div class="hero">
            <div class="menu">
                <!---PATROCINADORES--->
                <a href="#" class="fondo_botones" data-bs-toggle="modal" data-bs-target="#modalPatrocinadores">
                    <img src="{{ asset('storage/images/info_banner.jpg') }}" alt="">
                    <p>Partners</p>
                </a>

                <!---TIMING--->
                <a href="#" class="fondo_botones" data-bs-toggle="modal" data-bs-target="#modalTiming">
                    <img src="{{ asset('storage/images/timing.jpg') }}" alt="">
                    <p>Timing</p>
                </a>

                <!---RUTA--->
                <a href="{{route('cliente.ruta')}}" class="fondo_botones">
                    <img src="{{ asset('storage/images/ruta.jpg') }}" alt="">
                    <p>Ruta</p>
                </a>

                <!---PRE-RESERVA--->
                <a href="#" class="fondo_botones" data-bs-toggle="modal" data-bs-target="#modalReserva">
                    <img src="{{ asset('storage/images/reserva.jpg') }}" alt="">
                    <p>Pre Reserva</p>
                </a>

                <!---PRUEBA DINAMICA--->
                <a href="#" class="fondo_botones" data-bs-toggle="modal" data-bs-target="#modalPruebaDinamica">
                    <img src="{{ asset('storage/images/prueba_dinamica.jpg') }}" alt="">
                    <p>Prueba Dinamica</p>
                </a>
            </div>
        </div>
    </div>
    <footer class="footer">
        <img class="m-auto" src="{{asset('images/footer_bision.png')}}" style="width: 200px;">
    </footer>
    @include('cliente.modals.modal_timing')
    @include('cliente.modals.modal_reserva')
    @include('cliente.modals.modal_pruebaDinamica')
    @include('cliente.modals.modal_patrocinadores')
</x-app-layout>
@endsection