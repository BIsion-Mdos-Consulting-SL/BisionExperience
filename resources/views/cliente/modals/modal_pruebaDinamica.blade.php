<div class="modal fade" id="modalPruebaDinamica" tabindex="-1" aria-labelledby="modalPruebaDinamicaLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-4">

            <!-- HEADER -->
            <div class="modal-header mb-4">
                <h5 class="modal-title fw-bold" id="modalPruebaDinamicaLabel" style="font-size:30px;color:black;">
                    Empecemos...
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!-- BODY (sin <form>) -->
            <div id="cards-paradas" class="w-100"></div>
        </div>
    </div>
</div>

<script>
    /**Funcion para poder redcoger los datos y poder crear las PRUEBAS DINAMICAS */
    document.addEventListener('DOMContentLoaded', () => {
        //Recogemos el valor del modal.
        const modalEl = document.getElementById('modalPruebaDinamica');
        //Recogemos el valor del div donde se encuentran las cards.
        const cards = document.getElementById('cards-paradas');

        //Recogemos las URLS que hemos pasado en las rutas (web.php) -> Rutas Prueba Dinamica.
        const GET_URL = "{{ route('cargarDatos.pruebaDinamica') }}";
        const POST_URL = "{{ route('store.pruebaDinamica') }}";

        let EVENTO_ID = null;
        let DATA = {
            paradas: [], //Array de paradas.
            coches: [], //Array de coches.
            reservas: {} //JSON de reservas.
        };

        // Helper robusto: NO consumas el body en más sitios.
        const request = async (url, options = {}) => {
            //Realizamos la peticion.
            const res = await fetch(url, options);
            const text = await res.clone().text(); // podemos loguear siempre
            let json = null;
            try {
                json = JSON.parse(text); //Se parsea el json.
            } catch {
                /* no-json */
            }
            return {
                /**Devuelve obejto con la informacion */
                status: res.status,
                ok: res.ok,
                json,
                text
            };
        };

        /** Busca paradas*/
        const getParadaNumero = (paradaId) => {
            /**Busca parada con findIndex para encontrar la posicion que esta la parada cuyo id coincide con paradaId */
            const idx = DATA.paradas.findIndex(p => String(p.id) === String(paradaId));
            /**Si la encuentra devuelve el orden de la parada (siempre +1 arranca en 0) */
            return idx >= 0 ? (idx + 1) : paradaId;
        };

        /**Devuelve todo los arrays completos y el json que es igual = DATA  se pasa arriba. */
        const render = () => {
            //Se inicia en vacio la card hasta que carguen los datos.
            cards.innerHTML = '';
            const {
                paradas,
                coches,
                reservas
            } = DATA;

            /**Recorremos el array de paradas con un index el cual empezara en 0 para identificar el id de cada una de ellas. */
            paradas.forEach((parada, idx) => {
                //Recogemos las reservas y el valor de sus id.
                const reserva = reservas[parada.id] || null;
                /**Se le pasa una constante que posicionara al index en la posicion 0 , si es true y sino de cada reserva se eliminara 1 para que empiece desde el id 1 y donde recogera la hora inicio. */
                const previousOk = (idx === 0) ?
                    true :
                    !!(reservas[paradas[idx - 1].id] && reservas[paradas[idx - 1].id].hora_fin);

                const isDone = !!(reserva && reserva.hora_fin);

                /**Span para poder condicionar si parada esta completada. Si es distinto a la posicion que se usa en la constante de previousoK pues directamente saldra un span en donde te pondra un mensaje para condicionar que se tiene completar antes la parada anterior, y sino la parada ha sido completada. */
                const stateBadge = !previousOk ?
                    '<span class="badge bg-secondary">Completa la parada anterior</span>' :
                    (isDone ? '<span class="badge bg-success">Completada</span>' : '');
                /**Select opciones: Aqui recorreremos todos los coches que tiene coches[] , colocaremos una option para sacar como valor el id de cada uno de los coches seleccionados para poder recoger la informacion que se pintara en el select (join)*/
                const selOptions = coches.map(c =>
                    `<option value="${c.id}" ${reserva && +reserva.coche_id === +c.id ? 'selected' : ''}>
          ${c.marca} ${c.modelo}${c.matricula ? ' - ' + c.matricula : ''}
        </option>`
                ).join('');

            
                const horaInicio = reserva?.hora_inicio ?? '—';//Creamos una constante para recoger la hora inicio de la reserva.
                const horaFin = reserva?.hora_fin ?? '—';//Creamos una constante para recoger la hora de fin de la reserva.

                const disabledByState = (!previousOk || isDone);
                const disabledAttr = disabledByState ? 'disabled' : '';
                const disabledClass = disabledByState ? 'opacity-50 pe-none' : '';

                /**Creamos una constante para poder crear una card que es la que se presentara en la prueba dinamica en donde se le pasara la clase disabledClass que nos ocultara la card. */
                const card = document.createElement('div');
                card.className = `card mb-3 ${disabledClass}`;//Pasamos la clase.
                /** Creamos el cuerpo de la card para recoger toda la informacion.*/
                card.innerHTML = `
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <p class="card-text mb-0"><strong>Parada:</strong> ${parada.nombre}</p>
            ${stateBadge}
          </div>

          <div class="mb-2">
            <label class="fw-bold mb-2">Vehículo</label>
            <select class="form-select" name="coche_id_${parada.id}" data-parada="${parada.id}" ${disabledAttr}>
              <option value="" disabled ${reserva ? '' : 'selected'}>Selecciona el vehículo</option>
              ${selOptions}
            </select>
          </div>

          <div class="d-flex gap-4 align-items-center mb-2">
            <div class="form-check">
              <input class="form-check-input" type="radio" name="accion_${parada.id}" id="inicio_${parada.id}" value="inicio" ${disabledAttr}>
              <label class="form-check-label text-success fw-bold" for="inicio_${parada.id}">Start</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="accion_${parada.id}" id="fin_${parada.id}" value="fin" ${disabledAttr}>
              <label class="form-check-label text-danger fw-bold" for="fin_${parada.id}">Stop</label>
            </div>
          </div>

          <div class="small text-muted mb-2 text-end">
            <strong>Guardado:</strong> Inicio: ${horaInicio} | Fin: ${horaFin}
          </div>
        </div>
      `;
                cards.appendChild(card);//Se volcara toda las informacion en esta card(appendChild)
            });
        };

        /**Get inicial para poder traer toda la informacion mediante una funcion asincrona (modalEl) = const modal */
        modalEl.addEventListener('show.bs.modal', async () => {
            /** Agregamso un mensaje de texto en un div.*/
            cards.innerHTML = '<div class="text-center p-3">Cargando...</div>';
            try {
                const {
                    /**Se pasa toda la informacion que tenemos */
                    ok,
                    status,
                    json,
                    text
                } = await request(GET_URL, {
                    /**Recoge la ruta get */
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                //Si es distinto a ok que me pinte el error.
                if (!ok) throw new Error(`Server ${status}: ${text}`);

                //Recogemos el evento id.
                EVENTO_ID = json.evento.id;


                const reservasMap = Array.isArray(json.reservas) ?
                    Object.fromEntries(json.reservas.map(r => [r.parada_id, r])) :
                    (json.reservas || {});

                DATA = {
                    /**En el DATA recogeremos la informacion tanto de paradas , coches y reservas.*/
                    paradas: json.paradas || [],//Json o [] vacio.
                    //Recorremos el array de coches y traemos la informacion importante , la que queremos y utilizaremos.
                    coches: (json.coches || []).map(c => ({
                        ...c,
                        matricula: c.matricula ?? c.placa ?? null
                    })),
                    //Informacion de la reserva = reservasMap.
                    reservas: reservasMap
                };

                render();
                //Error me saca el alert mediante un catch(err).
            } catch (err) {
                console.error(err);
                cards.innerHTML = '<div class="alert alert-danger">No se pudo cargar la información.</div>';
            }
        });

        // POST para enviar la informacion (parada, coche , accion)
        const postAccion = ({
            paradaId,
            cocheId,
            accion
        }) => {
            const payload = new FormData();
            payload.append('_token', '{{ csrf_token() }}');//Recoge el token de verificacion.
            payload.append('evento_id', EVENTO_ID);//Recoge el EVENTO_ID para la prueba dinamica.
            payload.append('parada_id', paradaId);//Recoge la paradaId que se le pasa en el postAction.
            payload.append('coche_id', cocheId);//Recoge el cocheId que se le pasa en el postAction.
            payload.append('accion', accion);//Recoge la accion que se le pasa en el postAction.

            /**Envio de la ruta POST_URL*/
            return request(POST_URL, {
                method: 'POST',
                body: payload,
                headers: {
                    'Accept': 'application/json'
                }
            });
        };

        /** */
        const setCardEnabled = (paradaId, enabled) => {
            const select = cards.querySelector(`select[name="coche_id_${paradaId}"]`);
            const rInicio = cards.querySelector(`#inicio_${paradaId}`);
            const rFin = cards.querySelector(`#fin_${paradaId}`);
            [select, rInicio, rFin].forEach(el => {
                if (el) el.disabled = !enabled;
            });
        };

        /**Busca la card y luego la pinta con toda la informacion.*/
        cards.addEventListener('change', async (e) => {
            const radio = e.target.closest('input[type="radio"][name^="accion_"]');
            if (!radio) return;

            const name = radio.name;
            const paradaId = name.split('_')[1];
            const accion = radio.value;
            const select = cards.querySelector(`select[name="coche_id_${paradaId}"]`);

            const rState = DATA.reservas[paradaId];
            if (rState && rState.hora_fin) {
                radio.checked = false;
                Swal.fire({
                    icon: 'info',
                    title: `Parada ${getParadaNumero(paradaId)} ya finalizada`,
                    timer: 1100,
                    showConfirmButton: false
                });
                return;
            }

            if (!select || !select.value) {
                radio.checked = false;
                Swal.fire({
                    icon: 'warning',
                    title: 'Selecciona un vehículo',
                    timer: 1400,
                    showConfirmButton: false
                });
                return;
            }

            setCardEnabled(paradaId, false);

            try {
                const {
                    status,
                    json,
                    text
                } = await postAccion({
                    paradaId,
                    cocheId: select.value,
                    accion
                });

                // --- Errores controlados por backend ---
                if (status === 409 && json?.code === 'car_in_use') {
                    radio.checked = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Coche en uso',
                        text: json.message || 'El coche está en uso. Espera a su finalización.',
                        timer: 1800,
                        showConfirmButton: false
                    });
                    setCardEnabled(paradaId, true);
                    return;
                }

                if (status === 422 && json?.code === 'min_time_not_reached') {
                    radio.checked = false;
                    Swal.fire({
                        icon: 'info',
                        title: 'Aún no puedes finalizar',
                        text: json.message || 'Debes esperar 15 minutos desde el inicio.',
                        timer: 2200,
                        showConfirmButton: false
                    });
                    setCardEnabled(paradaId, true);
                    return;
                }

                if (status === 422 && json?.code === 'no_start') {
                    radio.checked = false;
                    Swal.fire({
                        icon: 'info',
                        title: 'Primero inicia',
                        text: json.message || 'Debes iniciar la parada antes de finalizarla.',
                        timer: 1600,
                        showConfirmButton: false
                    });
                    setCardEnabled(paradaId, true);
                    return;
                }

                // Si vino algo no-OK no tipificado arriba
                if (json?.ok === false || (!json && status >= 400)) {
                    throw new Error(json?.message || `Server ${status}: ${text}`);
                }

                // --- Éxito ---
                if (!DATA.reservas[paradaId]) DATA.reservas[paradaId] = {};
                DATA.reservas[paradaId].coche_id = +select.value;
                DATA.reservas[paradaId].hora_inicio = json.hora_inicio ?? DATA.reservas[paradaId].hora_inicio ?? null;
                DATA.reservas[paradaId].hora_fin = json.hora_fin ?? DATA.reservas[paradaId].hora_fin ?? null;

                const numeroParada = getParadaNumero(paradaId);

                if (json.finalizado) {
                    const finalMsg = json.final_message || json.message_final || '¡Gracias por participar!';
                    Swal.fire({
                        icon: 'success',
                        title: finalMsg,
                        timer: 1600,
                        showConfirmButton: false,
                        position: 'center'
                    });
                    setTimeout(() => {
                        const bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        bsModal.hide();
                    }, 1600);
                } else {
                    const titulo = json.message || (accion === 'inicio' ?
                        `Parada ${numeroParada} iniciada.` :
                        `Parada ${numeroParada} finalizada.`);
                    Swal.fire({
                        icon: 'success',
                        title: titulo,
                        timer: 1100,
                        showConfirmButton: false,
                        position: 'center'
                    });
                }

                render();
            } catch (err) {
                console.error(err);
                radio.checked = false;
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo guardar',
                    text: err.message
                });
            } finally {
                const s = DATA.reservas[paradaId];
                const done = s && s.hora_fin;
                if (!done) setCardEnabled(paradaId, true);
            }
        });

        // Si cambian vehículo, desmarcar radios
        cards.addEventListener('change', (e) => {
            const select = e.target.closest('select[name^="coche_id_"]');
            if (!select) return;
            const paradaId = select.name.split('_')[2];
            const rInicio = cards.querySelector(`#inicio_${paradaId}`);
            const rFin = cards.querySelector(`#fin_${paradaId}`);
            if (rInicio) rInicio.checked = false;
            if (rFin) rFin.checked = false;
        });

    });
</script>