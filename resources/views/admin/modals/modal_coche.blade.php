<div class="modal fade" id="modalCoche" tabindex="-1" aria-labelledby="modalCocheLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="d-flex justify-content-end mb-1">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-header">
                <h5 class="fs-2 fw-bold" id="modalCocheLabel" style="color: #05072e;">Editar Coche</h5>
            </div>

            <label class="mb-3 mt-3" style="color: black; font-weight: bold;">Elige una opcion: </label>
            <select id="selectCoche" class="form-select">
                <option value="" selected disabled>Seleccionar coche</option>
                @foreach($coches as $coche)
                <option
                    value="{{ $coche->id }}"
                    data-update-url="{{ route('evento.coche.update', ['evento' => $evento, 'coche' => $coche]) }}"
                    data-delete-url="{{ route('eliminarCoches', ['evento' => $evento->id, 'id' => $coche->id]) }}"
                    data-marca="{{ $coche->marca }}"
                    data-modelo="{{ $coche->modelo }}"
                    data-version="{{ $coche->version }}"
                    data-foto="{{ $coche->foto_vehiculo ? asset('storage/'.$coche->foto_vehiculo) : '' }}">
                    {{ $coche->marca }} {{ $coche->modelo }}
                </option>
                @endforeach
            </select>

            <!-- FORMULARIO EDITAR -->
            <form id="formCoche" method="POST" action="#" enctype="multipart/form-data" class="mt-4">
                @csrf
                @method('PUT')

                <div class="mb-2">
                    <label class="form-label fw-bold" style="color:black;">Marca</label>
                    <input id="marca" name="marca" type="text" class="form-control border rounded">
                </div>

                <div class="mb-2">
                    <label class="form-label fw-bold" style="color:black;">Modelo</label>
                    <input id="modelo" name="modelo" type="text" class="form-control border rounded">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="color:black;">Versión</label>
                    <input id="version" name="version" type="text" class="form-control border rounded">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="color:black;">Imagen del vehículo* (jpg, jpeg, png)</label>
                    <input id="foto_vehiculo" name="foto_vehiculo" type="file" accept="image/*" class="form-control border rounded p-1">
                    <div id="preview" class="mt-2"></div>
                </div>
            </form>

            <!-- Botones -->
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="submit" class="btn btn-dark" form="formCoche">Guardar</button>

                <!-- DELETE: action lo setea JS según selección -->
                <form id="formEliminarCoche" method="POST" action="#" class="d-inline form-eliminar">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('selectCoche');
        const formUpdate = document.getElementById('formCoche');
        const formDelete = document.getElementById('formEliminarCoche');

        const inputMarca = document.getElementById('marca');
        const inputModelo = document.getElementById('modelo');
        const inputVersion = document.getElementById('version');
        const preview = document.getElementById('preview');

        // Limpia el formulario al inicio (sin selección)
        function resetForm() {
            formUpdate.action = '#';
            if (formDelete) formDelete.action = '#';
            inputMarca.value = '';
            inputModelo.value = '';
            inputVersion.value = '';
            preview.innerHTML = '';
        }
        resetForm();

        // Cuando seleccionan un coche, hidrata update + delete + campos
        select.addEventListener('change', function() {
            const o = this.selectedOptions[0];
            if (!o || !o.value) {
                resetForm();
                return;
            }

            formUpdate.action = o.dataset.updateUrl || '#';
            if (formDelete) formDelete.action = o.dataset.deleteUrl || '#';

            inputMarca.value = o.dataset.marca || '';
            inputModelo.value = o.dataset.modelo || '';
            inputVersion.value = o.dataset.version || '';

            preview.innerHTML = o.dataset.foto ?
                `<img src="${o.dataset.foto}" style="max-width:350px; max-height:250px; border-radius:10px; display:block; margin:auto;">` :
                '';
        });

        // Confirmación de borrado
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
                    if (result.isConfirmed) this.submit();
                });
            });
        });
    });
</script>