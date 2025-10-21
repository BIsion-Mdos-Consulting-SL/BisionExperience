@extends('layouts.coches')
@section('content')
<x-app-layout>
    <div class="fondo_titulo">
        INFORMACION DE LOS COCHES
    </div>
    <div class="fondo_principal py-5 mb-sm-5">
        @if($coches->count())
        @foreach($coches as $coche)
        <div class="card-coche text-center">
            <img src="{{ asset('storage/'.$coche->foto_vehiculo) }}"
                alt="Coche {{ $coche->nombre ?? 'Sin nombre' }}"
                class="img-coche">
            <p class="fw-bold mt-3">{{$coche->marca}} - {{$coche->modelo}} {{$coche->version}}</p>
        </div>
        @endforeach
        @else
        <p class="text-center my-4">No hay coches registrados para este evento</p>
        @endif
    </div>
    <footer class="footer">
        <img class="m-auto" src="{{ asset('images/footer_bision.png') }}" style="width: 200px;">
    </footer>
</x-app-layout>
@endsection