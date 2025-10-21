<div class="modal fade" id="modalParada" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="d-flex justify-content-end mb-1">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!--TABS-->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link fw-bold {{ (!request('parada_id') && !$parada) ? 'active' : '' }}" id="crear-tab" data-bs-toggle="tab" data-bs-target="#crear" type="button" role="tab" style="color: #05072e;">Crear</button>
                </li>
                <li class="nav-item">
                    @if(isset($paradas))
                    <button class="nav-link fw-bold {{ (request('parada_id') || $parada) ? 'active' : '' }}" id="editar-tab" data-bs-toggle="tab" data-bs-target="#editar" type="button" role="tab" style="color: #05072e;">Editar</button>
                    @else
                    <button class="nav-link fw-bold disabled" type="button" tabindex="-1" aria-disabled="true" style="color: #05072e;">Editar</button>
                    @endif
                </li>
            </ul>

            <!--CONTENIDO-->
            <div class="tab-content mt-3">
                <!-- CREAR -->
                <div class="tab-pane fade {{ (!request('parada_id') && !$parada) ? 'show active' : '' }}" id="crear" role="tabpanel" aria-labelledby="crear-tab">
                    <div class="modal-header">
                        <h5 class="fs-2 fw-bold" style="color: #05072e;">Añadir Parada</h5>
                    </div>
                    <form method="POST" action="{{ route('store.paradas', $evento) }}" class="mt-4">
                        @csrf

                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold" style="color: black;">Nombre</label>
                            <input id="nombre" name="nombre" type="text" class="form-control border rounded" value="{{ old('nombre') }}">
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-bold" style="color: black;">Descripción</label>
                            <textarea id="descripcion" name="descripcion" rows="5" class="form-control border rounded">{{ old('descripcion') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="enlace" class="form-label fw-bold" style="color: black;">Enlace</label>
                            <input id="enlace" name="enlace" type="text" class="form-control border rounded" value="{{ old('enlace') }}">
                        </div>

                        <div class="mb-4 col-5">
                            <label for="orden" class="form-label fw-bold" style="color: black;">Orden</label>
                            <input id="orden" name="orden" type="number" min="1" class="form-control border rounded" value="{{ old('orden') }}">
                            @error('orden', 'parada')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="p-2 rounded" style="background-color:#05072e;">Guardar</button>
                        </div>
                    </form>
                </div>

                <!-- EDITAR -->
                <div class="tab-pane fade {{ (request('parada_id') || $parada) ? 'show active' : '' }}" id="editar" role="tabpanel" aria-labelledby="editar-tab">
                    <div class="modal-header">
                        <h5 class="fs-2 fw-bold" style="color:#05072e;">Editar Parada</h5>
                    </div>

                    @if(isset($paradas))
                    <!-- SELECT DE LAS PARADAS -->
                    <form method="GET" action="{{ route('admin.ajustes', $evento) }}" class="mt-3 mb-3">
                        <div class="mb-3">
                            <label for="parada_id" class="form-label fw-bold" style="color:black;" disabled>Selecciona la parada</label>
                            <select id="parada_id" name="parada_id" class="form-select border rounded" onchange="this.form.submit()">
                                <option value="" disabled {{ request('parada_id') ? '' : 'selected' }}>Selecciona una parada</option>
                                @foreach($paradas as $p)
                                <option value="{{ $p->id }}" {{ (string)request('parada_id') === (string)$p->id ? 'selected' : '' }}>
                                    {{ $p->nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    @if($parada)
                    <!-- FORM DE EDICIÓN-->
                    <form id="form-editar-parada" method="POST"
                        action="{{ route('evento.parada.update', ['evento' => $evento, 'parada' => $parada]) }}" class="mt-2">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nombre_edit" class="form-label fw-bold" style="color:black;">Nombre</label>
                            <input id="nombre_edit" name="nombre" type="text" class="form-control border rounded"
                                value="{{ old('nombre', $parada->nombre) }}">
                        </div>

                        <div class="mb-3">
                            <label for="descripcion_edit" class="form-label fw-bold" style="color:black;">Descripción</label>
                            <textarea id="descripcion_edit" name="descripcion" rows="5" class="form-control border rounded">{{ old('descripcion', $parada->descripcion) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="enlace_edit" class="form-label fw-bold" style="color:black;">Enlace</label>
                            <input id="enlace_edit" name="enlace" type="text" class="form-control border rounded"
                                value="{{ old('enlace', $parada->enlace) }}">
                        </div>

                        <div class="mb-4 col-5">
                            <label for="orden_edit" class="form-label fw-bold" style="color:black;">Orden</label>
                            <input id="orden_edit" name="orden" type="number" min="1" class="form-control border rounded"
                                value="{{ old('orden', $parada->orden) }}">
                            @error('orden','parada')
                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>

                    <!-- FILA DE BOTONES: juntos, cada uno con su form -->
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <!-- Botón que envía el form de EDITAR desde fuera -->
                        <button type="submit" class="btn btn-dark" form="form-editar-parada">
                            Guardar
                        </button>

                        <!-- Form de ELIMINAR, inline para estar al lado -->
                        <form method="POST"
                            action="{{ route('eliminarParada', ['evento' => $evento->id, 'id' => $parada->id]) }}"
                            class="d-inline form-eliminar">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!---REABRIMOS MODALES Y TABS CORRECTOS--->
@if ($errors->parada->any() || session('showModal') === 'modalParada' || request('parada_id'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modalEl = document.getElementById('modalParada');
        if (!modalEl) return;

        // Abre el modal
        var modal = new bootstrap.Modal(modalEl);
        modal.show();

        // Activa el tab correcto:
        let tabTrigger;
        if ("{{ request('parada_id') }}") {
            tabTrigger = document.querySelector('#editar-tab');
        } else {
            tabTrigger = document.querySelector('{{ $errors->parada->any() ? "#crear-tab" : "#crear-tab" }}');
        }

        if (tabTrigger) {
            var tab = new bootstrap.Tab(tabTrigger);
            tab.show();
        }

        // FUNCIÓN PARA ELIMINAR CON SWEETALERT
        document.querySelectorAll('.form-eliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
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
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
@endif