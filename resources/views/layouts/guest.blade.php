<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bision-io</title>

    <!-- Fonts -->
     <!--FAVICON (LOGO NAVEGACION)-->
    <link rel="icon" type="image/png" href="{{asset('images/header_logo.png')}}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased" style="background-color:#0A0D40;">
    <div class="min-h-screen flex flex-col justify-center items-center p-sm-5 pb-24">
        <div>
            <a href="{{ route('login')}}">
                <img class="w-20 h-15 fill-current text-gray-500" src="{{ asset('/images/header_logo.png') }}" alt="">
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
    <!-- FOOTER dentro del contenedor principal -->
    <footer style="background-color: #05072e; width: 100%; display: flex; justify-content: center; position: fixed; bottom: 0;">
        <img src="{{ asset('images/footer_bision.png') }}" style="width: 200px;">
    </footer>
</body>

</html>