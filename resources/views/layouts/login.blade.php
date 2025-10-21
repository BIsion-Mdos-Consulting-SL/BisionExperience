<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bision-io</title>
    <!--FAVICON (LOGO NAVEGACION)-->
    <link rel="icon" type="image/png" href="{{asset('images/header_logo.png')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<style>
    /* ====== RESET Y BASE ====== */
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    html,
    body {
        overflow-x: hidden;
        margin: 0;
        padding: 0;
    }

    /* ====== SECCIONES PRINCIPALES ====== */
    .fondo_principal {
        background-color: #0A0D40;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .fondo_secundario {
        background-color: #EBEBEB;
        padding: 40px;
        border-radius: 10px;
        margin: auto;
    }

    .fondo {
        background-image: url("/storage/images/fondo.jpg");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100svh;
        display: flex;
        align-items: center;
        justify-items: center;
        justify-content: center;
        padding: 2rem;
        opacity: 1.5;
    }

    .hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
    }

    .logo-container {
        margin-bottom: 2rem;
    }

    /* ====== MENU Y BOTONES ====== */
    .menu {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 6rem;
        align-items: center;
        justify-content: center;
        text-align: center;
        margin-top: 2rem;
    }

    /* ====== ANIMACIÓN DE APARICIÓN ====== */
    /* @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(40px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    } */

    /* ====== CADA ITEM ====== */
    /* .menu-item {
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        opacity: 0;
        animation: fadeInUp 1.2s ease forwards;
        transition: transform 0.4s ease, filter 0.4s ease;
    } */

    /*Retraso progresivo (efecto secuencial) */
    /* .menu-item:nth-child(1) {
        animation-delay: 0.5s;
    }

    .menu-item:nth-child(2) {
        animation-delay: 0.6s;
    }

    .menu-item:nth-child(3) {
        animation-delay: 0.9s;
    }

    /* ====== IMAGEN ====== */
    /* .menu-item img {
        width: 280px;
        height: auto;
        border-radius: 16px;
        transition: all 0.6s ease;
    } */

    /* ====== TEXTO ====== */
    /* .menu-item p {
        font-size: 26px;
        font-family: 'Poppins', sans-serif;
        color: #fff;
        font-weight: 600;
        text-align: center;
    } */
    */

    /**FONDO BOTONES , (NO DEPENDE DEL MENU) */
    .fondo_botones {
        background-color: #EBEBEB;
        padding: 10px;
        border-radius: 10px;
        color: black;
        transition: background-color 0.3s ease;
        width: 200px;
        height: 180px;
        text-align: center;
        font-size: 20px;
        text-decoration: none;
    }

    .fondo_botones:hover {
        background-color: #d6d6d6;
    }

    /* ====== RESPONSIVE ====== */
    @media (max-width: 768px) {

        html,
        body {
            overflow-x: hidden !important;
            overflow-y: hidden !important;
        }

        .logo-container img {
            max-width: 80%;
            height: auto;
            margin-top: 2px;
            overflow-y: hidden;
        }

        .fondo_botones {
            width: 100px;
            height: 80px;
        }
    }

    /* ====== BOTONES Y FORM ====== */
    .btn_color {
        background-color: #0A0D40;
        color: white;
        border-radius: 5px;
        text-align: center;
        padding: 10px;
    }

    .btn_color:hover {
        filter: brightness(1.05);
    }

    .validacion-mal {
        border: 1px solid red !important;
    }

    .validacion-bien {
        border: 1px solid green !important;
    }

    /* ====== FOOTER ====== */
    .footer {
        background-color: #05072e;
        height: 80px;
        width: 100%;
        position: fixed;
        bottom: 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>