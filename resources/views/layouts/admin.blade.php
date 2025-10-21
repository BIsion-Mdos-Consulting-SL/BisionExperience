<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bision-io</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<style>
    :root {
        --bg-primary: #0A0D40;
        --bg-header: #05072e;
        --footer-h: 80px;
        --card-radius: 12px;
        --card-w: 320px;
        --card-h: 200px;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        overflow-y: auto;
        background: var(--bg-primary);
        color: #fff;
    }

    .header {
        background-color: var(--bg-header);
    }

    .header_logo {
        width: 50px;
    }

    .boton_container {
        display: flex;
        justify-content: flex-end
    }

    .boton_primario {
        background-color: #05072e;
        color: white;
        border-radius: 5px;
        padding: 10px;
        text-align: end;
    }

    .btn_secundario {
        background-color: #EBEBEB;
        border: 1px solid #0A0D40;
        border-radius: 5px;
        color: #0A0D40;
        padding: 10px 15px;
        text-decoration: none;
        font-weight: bold;
    }

    .btn_secundario:hover {
        background-color: #dbd7d7ff;
    }

    /* Contenedor principal */
    .fondo_principal {
        min-height: calc(100vh - var(--footer-h));
        display: grid;
        place-items: center;
        padding: 10px;
        background: var(--bg-primary);
    }

    .hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: clamp(12px, 2.5vw, 24px);
        width: min(1200px, 100%);
        margin-inline: auto;
    }

    .menu {
        display: grid;
        gap: clamp(16px, 4vw, 40px);
        padding: 5%;
        grid-template-columns: 1fr;
        width: 100%;
        justify-items: center;
        margin-bottom: 50px;
    }

    /* Tarjeta/botón */
    .fondo_botones {
        position: relative;
        display: block;
        width: clamp(240px, 42vw, var(--card-w));
        aspect-ratio: 16 / 10;
        border-radius: var(--card-radius);
        overflow: hidden;
        text-decoration: none;
        background: #111;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .25);
    }

    /* Imagen de fondo con “wash” por defecto */
    .fondo_botones img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        filter: grayscale(100%) brightness(1.2) sepia(20%) hue-rotate(0deg) saturate(0%) contrast(90%);
        transition: filter .35s ease, transform .35s ease;
    }

    .fondo_botones p {
        position: absolute;
        inset: 0;
        margin: 0;
        display: grid;
        place-items: center;
        color: #111;
        font-weight: 700;
        font-size: 30px;
        text-align: center;
        padding: 6px 10px;
        line-height: 1.1;
        text-shadow: 0 1px 2px rgba(255, 255, 255, .3);
    }

    /* Hover solo para pantallas que necesitan pasar el raton*/
    @media (hover:hover) and (pointer:fine) {
        .fondo_botones:hover p {
            color: #fff;
            text-shadow: 0 2px 8px rgba(0, 0, 0, .6);
        }

        .fondo_botones:hover img {
            filter: none;
            transform: scale(1.03);
        }
    }

    /* Hover solo para pantallas que no necesitan pasar el raton*/

    @media (hover: none) and (pointer: coarse) {
        .fondo_botones p {
            color: white;
            text-shadow: none;
        }

        .fondo_botones img {
            filter: none;
            transform: none;
        }
    }


    .footer {
        background-color: #05072e;
        height: 80px;
        text-align: center;
        width: 100%;
        position: fixed;
        bottom: 0;
    }

    /* sm*/
    @media (min-width: 576px) and (max-width: 767.98px) {
        .menu {
            grid-template-columns: repeat(1, fr);
        }

        .hero {
            margin-top: 0;
        }

        .fondo_botones {
            width: min(90vw, 420px);
        }
    }

    /* md*/
    @media (min-width: 768px) {
        .menu {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            justify-items: center;
        }

        .hero {
            gap: 5px;
        }

        .fondo_botones {
            width: 100%;
        }
    }

    /* lg*/
    @media (min-width: 992px) {
        .menu {
            gap: clamp(24px, 3vw, 48px);
        }

        .fondo_botones {
            width: 100%;
            gap: 5%;
        }
    }

    /* xl*/
    @media (min-width: 1200px) {
        .fondo_botones {
            width: clamp(300px, 24vw, 360px);
        }
    }

    /* xxl*/
    @media (min-width: 1400px) {
        .menu {
            gap: 40px;
        }

        .fondo_botones {
            width: 360px;
        }
    }
</style>

<body class="fondo_secundario">
    <!--NOMBRE DEL MAIN = 'CONTENT'-->
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!----SWEETALERT--->
    <!----En el layout de cada caso (cliente y admin , .... ) , colocamos 
    el script del sweet alert para mantener mas limpio el codigo  , este luego heredara de 
    AppServiceProvider el toast con cada cada una de sus clases.---->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (t) => {
                t.onmouseenter = Swal.stopTimer;
                t.onmouseleave = Swal.resumeTimer;
            }
        });
    </script>
    <!---En este layout tendremos que incluir el toast.blade.php que esta en profile para que se accione la alerta del sweet alert.--->
    @include('profile.partials.toast')
</body>

</html>