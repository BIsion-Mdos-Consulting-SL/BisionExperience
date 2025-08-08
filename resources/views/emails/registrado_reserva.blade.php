<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo invitado registrado</title>
</head>

<body style="margin:0; padding:0; background-color:#0A0D40; font-family: Arial, sans-serif; color:#ffffff;">

    <div style="max-width:600px; margin:0 auto; padding:40px 30px; background-color:#0A0D40;">
        <h1 style="font-size:24px; font-weight:bold; margin-bottom:30px; text-align:center;">
            Nueva parada registrada
        </h1>

        <p style="font-size:16px; margin-bottom:15px;">
            <strong>Nombre:</strong> {{ $conductor->nombre }} {{ $conductor->apellido }}
        </p>

        <p style="font-size:16px; margin-bottom:15px;">
            <strong>Email:</strong> {{ $conductor->email }}
        </p>

        <p style="font-size:16px; margin-bottom:30px;">
            <strong>Evento:</strong> {{ $evento->nombre ?? 'N/A' }}
        </p>
    </div>
    <div style="width: 100%; text-align: center; background-color: #05072E; height: auto;">
        <img src="{{ asset('images/footer_bision.png') }}" alt="Footer Logo" style="width: 200px;">
    </div>

</body>

</html>