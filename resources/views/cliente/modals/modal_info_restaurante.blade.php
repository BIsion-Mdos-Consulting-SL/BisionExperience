<div class="modal fade" id="modalRestaurante" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-4">
            @if(isset($restaurante))
            <div class="modal-header border-0 pb-0">
                <h1 class="fw-bold m-0" style="color: black; font-size: xx-large;">
                    {{ $restaurante->nombre }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <hr class="mt-2 mb-4" style="border-top: 2px solid #e0e0e0;">

            <div class="modal-body pt-0 d-flex flex-column gap-4">
                <div class="p-2">
                    <img src="{{ Storage::url($restaurante->foto_restaurante) }}"
                        class="img-fluid w-100 rounded" style="height: 350px;"
                        alt="Foto del restaurante">
                </div>

                <div class="mx-auto w-100">
                    <p class="mb-3"
                        style="color:black; font-style:italic; font-size:medium; text-align:justify;">
                        {{ $restaurante->descripcion }}
                    </p>

                    <div class="d-flex justify-content-end">
                        <a href="{{ $restaurante->enlace }}"
                            class="p-2 rounded d-inline-flex align-items-center gap-2"
                            style="background-color:#05072e; color:#fff;">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Ver mapa</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>