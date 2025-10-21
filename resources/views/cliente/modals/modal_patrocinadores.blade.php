<div class="modal fade" id="modalPatrocinadores" tabindex="-1" aria-hidden="true"
    data-endpoint="{{ route('cargarDatos.patrocinadores') }}"
    data-evento-id="{{ $evento->id ?? '' }}">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-4">

            <!---HEADER--->
            <div class="modal-header mb-4">
                <h1 class="fw-bold" style="color: #05072e; font-size:xx-large;">Partners</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <!----CONTENEDOR DONDE PINTAREMOS INFORMACION--->
            <div id="patrocinadores-container"></div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const modalEl = document.getElementById('modalPatrocinadores');
        const container = document.getElementById('patrocinadores-container');

        const skeleton = () => `
            <div class="d-flex align-items-center gap-2 mb-3">
              <div class="spinner-border" role="status" aria-hidden="true"></div>
              <span>Cargando patrocinadores…</span>
            </div>
        `;

        const emptyState = (msg = 'No hay patrocinadores para este evento.') =>
            `<div class="alert alert-warning mb-0">${msg}</div>`;

        const bannerCard = (b) => `
        <div class="card shadow-sm mb-3">
            <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-4 d-flex justify-content-center">
                ${b?.imagen 
                    ? `<img class="img-fluid rounded banner-media" src="${b.imagen}" alt="${b.empresa||'Banner'}" />` 
                    : ''
                }
                ${!b?.imagen && b?.video 
                    ? `<video class="rounded banner-media" controls src="${b.video}"></video>` 
                    : ''
                }
                </div>
                <div class="col-12 col-md-8 d-flex flex-column justify-content-center">
                <h5 class="mb-1 fw-bold">${b?.empresa ?? '—'}</h5>
                ${b?.frase ? `<p class="mb-2 fw-semibold fst-italic">"${b.frase}"</p>` : ''}
                ${b?.texto ? `<p class="mb-2">${b.texto}</p>` : ''}
                <div class="d-flex flex-wrap gap-3 mt-2">
                    ${b?.enlace ? `<a class="link-primary" href="${b.enlace}" target="_blank" rel="noopener">Visitar</a>` : ''}
                    ${b?.contacto ? `<small class="text-muted">Contacto: ${b.contacto}</small>` : ''}
                </div>
                </div>
            </div>
            </div>
        </div>
        `;


        const render = ({
            evento
        }) => {
            const banners = evento?.banners ?? [];
            if (!banners.length) {
                container.innerHTML = emptyState('No hay patrocinadores para este evento.');
                return;
            }
            container.innerHTML = banners.map(bannerCard).join('');
        };

        const loadData = async () => {
            const endpoint = modalEl.dataset.endpoint;
            const eventoId = modalEl.dataset.eventoId || '';
            container.innerHTML = skeleton();
            try {
                const url = new URL(endpoint, window.location.origin);
                if (eventoId) url.searchParams.set('evento_id', eventoId);
                const res = await fetch(url.toString(), {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) {
                    container.innerHTML = emptyState(data?.message || 'Error cargando patrocinadores.');
                    return false;
                }
                render(data);
                return true;
            } catch {
                container.innerHTML = emptyState('Fallo de red cargando patrocinadores.');
                return false;
            }
        };
        await loadData();
    });
</script>