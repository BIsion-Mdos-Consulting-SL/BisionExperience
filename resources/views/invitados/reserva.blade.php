@extends('layouts.main')
@section('content')
<div class="py-3 mb-5">
    <h1 class="mb-3 text-center mt-5 texto fw-bold">Registro reserva de parada</h1>
    <!----CAPTURA EL EVENTO CON SU PROPIO ID----->
    <input type="hidden" id="evento-id" value="{{ $evento->id }}">

    <!-- PARADA 1 -->
    <form action="#" method="POST" class="container col-10 mb-5 p-5">
        @csrf
        <div class="row">
            <h2 class="text-center texto mb-5 fw-bold">PARADA 1</h2>

            @if(session('success'))
            <div class="alert alert-success w-50 mx-auto">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger w-50 mx-auto">
                {{ session('error') }}
            </div>
            @endif

            <!-- CONDUCTOR -->
            <div class="col-md-6">
                <h3 class="texto fw-bold mb-4">CONDUCTOR</h3>

                <!-----NOMBRE----->
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre*</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Introduce nombre">
                </div>


                <!-----MARCA----->
                <div class="mb-3">
                    <label class="form-label fw-bold">Marca*</label>

                    <div class="position-relative">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control"
                                id="marcaInput-conductor"
                                name="marca_conductor"
                                placeholder="Introduce marca"
                                autocomplete="off">
                        </div>
                        <ul id="marcaSuggestions-conductor"
                            class="list-group position-absolute w-100"
                            style="z-index: 1000;"></ul>
                    </div>
                </div>

                <!-----MODELO----->
                <div class="mb-3">
                    <label class="form-label fw-bold">Modelo*</label>
                    <select name="modelo_conductor" id="modelo-conductor" class="form-select">
                        <option selected disbaled>Selecciona una opción</option>
                    </select>
                </div>

                <!-----VERSION----->
                <div class="mb-3">
                    <label class="form-label fw-bold">Versión*</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-car-front"></i></span>
                        <input type="text" id="version-conductor" class="form-control" placeholder="Introduce versión">
                    </div>
                </div>
            </div>

            <!-- ACOMPAÑANTE -->
            <div class="col-md-6">
                <h3 class="texto fw-bold mb-4">ACOMPAÑANTE</h3>

                <!-----NOMBRE----->
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre*</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Introduce nombre">
                </div>

                <!-----MARCA----->
                <div class="mb-3">
                    <label class="form-label fw-bold">Marca*</label>

                    <div class="position-relative">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control"
                                id="marcaInput-acompanante"
                                name="marca_acompanante"
                                placeholder="Introduce marca"
                                autocomplete="off">
                        </div>
                        <ul id="marcaSuggestions-acompanante"
                            class="list-group position-absolute w-100"
                            style="z-index: 1000;"></ul>
                    </div>
                </div>

                <!-----MODELO----->
                <div class="mb-3">
                    <label class="form-label fw-bold">Modelo*</label>
                    <select name="modelo_acompanante" id="modelo-acompanante" class="form-select">
                        <option selected>Selecciona una opción</option>
                    </select>
                </div>

                <!-----VERSION----->
                <div class="mb-3">
                    <label class="form-label fw-bold">Versión*</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-car-front"></i></span>
                        <input type="text" id="version-acompanante" class="form-control" placeholder="Introduce versión">
                    </div>
                </div>
            </div>

            <!-- BOTÓN -->
            <div class="col-12 text-end mt-4">
                <button type="submit" class="btn_color">Guardar</button>
            </div>
        </div>
    </form>

    <!-- PARADA 2 -->
    <form action="#" method="POST" class="container col-10 mb-5 p-5">
        @csrf
        <div class="row">
            <h2 class="text-center texto mb-5 fw-bold">PARADA 2</h2>

            @if(session('success'))
            <div class="alert alert-success w-50 mx-auto">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger w-50 mx-auto">
                {{ session('error') }}
            </div>
            @endif

            <!-- CONDUCTOR -->
            <div class="col-md-6">
                <h3 class="texto fw-bold mb-4">CONDUCTOR</h3>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre*</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Introduce nombre">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Marca*</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="marca" placeholder="Introduce marca">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Modelo*</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-car-front"></i></span>
                        <input type="text" class="form-control" name="modelo" placeholder="Introduce modelo">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Versión*</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-ev-front"></i></span>
                        <input type="text" class="form-control" name="version" placeholder="Introduce versión">
                    </div>
                </div>
            </div>

            <!-- ACOMPAÑANTE -->
            <div class="col-md-6">
                <h3 class="texto fw-bold mb-4">ACOMPAÑANTE</h3>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre*</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Introduce nombre">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Marca*</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="marca" placeholder="Introduce marca">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Modelo*</label>
                    <select name="modelo" class="form-select">
                        <option disabled selected>Selecciona una opción</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Versión*</label>
                    <select name="version" class="form-select">
                        <option disabled selected>Selecciona una opción</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                </div>
            </div>

            <!-- BOTÓN -->
            <div class="col-12 text-end mt-4">
                <button type="submit" class="btn_color">Guardar</button>
            </div>
        </div>
    </form>
</div>

<!--FOOTER--->
<footer class="footer">
    <img class="m-auto" src="{{asset('images/footer_bision.png')}}" style="width: 200px;">
</footer>
@endsection


