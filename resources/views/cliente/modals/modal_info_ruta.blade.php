<div class="modal fade" id="modalRuta" tabindex="-1" aria-labelledby="modalRutaLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" style="font-size: 30px; color: black;">{{$evento->nombre}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            @if(!empty($evento->descripcion))
            <p class="lead text-secondary mb-4 mt-3">{{ $evento->descripcion }}</p>
            @else
            <p class="lead text-secondary mb-4 mt-3">{{$evento->texto_invitacion}}</p>
            @endif

            <h4 class="fw-bold mb-3 text-dark" style="font-size: 15px;">Ruta del evento</h4>

            <!---RUTAS DEL EVENTO--->
            @if(isset($paradas) && $paradas->count())
            @foreach($paradas as $parada)
            <div @class([ 'bg-white rounded-4 shadow-sm p-3' , 'mb-3'=> !$loop->last])>
                <div class="d-flex flex-column flex-sm-row align-items-md-center justify-content-between gap-5">
                    <div>
                        <div class="h5 fw-bold mb-1 text-dark">{{ $parada->nombre }}</div>
                        <div class="text-secondary">{{ $parada->descripcion }}</div>
                    </div>
                    @if(!empty($parada->enlace))
                    <a href="{{ $parada->enlace }}" target="_blank" class="fw-semibold p-2 rounded text-center gap-2" title="Ver ruta" style="background-color: #05072e;;">
                    <span aria-hidden="true"><i class="bi bi-geo-alt-fill"></i></span>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
            @else
            <p class="text-center my-4">No hay paradas registradas para este evento 🚏</p>
            @endif

        </div>
    </div>
</div>