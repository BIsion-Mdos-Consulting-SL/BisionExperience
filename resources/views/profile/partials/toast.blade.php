<!---Usamos esta funcion para manjear las sesiones y mantener una estructura mas limpia al momneto de pasar en el with el mensaje de error y el mensaje exito.---->
@if (session('toast'))
<meta name="toast" content="{{ base64_encode(json_encode(session('toast'))) }}">
@endif

@if ($errors->any() && !session('toast'))
<meta name="has-errors" content="1">
@endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const meta = (n) => document.querySelector(`meta[name="${n}"]`)?.content;

        const toastB64 = meta('toast');
        if (toastB64) {
            try {
                const payload = JSON.parse(atob(toastB64));
                Toast.fire(payload);
                return;
            } catch (e) {
                console.error('Toast payload inválido:', e);
            }
        }

        if (meta('has-errors') === '1') {
            Toast.fire({
                icon: 'error',
                title: 'Hay errores en el formulario'
            });
        }
    });
</script>