<script>
    //FUNCION PARA CARGAR EN EL INPUT VERSION
    document.addEventListener('DOMContentLoaded', function() {
        // Función para conectar un <select> con su input de versión correspondiente
        function setupModeloVersion(selectId, inputId) {
            const select = document.getElementById(selectId);
            const input = document.getElementById(inputId);

            if (!select || !input) {
                console.warn(`No se encontró ${selectId} o ${inputId}`);
                return;
            }

            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const version = selectedOption.getAttribute('data-version');
                input.value = version || '';
            });
        }

        // Conectamos conductor y acompañante
        setupModeloVersion('modelo-conductor', 'version-conductor');
        setupModeloVersion('modelo-acompanante', 'version-acompanante');
    });

    //FUNCION PARA CARGAR LAS MARCAS Y MODELOS
    document.addEventListener('DOMContentLoaded', function() {
        //FUNCIÓN AUTOCOMPLETADO ()
        function setupAutocomplete(inputId, suggestionId, onSelectMarca = null) {
            const input = document.getElementById(inputId);
            const suggestions = document.getElementById(suggestionId);
            const eventoId = document.getElementById('evento-id')?.value;

            if (!input || !suggestions || !eventoId) return;

            //Escucha cuando el usuario escribe la marca.
            input.addEventListener('input', function() {
                //Toma lo escrito por el usuario sin espacios en blanco y limpia las sugerencias anteriores.
                const query = this.value.trim();
                suggestions.innerHTML = '';

                //Si tiene menos de dos letras no aparece el campo con la marca
                if (query.length < 2) return;

                //Llamado a la ruta.
                fetch(`/clientes/marcas?marca=${encodeURIComponent(query)}&evento_id=${eventoId}`)
                    .then(response => {
                        //Comprueba si hay un error.
                        if (!response.ok) throw new Error(`Error ${response.status}`);
                        return response.json();
                    })
                    //Recibe la lista de marcas.
                    .then(data => {
                        //Limpia las anteriores.
                        suggestions.innerHTML = '';
                        //Por cada marca , crea una lista , pone esa marca , le da estilos. 
                        data.forEach(marca => {
                            const li = document.createElement('li');
                            li.textContent = marca;
                            li.className = 'list-group-item list-group-item-action';
                            li.style.cursor = 'pointer';

                            //Cuando da click reeemplaza el input por la marca.
                            li.onclick = () => {
                                input.value = marca;
                                //Limpia las anteriores.
                                suggestions.innerHTML = '';

                                //Llama a la función que le pasamos como callback (onSelectMarca), pasando la marca y el eventoId seleccionada como argumento. Aquí es donde se activa la carga de modelos.
                                if (typeof onSelectMarca === 'function') {
                                    onSelectMarca(marca, eventoId);
                                }
                            };
                            //Añade el li.
                            suggestions.appendChild(li);
                        });
                    })
                    .catch(error => {
                        console.error('Error cargando marcas:', error);
                    });
            });

            //Si damos un click fuera se ocultan las sugerencias.
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !suggestions.contains(e.target)) {
                    suggestions.innerHTML = '';
                }
            });
        }

        //FUNCIÓN PARA CARGAR MODELOS
        function cargarModelosMarca(marca, modeloSelectId, eventoId) {
            //Llama a la ruta
            fetch(`/clientes/modelos?marca=${encodeURIComponent(marca)}&evento_id=${eventoId}`)
                //Verificacion de respuesta de la ruta.
                .then(response => {
                    if (!response.ok) throw new Error(`Error ${response.status}`);
                    return response.json();
                })
                //Encontramos el select por id y limpia antes de agregar.
                .then(modelos => {
                    const select = document.getElementById(modeloSelectId);
                    select.innerHTML = '';

                    //Si hay modelos que se añada lo siguiente.
                    if (modelos.length > 0) {
                        const defaultOption = document.createElement('option');
                        defaultOption.textContent = "Selecciona una opcion";
                        defaultOption.disabled = true;
                        defaultOption.selected = true;
                        select.appendChild(defaultOption);

                        //Por cada modelo
                        modelos.forEach(modelo => {
                            //Crea una opcion , le pone el id y guarda la version en data-version y lo añade al select.
                            const option = document.createElement('option');
                            option.value = modelo.id;
                            option.textContent = modelo.modelo;
                            option.dataset.version = modelo.version;
                            select.appendChild(option);
                        });
                    } else {
                        //Si no entra en la otra condicion y manjea los errores y coloca el mensaje.
                        const option = document.createElement('option');
                        option.textContent = "No hay modelos disponibles";
                        option.disabled = true;
                        option.selected = true;
                        select.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Error cargando modelos:', error);
                });
        }

        //ACTIVA CONDUCTOR Y ACOMPAÑANTE , REALIZA TODA LA FUNCION.
        setupAutocomplete(
            'marcaInput-conductor',
            'marcaSuggestions-conductor',
            function(marcaSeleccionada, eventoId) {
                cargarModelosMarca(marcaSeleccionada, 'modelo-conductor', eventoId);
            }
        );

        setupAutocomplete(
            'marcaInput-acompanante',
            'marcaSuggestions-acompanante',
            function(marcaSeleccionada, eventoId) {
                cargarModelosMarca(marcaSeleccionada, 'modelo-acompanante', eventoId);
            }
        );

    });
</script>