<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Confirmación Evento</title>
</head>

<body style="margin:0; padding:0; background-color:#0A0D40; font-family: Arial, sans-serif; color:#ffffff; overflow-x:hidden;">

    <!-- Contenedor principal centrado y adaptable -->
    <div style="width:100%; max-width:600px; margin:0 auto; padding:40px 20px; background-color:#0A0D40; box-sizing:border-box;">

        <h2 style="font-size:26px; font-weight:bold; margin-bottom:24px; text-align:center; color:#ffffff;">
            Confirmación de Asistencia al Evento
        </h2>

        <p style="font-size:17px; margin-bottom:24px; text-align:center;">
            Haz clic en el siguiente botón para confirmar tu asistencia al evento:
        </p>

        <!-- Botón centrado y responsive -->
        <div style="text-align:center; margin:36px 0;">
            <a href="{{ $url }}" style="display:inline-block; padding:16px 32px; background-color:#ffffff; color:#0A0D40; text-decoration:none; border-radius:8px; font-weight:bold; font-size:18px; max-width:100%; box-sizing:border-box;">
                Confirmar Asistencia
            </a>
        </div>

        <p style="font-size:16px; margin-bottom:30px; text-align:center;">
            Un saludo y muchas gracias.
        </p>
    </div>

    <div style="width: 100%; text-align: center; background-color: #05072E; height: auto;">
        <img src="{{ asset('images/footer_bision.png') }}" alt="Footer Logo" style="width: 200px;">
    </div>

</body>

</html>