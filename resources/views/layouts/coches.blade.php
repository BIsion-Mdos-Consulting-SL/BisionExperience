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
    .fondo_principal {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        min-height: 100vh;
    }

    .fondo_titulo {
        background-color: #36c320ff;
        padding: 10px;
        text-align: center;
        font-size: 20px;
        font-weight: bold;
    }

    .card-coche {
        margin: 15px;
    }

    .img-coche {
        max-height: 190px;
        width: 400px;
        object-fit: cover;
    }

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

    .banner-rotator {
        position: relative;
        width: 100%;
        height: 100%;
        /* centrado vertical/horizontal ya lo hace .footer */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .banner-item {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        max-height: 60px;
        /* cabe en tu footer de 80px */
        height: 60px;
        width: auto;
        object-fit: contain;
        opacity: 0;
        transition: opacity .6s ease;
        pointer-events: none;
        /* no cliqueable, opcional */
    }

    .banner-item.active {
        opacity: 1;
        pointer-events: auto;
        /* si quieres que video sea clickeable, quita este */
    }
</style>

<body>
    <!--NOMBRE DEL MAIN = 'CONTENT'-->
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>