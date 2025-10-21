<div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-4">

            <!---BARRA DE PROGRESO---->
            <div class="progress" role="progressbar" aria-label="Success striped example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 40px;">
                <div class="progress-bar progress-bar-striped bg-success" style="width: 25%"></div>
            </div>

            <!---HEADER---->
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalReservaLabel" style="font-size:30px;color:black;">Reserva de vehículos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>


            <div class="container">
                <h2 style="color:black;font-weight:bold;font-size:25px;margin-top:3%; margin-bottom: 3%;">Parada —</h2>
                <p class="mt-3 mb-3" style="color:black;">Registrarse como:</p>

                <form id="form-reserva" action="" method="POST">
                    @csrf

                    <div class="d-flex flex-wrap gap-5 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo" id="tipo-conductor" value="conductor">
                            <label class="form-check-label" for="tipo-conductor" style="color:black;">Conductor</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tipo" id="tipo-acomp" value="acompañante" checked>
                            <label class="form-check-label" for="tipo-acomp" style="color:black;">Acompañante</label>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label" style="color:black;font-weight:bold;">Vehículos</label>
                        <select name="coche_id" class="form-select" required>
                            <option value="" disabled selected>Selecciona el vehículo</option>
                        </select>
                    </div>

                    <div class="mt-3 col-12 d-flex justify-content-end">
                        <button type="submit" class="p-2 border rounded" style="background-color: #05072e;">Guardar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("modalReserva");
        const form = document.getElementById("form-reserva");
        const h2Parada = modal.querySelector("h2");
        const selectCoches = modal.querySelector("select[name='coche_id']");
        const btnSubmit = modal.querySelector("button[type='submit']");
        const progressBar = modal.querySelector(".progress-bar");

        const routeTemplate = "{{ route('store.reserva', ['evento' => 'EVENTO_ID', 'parada' => 'PARADA_ID']) }}";

        let EVENTO_ID = null;
        let PARADA_ID = null;

        // cache del último array de coches recibido
        let __ULTIMOS_COCHES__ = [];

        const setLoading = (state) => {
            btnSubmit.disabled = state;
            btnSubmit.dataset._originalText ??= btnSubmit.textContent;
            btnSubmit.textContent = state ? "Cargando..." : btnSubmit.dataset._originalText;
        };

        const setProgress = (total, completadas) => {
            if (!progressBar) return;
            const pct = total > 0 ? Math.round((completadas / total) * 100) : 0;
            progressBar.style.width = pct + "%";
            progressBar.setAttribute("aria-valuenow", pct);
            progressBar.textContent = `${completadas ?? 0}/${total ?? 0}`;
        };

        const currentTipo = () =>
            modal.querySelector('input[name="tipo"]:checked')?.value || 'acompañante';

        // pinta el select leyendo flags del backend
        const renderCoches = (coches) => {
            __ULTIMOS_COCHES__ = Array.isArray(coches) ? coches : [];
            const tipo = currentTipo();

            selectCoches.innerHTML = `<option value="" disabled selected>Selecciona el vehículo</option>`;

            if (__ULTIMOS_COCHES__.length) {
                __ULTIMOS_COCHES__.forEach(c => {
                    const opt = document.createElement("option");
                    opt.value = c.id;

                    // Texto que se muestra
                    let label = `${c.marca ?? ''} ${c.modelo ?? ''} - ${c.matricula ?? ''}`.trim() || `Vehículo #${c.id}`;
                    const badges = [];
                    if (c.usado) badges.push("En uso");
                    if (tipo === "conductor" && c.conductor_asignado) badges.push("Conductor asignado");
                    if (tipo === "acompañante" && c.lleno) badges.push("Sin plazas");
                    if (badges.length) label += " (" + badges.join(", ") + ")";
                    if (tipo === "acompañante" && typeof c.plazas_disponibles === "number")
                        label += ` — ${c.plazas_disponibles} libres`;

                    opt.textContent = label;

                    // reglas de disabled
                    let disabled = false;
                    if (c.usado) disabled = true;
                    else if (tipo === "conductor" && c.conductor_asignado) disabled = true;
                    else if (tipo === "acompañante" && c.lleno) disabled = true;

                    opt.disabled = disabled;
                    selectCoches.appendChild(opt);
                });
                selectCoches.disabled = false;
            } else {
                const opt = document.createElement("option");
                opt.value = "";
                opt.textContent = "No hay vehículos disponibles";
                selectCoches.appendChild(opt);
                selectCoches.disabled = true;
            }
        };

        //Re-pintar cuando cambias entre Conductor/Acompañante
        modal.addEventListener("change", (e) => {
            if (e.target.name === "tipo") {
                renderCoches(__ULTIMOS_COCHES__);
            }
        });

        // Carga inicial al abrir modal
        modal.addEventListener("show.bs.modal", async () => {
            setLoading(true);
            try {
                const res = await fetch("{{ route('cargar.datos') }}", {
                    headers: {
                        "Accept": "application/json"
                    },
                    cache: "no-store"
                });
                if (!res.ok) {
                    const text = await res.text();
                    throw new Error(`Error al obtener datos (${res.status}) ${text || ""}`);
                }

                const payload = await res.json();
                const {
                    evento,
                    parada,
                    coches,
                    progreso
                } = payload;

                if (!evento?.id || !parada?.id) throw new Error("Faltan IDs de evento/parada.");
                EVENTO_ID = evento.id;
                PARADA_ID = parada.id;

                h2Parada.textContent = (parada && parada.orden != null) ? `Parada ${parada.orden}` : "Parada —";

                renderCoches(coches);

                form.action = routeTemplate
                    .replace("EVENTO_ID", encodeURIComponent(EVENTO_ID))
                    .replace("PARADA_ID", encodeURIComponent(PARADA_ID));

                if (progreso && typeof progreso.total !== "undefined") {
                    setProgress(progreso.total, progreso.completadas ?? 0);
                }

                btnSubmit.disabled = false;
            } catch (err) {
                console.error(err);
                selectCoches.innerHTML = `<option value="" disabled selected>Error al cargar</option>`;
                selectCoches.disabled = true;
                form.action = "";
                btnSubmit.disabled = true;
                //SweetAlert error.
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudieron cargar los datos',
                    text: err.message || 'Inténtalo de nuevo.'
                });
            } finally {
                setLoading(false);
            }
        });

        // Submit por AJAX para no cerrar el modal y avanzar a la siguiente parada
        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            setLoading(true);
            try {
                const fd = new FormData(form);
                const res = await fetch(form.action, {
                    method: "POST",
                    body: fd,
                    headers: {
                        "Accept": "application/json"
                    }
                });

                if (!res.ok) {
                    // Si backend devuelve JSON con error, lo leemos para mostrarlo
                    let serverMsg = '';
                    try {
                        const maybeJson = await res.json();
                        if (maybeJson?.error) serverMsg = maybeJson.error;
                    } catch (_) {
                        /* ignore */ }

                    const text = serverMsg || (await res.text());
                    throw new Error(`Error al guardar (${res.status}) ${text || ""}`);
                }

                const json = await res.json();

                //Si backend devuelve { error: '...' } (p.ej. yaReservoEstaParada)
                if (json?.error) {
                    throw new Error(json.error);
                }

                if (!json?.ok) {
                    throw new Error(json?.message || "No se pudo crear la reserva.");
                }

                // Progreso
                const prog = json.progreso || {};
                if (typeof prog.total !== "undefined") {
                    setProgress(prog.total, prog.completadas ?? 0);
                }

                // ¿Hay siguiente parada?
                if (prog.siguiente_id) {
                    PARADA_ID = prog.siguiente_id;
                    h2Parada.textContent = (prog.siguiente_orden != null) ?
                        `Parada ${prog.siguiente_orden}` : "Parada —";

                    form.action = routeTemplate
                        .replace("EVENTO_ID", encodeURIComponent(EVENTO_ID))
                        .replace("PARADA_ID", encodeURIComponent(PARADA_ID));
                } else {
                    // No hay más paradas → cerramos
                    const instance = bootstrap.Modal.getInstance(modal);
                    instance?.hide();
                }

                // repinta el select con los coches que retorna el backend
                if (Array.isArray(json.coches)) {
                    renderCoches(json.coches);
                } else {
                    selectCoches.selectedIndex = 0;
                }

                //Sweet alert de éxito (opcional)
                Swal.fire({
                    icon: 'success',
                    iconColor: "#05072e",
                    title: 'Reserva guardada',
                    showConfirmButton: false,
                    confirmarButtonColor: "#05072e",
                    timer: 1400
                });
            } catch (err) {
                console.error(err);
                // SweetAlert de error
                Swal.fire({
                    icon: 'error',
                    iconColor: "#05072e",
                    title: 'No se pudo crear la reserva',
                    text: err.message || 'Revisa los datos e inténtalo otra vez.',
                    confirmButtonColor: "#05072e"
                });
            } finally {
                setLoading(false);
            }
        });

        // Reset al cerrar
        modal.addEventListener("hidden.bs.modal", () => {
            h2Parada.textContent = "Parada —";
            selectCoches.innerHTML = `<option value="" disabled selected>Selecciona el vehículo</option>`;
            selectCoches.disabled = true;
            form.action = "";
            btnSubmit.disabled = true;
            setProgress(100, 0);
            __ULTIMOS_COCHES__ = [];
        });
    });
</script>