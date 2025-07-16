<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bision-io</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<style>
  .header {
    background-color: #05072e;
  }

  .header_logo {
    width: 50px;
  }

  .fondo_principal {
    background-color: #0A0D40;
  }

  .fondo_secundario {
    background-color: #EBEBEB;
    min-height: 100vh;
  }

  .btn_color {
    background-color: #0A0D40;
    color: white;
    border-radius: 5px 5px 5px 5px;
    text-align: center;
    padding: 10px;
  }

  .btn_secundario {
    background-color: #EBEBEB;
    border: 1px solid #0A0D40;
    border-radius: 5px 5px 5px 5px;
    color: #0A0D40;
    text-align: center;
    padding: 10px;
  }

  .texto {
    color: #0A0D40;
  }

  .tooltip-container {
    position: relative;
  }

  .tooltip-texto {
    display: none;
    position: absolute;
    bottom: 100%;
    /* Muestra arriba del <p> */
    left: 50%;
    /**Centra al medio de la card el contenedor. */
    transform: translateX(-50%);
    /**Ayuda a centrar contendor con el de arriba. */
    background-color: black;
    color: white;
    padding: 15px;
    width: 300px;
    border: 1px solid #ccc;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 10px;
  }

  /*Cuando pasas por el contenedor , aplica estilos sobre elemento hijo. */
  .tooltip-container:hover .tooltip-texto {
    display: block;
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

<body class="fondo_principal">
  <!--NOMBRE DEL MAIN = 'CONTENT'-->
  @yield('content')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>