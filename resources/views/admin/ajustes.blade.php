@extends('layouts.admin')
@section('content')
<x-app-layout>
    <div class="fondo_principal">
        @if(session('success') || session('error'))
        <div class="position-fixed top-0 end-0 p-3" style="margin-top: 80px; z-index:1050;">
            <div
                class="alert shadow alert-dismissible fade show 
                {{ session('success') ? 'alert-success' : 'alert-danger' }}"
                role="alert">
                {{ session('success') ?? session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        <div class="hero">
            <div class="menu">
                {{-- INFO PARADAS --}}
                <a href="#" class="fondo_botones" data-bs-toggle="modal" data-bs-target="#modalParada">
                    <img src="{{ asset('storage/images/info.png') }}" alt="">
                    <p>Paradas</p>
                </a>

                {{-- INFO COCHES --}}
                <a href="#" class="fondo_botones" data-bs-toggle="modal" data-bs-target="#modalCoche">
                    <img src="{{ asset('storage/images/info_coches.png') }}" alt="">
                    <p>Coches</p>
                </a>

                {{-- INFO RESTAURANTES --}}
                <a href="#" class="fondo_botones" data-bs-toggle="modal" data-bs-target="#modalRestaurante">
                    <img src="{{ asset('storage/images/info_restaurante.jpg') }}" alt="">
                    <p>Restaurante</p>
                </a>

                {{-- INFO BANNER --}}
                <a href="#" class="fondo_botones" data-bs-toggle="modal" data-bs-target="#modalBanner">
                    <img src="{{asset('storage/images/info_banner.jpg')}}" alt="">
                    <p>Partners</p>
                </a>

                <a href="#" class="fondo_botones" data-bs-toggle="modal" data-bs-target="#modalTiming">
                    <img src="{{asset('storage/images/timing.jpg')}}" alt="">
                    <p>Timing</p>
                </a>
            </div>
        </div>
    </div>
    @include('admin.modals.modal_parada', ['evento' => $evento])
    @include('admin.modals.modal_coche' , ['evento' => $evento , 'coches' => $coches])
    @include('admin.modals.modal_restaurante' , ['evento' => $evento , 'restaurante' => $restaurante])
    @include('admin.modals.modal_banner' , ['evento' => $evento])
    @include('admin.modals.modal_timing' , ['evento' => $evento])
</x-app-layout>
<footer class="footer">
    <img class="m-auto" src="{{ asset('images/footer_bision.png') }}" style="width: 200px;">
</footer>
@endsection