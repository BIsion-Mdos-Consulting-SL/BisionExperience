<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Parada Exitoso</title>
</head>

<body style="margin:0; padding:0; background-color:#0A0D40; font-family: Arial, sans-serif; color: #ffffff; height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">

    <div style="width: 100%; max-width: 600px; padding: 40px 20px; text-align: center; background-color: #0A0D40; flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">

        <h2 style="font-size: clamp(22px, 6vw, 28px); font-weight: bold; margin-bottom: 20px;">
            ¡Gracias por reservar los coches!
        </h2>

        <p style="font-size: clamp(16px, 4.5vw, 20px); margin-bottom: 15px;">
            Hemos recibido tu información correctamente.
        </p>

        <p style="font-size: clamp(16px, 4.5vw, 20px); margin-bottom: 30px;">
            Te esperamos!!!
        </p>

        <div style="margin: 30px 0;">
            <a href="{{ route('login') }}" style="display: inline-block; padding: 12px 24px; background-color: #ffffff; color: #0A0D40; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: clamp(14px, 4vw, 18px);">
                Volver al inicio
            </a>
        </div>
    </div>

    <div style="width: 100%; text-align: center; background-color: #05072E; height: auto; position: fixed; bottom: 0; left: 0; z-index: 100;">
        <img src="{{ asset('images/footer_bision.png') }}" alt="Footer Logo" style="width: 200px; max-width: 80%; padding: 10px;">
    </div>

</body>

</html>