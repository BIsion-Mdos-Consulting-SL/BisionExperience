@php
// ¿Hay timings para este evento?
$hasTiming = $evento->timings()->exists();

$selectedId = request('timing_id');

// Lista completa (solo si hay alguno)
$timings = $hasTiming
? $evento->timings()->orderBy('created_at','asc')->get()
: collect();

// Timing a editar (lo buscamos a través de la relación para asegurar pertenencia)
$timing = $selectedId
? $evento->timings()->find($selectedId)
: null;

$hasSelected = filled($selectedId) && $timing;
@endphp

<div class="modal fade" id="modalTiming" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="d-flex justify-content-end mb-1">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- TABS -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold" id="tim-crear-tab"
                        data-bs-toggle="tab" data-bs-target="#tim-crear" type="button" style="color: #05072e;">Crear</button>
                </li>
                <li class="nav-item">
                    @if($hasTiming)
                    <button class="nav-link fw-bold" id="tim-editar-tab"
                        data-bs-toggle="tab" data-bs-target="#tim-editar" type="button" style="color: #05072e;">Editar</button>
                    @else
                    <button class="nav-link fw-bold disabled" type="button" tabindex="-1" aria-disabled="true" style="color: #05072e;">Editar</button>
                    @endif
                </li>
            </ul>

            <!-- CONTENIDO -->
            <div class="tab-content mt-3">
                <!-- CREAR -->
                <div class="tab-pane fade show active" id="tim-crear" role="tabpanel" aria-labelledby="tim-crear-tab">
                    <div class="modal-header">
                        <h5 class="fs-2 fw-bold" style="color: #05072e;">Añadir Timing</h5>
                    </div>
                    <form method="POST" action="{{ route('store.timing', $evento) }}" class="mt-4" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold" style="color: black;">Nombre</label>
                            <input name="nombre" type="text" class="form-control border rounded" value="{{ old('nombre') }}">
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-bold" style="color: black;">Descripción</label>
                            <textarea name="descripcion" rows="5" class="form-control border rounded">{{ old('descripcion') }}</textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="p-2 rounded" style="background:#05072e;color:#fff">Guardar</button>
                        </div>
                    </form>
                </div>

                <!-- EDITAR -->
                <div class="tab-pane fade" id="tim-editar" role="tabpanel" aria-labelledby="tim-editar-tab">
                    <div class="modal-header">
                        <h5 class="fs-2 fw-bold" style="color:#05072e;">Editar Timing</h5>
                    </div>

                    <label for="tim-editar-select" class="form-label fw-bold">Selecciona timing</label>
                    <select name="select-coche" id="tim-editar-select" class="form-select">
                        <option value="" disabled>Selecciona timing</option>
                        @foreach($timings as $t)
                        <option
                            value="{{ $t->id }}"
                            data-update-url="{{ route('evento.timing.update', ['evento' => $evento, 'timing' => $t]) }}"
                            data-delete-url="{{ route('eliminarTiming', ['evento' => $evento->id, 'id' => $t->id]) }}"
                            data-nombre="{{ e($t->nombre) }}"
                            data-descripcion="{{ e($t->descripcion) }}"
                            {{ isset($timing) && (string)$timing->id === (string)$t->id ? 'selected' : '' }}>
                            {{ $t->nombre }}
                        </option>
                        @endforeach
                    </select>

                    <form id="tim-editar-form"
                        method="POST"
                        action="{{ isset($timing) ? route('evento.timing.update', ['evento' => $evento, 'timing' => $timing]) : '#' }}"
                        class="mt-4">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="tim-editar-nombre" class="form-label fw-bold" style="color:black;">Nombre</label>
                            <input id="tim-editar-nombre" name="nombre" type="text"
                                class="form-control border rounded"
                                value="{{ old('nombre', $timing->nombre ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="tim-editar-descripcion" class="form-label fw-bold" style="color:black;">Descripción</label>
                            <textarea id="tim-editar-descripcion" name="descripcion" rows="5"
                                class="form-control border rounded">{{ old('descripcion', $timing->descripcion ?? '') }}</textarea>
                        </div>
                    </form>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="submit" class="p-2 rounded" style="background:#05072e;color:#fff;" form="tim-editar-form">
                            Guardar
                        </button>

                        <form id="tim-eliminar-form" method="POST" action="#" class="d-inline form-eliminar">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const root = document.getElementById('tim-editar');
        if (!root) return;

        const select = root.querySelector('#tim-editar-select');
        const updFrm = root.querySelector('#tim-editar-form');
        const delFrm = root.querySelector('#tim-eliminar-form');
        const nombre = root.querySelector('#tim-editar-nombre');
        const desc = root.querySelector('#tim-editar-descripcion');

        function hydrate(optionEl) {
            const hasOption = !!(optionEl && optionEl.value);

            // set actions
            updFrm.action = hasOption ? (optionEl.dataset.updateUrl || '#') : '#';
            if (delFrm) delFrm.action = hasOption ? (optionEl.dataset.deleteUrl || '#') : '#';

            // set fields (sin JSON.parse)
            if (hasOption) {
                nombre.value = optionEl.dataset.nombre || '';
                desc.value = optionEl.dataset.descripcion || '';
            } else {
                nombre.value = '';
                desc.value = '';
            }
        }

        // on change
        if (select) {
            select.addEventListener('change', function() {
                hydrate(this.selectedOptions[0]);
            });
        }

        // hidrata al cargar (si hay seleccionado) o el primero con valor
        (function initialHydrate() {
            if (!select) return;
            const current = select.selectedOptions.length ? select.selectedOptions[0] : null;
            if (current && current.value) {
                hydrate(current);
                return;
            }
            const firstVal = Array.from(select.options).find(o => o.value !== '');
            hydrate(firstVal || null);
        })();

        // confirm delete
        document.querySelectorAll('.form-eliminar').forEach(formEl => {
            formEl.addEventListener('submit', function(e) {
                if (!this.action || this.action === '#') {
                    e.preventDefault();
                    Swal.fire({
                        title: "Selecciona un timing primero",
                        icon: "info",
                        confirmButtonColor: "#05072e"
                    });
                    return;
                }
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
    });
</script>