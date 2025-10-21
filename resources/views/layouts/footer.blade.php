<footer class="footer">
    @php
    $banners = isset($banners) ? collect($banners) : collect();
    $banners = $banners->filter(fn($b) => !empty($b->imagen) || !empty($b->video));
    @endphp

    @if($banners->isNotEmpty())
    <div class="banner-rotator" id="footerRotator">
        @foreach($banners as $b)

        @if($b->video)
        <video
            class="banner-item"
            src="{{ Storage::url($b->video) }}"
            muted loop playsinline>
        </video>
        @elseif($b->imagen)
        <img
            class="banner-item"
            src="{{ Storage::url($b->imagen) }}"
            alt="Banner" loading="lazy" />
        @endif
        @endforeach
    </div>
    @endif
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rotator = document.getElementById('footerRotator');
        if (!rotator) return;

        const items = Array.from(rotator.querySelectorAll('.banner-item'));
        if (items.length === 0) return;

        const DURATION = 3000; // 3s por banner
        let i = 0;

        /**Funcion que muestra el banner y los oculta. */
        function show(n) {
            items.forEach((el, index) => { //Recorre todos los banners.
                const active = index === n; //Determinamos si este es el banner activo.
                el.classList.toggle('active', active); //Añade y quita la clase active..

                /**Si el banner es un video y esta activo intenta reiniciar el video al seg 0 
                 * y lo reproduce , si no es el activo pause el video.*/
                if (el.tagName === 'VIDEO') {
                    if (active) {
                        try {
                            el.currentTime = 0;
                            el.play();
                        } catch (e) {}
                    } else {
                        try {
                            el.pause();
                        } catch (e) {}
                    }
                }
            });
        }

        show(0); //Muestra el primer banner al iniciar.

        /**Avanza el indice y muestra el banner correspondiente , con la duracion correspondiente
         * que se pasa por variable (INTERVALO DE TIEMPO)*/
        setInterval(() => {
            i = (i + 1) % items.length;
            show(i);
        }, DURATION);
    });
</script>