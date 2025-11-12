<div class="modal fade" id="modalRestaurante" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="d-flex justify-content-end mb-1">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- TABS -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold"
                        id="rest-crear-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#rest-crear"
                        type="button" role="tab" style="color: #05072e;">Crear</button>
                </li>
                <li class="nav-item">
                    @if($restaurante)
                    <button class="nav-link fw-bold"
                        id="rest-editar-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#rest-editar"
                        type="button" role="tab" style="color: #05072e;">Editar</button>
                    @else
                    <button class="nav-link fw-bold disabled" type="button" tabindex="-1" aria-disabled="true">Editar</button>
                    @endif
                </li>
            </ul>

            <!-- CONTENIDO -->
            <div class="tab-content mt-3">
                <!-- CREAR -->
                <div class="tab-pane fade show active" id="rest-crear" role="tabpanel" aria-labelledby="rest-crear-tab">
                    <div class="modal-header">
                        <h5 class="fs-2 fw-bold" style="color: #05072e;">Añadir Restaurante</h5>
                    </div>
                    <form method="POST" action="{{ route('store.restaurantes', $evento) }}" class="mt-4" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold" style="color: black;">Nombre</label>
                            <input name="nombre" type="text" class="form-control border rounded" value="{{ old('nombre') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold" style="color: black;">Descripción</label>
                            <textarea name="descripcion" rows="5" class="form-control border rounded">{{ old('descripcion') }}</textarea>
                        </div>

                        <!---IMAGEN--->
                        <div class="mb-3">
                            <label class="form-label fw-bold" style="color: black;">Imagen* (jpg, jpeg, png)</label>
                            <input id="foto_restaurante_crear" name="foto_restaurante_crear" type="file"
                                class="form-control border rounded p-1" accept="image/*">

                            <!-- PREVIEW IMAGEN CREAR -->
                            <img id="preview-crear" class="mt-2 rounded d-none"
                                style="max-height: 120px; margin: auto;" alt="Vista previa">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold" style="color: black;">Enlace</label>
                            <input name="enlace" type="text" class="form-control border rounded" value="{{ old('enlace') }}">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="p-2 rounded" style="background:#05072e;color:#fff">Guardar</button>
                        </div>
                    </form>
                </div>

                <!-- EDITAR -->
                <div class="tab-pane fade" id="rest-editar" role="tabpanel" aria-labelledby="rest-editar-tab">
                    <div class="modal-header">
                        <h5 class="fs-2 fw-bold" style="color: #05072e;">Editar Restaurante</h5>
                    </div>

                    @if($restaurante)
                    <form method="POST" action="{{ route('evento.restaurante.update', ['evento' => $evento, 'restaurante' => $restaurante]) }}" class="mt-4" enctype="multipart/form-data" id="form-editar">
                        @csrf
                        @method('PUT')

                        <!-- NOMBRE -->
                        <div class="mb-3">
                            <label for="rest-nombre" class="form-label fw-bold" style="color: black;">Nombre</label>
                            <input id="rest-nombre" name="nombre" type="text" class="form-control border rounded"
                                value="{{ old('nombre', $restaurante->nombre) }}">
                        </div>

                        <!-- DESCRIPCIÓN -->
                        <div class="mb-3">
                            <label for="rest-descripcion" class="form-label fw-bold" style="color: black;">Descripción</label>
                            <textarea id="rest-descripcion" name="descripcion" rows="5" class="form-control border rounded">{{ old('descripcion', $restaurante->descripcion) }}</textarea>
                        </div>

                        <!-- FOTO RESTAURANTE -->
                        <div class="mb-3">
                            <label for="foto_restaurante" class="form-label fw-bold" style="color: black;">Imagen* (jpg, jpeg, png)</label>
                            <input id="foto_restaurante_editar" name="foto_restaurante" type="file"
                                accept="image/*" class="form-control border rounded p-1">

                            <!-- PREVIEW IMAGEN EDITAR -->
                            <img id="preview-editar"
                                src="{{ !empty($restaurante->foto_restaurante) ? Storage::url($restaurante->foto_restaurante) : '#' }}"
                                class="mt-2 rounded {{ !empty($restaurante->foto_restaurante) ? '' : 'd-none' }}"
                                style="max-height:120px;margin:auto;" alt="Vista previa">
                        </div>

                        <!-- ENLACE -->
                        <div class="mb-3">
                            <label for="rest-enlace" class="form-label fw-bold" style="color: black;">Enlace</label>
                            <input id="rest-enlace" name="enlace" type="text" class="form-control border rounded"
                                value="{{ old('enlace', $restaurante->enlace) }}">
                        </div>
                    </form>


                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="submit" class="p-2 rounded" style="background:#05072e;color:#fff" form="form-editar">Guardar</button>


                        <form method="POST" action="{{route('eliminarRestaurante' , ['evento' => $evento->id , 'id' => $restaurante->id])}}" class="form-eliminar">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Confirmación eliminar (tu código)
        document.querySelectorAll('.form-eliminar').forEach(formEl => {
            formEl.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: "¿Seguro que quieres eliminar?",
                    icon: "warning",
                    iconColor: "#05072e",
                    showDenyButton: true,
                    denyButtonColor: "#05072e",
                    confirmButtonText: "Sí",
                    confirmButtonColor: "#05072e",
                    denyButtonText: "No"
                }).then(result => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });

        //FUNCION PARA VER IMAGENES.
        function previewImage(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            if (!input || !preview) return;

            input.addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = '#';
                    preview.classList.add('d-none');
                }
            });
        }

        // Inicializa crear y editar
        previewImage('foto_restaurante_crear', 'preview-crear');
        previewImage('foto_restaurante_editar', 'preview-editar');
    });
</script>