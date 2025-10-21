<div class="modal fade" id="modalRestaurante" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-4">
            @if(isset($restaurante))
            <div class="modal-header">
                <h1 class="fw-bold" style="color: black; font-size: xx-large;">{{$restaurante->nombre}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-wrap gap-5">

                <div>
                    <img src="{{ Storage::url($restaurante->foto_restaurante) }}" class="mt-2 w-60">
                </div>
                <div>
                    <p style="color: black; font-style: italic; font-size: medium;">{{$restaurante->descripcion}}</p>
                </div>
                <div class="d-flex ms-auto">
                    <a href="{{ $restaurante->enlace }}"
                        class="p-2 rounded d-inline-flex align-items-center gap-2 ms-auto"
                        style="background-color:#05072e; color:#fff;">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Ver mapa</span>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>