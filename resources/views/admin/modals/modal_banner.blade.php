<div class="modal fade" id="modalBanner" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="d-flex justify-content-end mb-1">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!---ESTADOS QUE SIRVEN PARA LE BANNER (MUY IMPORTANTE , RECIBE Y MANDA A LAS CONDICIONES)--->
            @php
            $hasBanners = isset($banners) && $banners->isNotEmpty();
            $hasSelected = request()->filled('banner_id');
            @endphp

            <!-- TABS -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button
                        class="nav-link fw-bold {{ (!$hasSelected && !$hasBanners) ? 'active' : '' }}"
                        id="crear-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#banner-crear"
                        type="button" role="tab" style="color:#05072e;">
                        Crear
                    </button>
                </li>
                <li class="nav-item">
                    @if($hasBanners)
                    <button
                        class="nav-link fw-bold {{ ($hasSelected || $hasBanners) ? 'active' : '' }}"
                        id="editar-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#banner-editar"
                        type="button" role="tab" style="color:#05072e;">
                        Editar
                    </button>
                    @else
                    <button class="nav-link fw-bold disabled" type="button" tabindex="-1" aria-disabled="true">Editar</button>
                    @endif
                </li>
            </ul>

            <!-- CONTENIDO -->
            <div class="tab-content mt-3">
                <!-- CREAR -->
                <div
                    class="tab-pane fade {{ (!$hasSelected && !$hasBanners) ? 'show active' : '' }}"
                    id="banner-crear" role="tabpanel" aria-labelledby="crear-tab">
                    <div class="modal-header">
                        <h5 class="fs-2 fw-bold" style="color:#05072e;">Añadir Banner</h5>
                    </div>

                    <form method="POST" action="{{ route('store.banner', $evento) }}" class="mt-4" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="evento_id" value="{{ $evento->id }}">

                        <!-- EMPRESA -->
                        <div class="mb-3">
                            <label for="empresa" class="form-label fw-bold" style="color:black;">Empresa</label>
                            <select name="empresa" id="empresa" class="form-select border rounded" required>
                                <option value="">Selecciona empresa</option>
                                @foreach($empresas as $empresa)
                                <option value="{{ $empresa }}">{{ $empresa }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- VIDEO -->
                        <div class="mb-3">
                            <label for="video" class="form-label fw-bold" style="color:black;">Video</label>
                            <input name="video" id="video" type="file" class="form-control border rounded p-1">
                        </div>

                        <!-- ENLACE -->
                        <div class="mb-3">
                            <label for="enlace" class="form-label fw-bold" style="color:black;">Enlace</label>
                            <input id="enlace" name="enlace" type="text" class="form-control border rounded" value="{{ old('enlace') }}">
                        </div>

                        <!-- FRASE -->
                        <div class="mb-3">
                            <label for="frase" class="form-label fw-bold" style="color:black;">Frase</label>
                            <input id="frase" name="frase" type="text" class="form-control border rounded" value="{{ old('frase') }}">
                        </div>

                        <!-- CONTACTO -->
                        <div class="mb-3">
                            <label for="contacto" class="form-label fw-bold" style="color:black;">Contacto</label>
                            <input id="contacto" name="contacto" type="text" class="form-control border rounded" value="{{ old('contacto') }}">
                        </div>

                        <!-- TEXTO -->
                        <div class="mb-3">
                            <label for="texto" class="form-label fw-bold" style="color:black;">Texto</label>
                            <input id="texto" name="texto" type="text" class="form-control border rounded" value="{{ old('texto') }}">
                        </div>

                        <!-- IMAGEN -->
                        <div class="mb-3">
                            <label for="imagen" class="form-label fw-bold" style="color:black;">Imagen</label>
                            <input id="imagen-crear" name="imagen" type="file" class="form-control border rounded p-1" accept="image/*" required>

                            <!---CONTENEDOR DE IMAGEN--->
                            <img id="preview-crear" class="mt-2"></img>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="p-2 rounded" style="background-color:#05072e;">Guardar</button>
                        </div>
                    </form>
                </div>

                <!-- EDITAR -->
                <div
                    class="tab-pane fade {{ ($hasSelected || $hasBanners) ? 'show active' : '' }}"
                    id="banner-editar" role="tabpanel" aria-labelledby="editar-tab">

                    <div class="modal-header">
                        <h5 class="fs-2 fw-bold" style="color:#05072e;">Editar Banner</h5>
                    </div>

                    @if($hasBanners)
                    <form id="form-select-banner" method="GET" action="{{ route('admin.ajustes', $evento) }}" class="mt-3 mb-3">
                        <div class="mb-3">
                            <label for="banner_id" class="form-label fw-bold" style="color:black;">Selecciona la empresa</label>
                            <select name="banner_id" id="banner_id" class="form-select border rounded">
                                <option value="" disabled {{ $hasSelected ? '' : 'selected' }}>Selecciona la empresa</option>
                                @foreach($banners as $bannerOption)
                                <option value="{{ $bannerOption->id }}" {{ (string)request('banner_id') === (string)$bannerOption->id ? 'selected' : '' }}>
                                    {{ $bannerOption->empresa }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    @endif

                    <div id="editar-form-wrapper">
                        @isset($banner)
                        @if($banner)
                        <form id="formBanner" method="POST" action="{{route('evento.banner.update' , ['evento' => $evento->id , 'banner' => $banner->id])}}" class="mt-4" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- IMAGEN -->
                            <div class="mb-3">
                                <label for="imagen-editar" class="form-label fw-bold" style="color:black;">Imagen</label>
                                <input id="imagen-editar" name="imagen" type="file" class="form-control border rounded p-1" accept="image/*">
                                <div id="preview-editar" class="mt-2">
                                    @if(!empty($banner?->imagen))
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::url($banner->imagen) }}"
                                        alt="Imagen actual"
                                        class="img-fluid"
                                        style="max-height: 150px; margin: auto;">
                                    @endif
                                </div>
                            </div>

                            <!-- VIDEO -->
                            <div class="mb-3">
                                <label for="video" class="form-label fw-bold" style="color:black;">Video</label>
                                <input name="video" id="video" type="file" class="form-control border rounded p-1">
                            </div>

                            <!-- ENLACE -->
                            <div class="mb-3">
                                <label for="enlace" class="form-label fw-bold" style="color:black;">Enlace</label>
                                <input id="enlace" name="enlace" type="text" class="form-control border rounded" value="{{ old('enlace', $banner->enlace ?? '') }}">
                            </div>

                            <!-- FRASE -->
                            <div class="mb-3">
                                <label for="frase" class="form-label fw-bold" style="color:black;">Frase</label>
                                <input id="frase" name="frase" type="text" class="form-control border rounded" value="{{ old('frase', $banner->frase ?? '') }}">
                            </div>

                            <!-- CONTACTO -->
                            <div class="mb-3">
                                <label for="contacto" class="form-label fw-bold" style="color:black;">Contacto</label>
                                <input id="contacto" name="contacto" type="text" class="form-control border rounded" value="{{ old('contacto', $banner->contacto ?? '') }}">
                            </div>

                            <!-- TEXTO -->
                            <div class="mb-3">
                                <label for="texto" class="form-label fw-bold" style="color:black;">Texto</label>
                                <input id="texto" name="texto" type="text" class="form-control border rounded" value="{{ old('texto', $banner->texto ?? '') }}">
                            </div>

                        </form>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="p-2 rounded" style="background-color:#05072e;" form="formBanner">Guardar</button>

                            <form method="POST" action="{{route('eliminarBanner' , ['evento' => $evento->id , $banner->id])}}" class="d-inline form-eliminar">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            </form>
                        </div>
                        @endif
                        @endisset
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function() {
        /**Busca el input , genera el preview en la parte de crear. */
        var inputCrear = document.getElementById("imagen-crear");
        if (inputCrear) {
            inputCrear.onchange = function(e) {
                readfileCrear(e.target);
            };
        }

        bindEditarPreview(); // Se usara para poder re-enlazar tras inyectar HTML

        // EDITAR: cargar formulario por AJAX
        var select = document.getElementById('banner_id'); //Busca el select del banner que se editara.
        var formSelect = document.getElementById('form-select-banner'); //Form que enevuelve el select.
        if (select && formSelect) { //Si existe colocamos el evento change()
            select.addEventListener('change', function() {
                var bannerId = select.value;
                if (!bannerId) return;

                // Construimos la URL con el banner_id (misma ruta del form GET)
                var url = new URL(formSelect.action, window.location.origin);
                url.searchParams.set('banner_id', bannerId);

                // Fetch del HTML de esa página
                fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(res) {
                        return res.text();
                    })
                    .then(function(html) {
                        var parser = new DOMParser();
                        var doc = parser.parseFromString(html, 'text/html');

                        var newWrapper = doc.querySelector('#editar-form-wrapper');
                        var wrapper = document.getElementById('editar-form-wrapper');

                        if (!wrapper) return;

                        if (newWrapper) {
                            wrapper.innerHTML = newWrapper.innerHTML; // incluye form + botones
                        } else {
                            wrapper.innerHTML = '<div class="text-muted">No hay datos para este banner.</div>';
                        }

                        // Reengancha los listeners sobre el HTML recién inyectado
                        bindEditarPreview();
                        bindEliminarConfirm();
                    })
                    //Si falla que me lanze un error try catch().
                    .catch(function(err) {
                        console.error('Error cargando banner:', err);
                    });
            });
        }
    };

    //FUNCION CREAR IMAGEN 
    function readfileCrear(input) {
        var file = input.files[0];

        var reader = new FileReader();
        reader.onload = function(e) {
            var filePreview = document.getElementById('preview-crear');
            filePreview.src = e.target.result;
            filePreview.style.display = 'block';
            filePreview.style.height = '150px';
            filePreview.style.margin = 'auto';
        };
        reader.readAsDataURL(file);
    }

    //**Esta funcion busca el input de imagen-editar , si existe lo engancha con un onChange que llama a readFileEditar. */
    function bindEditarPreview() {
        var inputEditar = document.getElementById("imagen-editar");
        if (inputEditar) {
            inputEditar.onchange = function(e) {
                readfileEditar(e.target);
            };
        }
    }

    /**Esta funcion se asegura de que haya un archivo antes y que sea una imagen. */
    function readfileEditar(input) {
        var file = input.files[0];

        var reader = new FileReader();
        reader.onload = function(e) {
            var container = document.getElementById('preview-editar');

            // Busca/crea <img> dentro del contenedor
            var img = container.querySelector('img');
            if (!img) {
                img = document.createElement('img');
                img.style.maxHeight = '150px';
                img.style.margin = 'auto';
                img.style.display = 'block';
                container.innerHTML = '';
                container.appendChild(img);
            }
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function bindEliminarConfirm() {
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
            })
        })
    }
</script>