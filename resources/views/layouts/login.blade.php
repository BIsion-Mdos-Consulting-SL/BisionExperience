<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bision-io</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<style>
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
    }

    .btn_color {
        background-color: #0A0D40;
        color: white;
        border-radius: 5px;
        text-align: center;
        padding: 10px;
    }

    .validacion-mal {
        border: 1px solid red !important;
    }

    .validacion-bien {
        border: 1px solid green !important;
    }

    .footer {
        background-color: #05072e;
        height: 80px;
        text-align: center;
        width: 100%;
        position: fixed;
        bottom: 0;
    }
</style>

<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>