@extends('layouts.login')
@section('content')
<div class="fondo_principal">
    <div class="p-5 d-sm-block d-md-block d-lg-flex flex-wrap justify-content-center align-items-center gap-5">
        <div class="mb-5 mb-sm-5 col-sm-12 col-md-12 col-lg-6 text-center" style="background-color: #0A0D40; border-radius: 10px;">
            <img src="{{asset('images/logo.png')}}" style="width: 100%; border-radius: 10px; margin-bottom: 30px;">
            <h4 style="color: white;">Caravana Sostenible Mayo 2025</h4>
        </div>
        <div class="col-sm-12 p-sm-5 col-md-12 col-lg-5 fondo_secundario mb-sm-5">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            <form id="form" method="POST" action="{{ route('login') }}">
                @csrf
                <!---USUARIO--->
                <div class="mb-3">
                    <x-input-label for="email" :value="__('Usuario')" class="mb-3 fw-bold" />
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-at-fill"></i></span>
                        <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" class="form-control" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!--CONTRASEÑA--->
                <div class="mb-3">
                    <x-input-label for="password" :value="__('Contraseña')" class="mb-3 fw-bold" />
                    <div class="input-group">
                        <span id="toggle-password" class="input-group-text"><i class="bi bi-eye-fill"></i></span>
                        <x-text-input id="password" type="password" name="password" required autocomplete="current-password" class="form-control" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!----FUNCION PARA MOSTRAR/OCULTAR CONTRASEÑA(JS)---->
                <script>
                    const inputPassword = document.getElementById('password');
                    const togglePassword = document.getElementById('toggle-password');
                    const icon = togglePassword.querySelector('i');

                    function mostrar() {
                        if (inputPassword.type === 'password') {
                            inputPassword.type = 'text';
                            icon.classList.remove('bi-eye-fill');
                            icon.classList.add('bi-eye-slash-fill');

                        } else {
                            inputPassword.type = 'password';
                            icon.classList.remove('bi-eye-slash-fill');
                            icon.classList.add('bi-eye-fill');
                        }
                    }

                    togglePassword.addEventListener('click', () => {
                        mostrar();
                    })
                </script>


                <!-- Remember Me -->
                <div class="mb-3">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2  text-gray-600" style="font-size: small;">
                            He leído y acepto las
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalNormas">normas de uso</a> y la
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalPolitica">política de privacidad</a>.
                        </span>
                    </label>
                </div>

                <!---VALIDACION JS PARA PODER LOGUEARTE-->
                <script>
                    const remember = document.getElementById('remember_me');
                    const form = document.getElementById('form');

                    function validarCheckbox() {
                        if (!remember.checked) {
                            Swal.fire({
                                title: "Marque casilla para continuar.",
                                icon: "warning",
                                iconColor: "#05072e", // color del icono
                                confirmButtonText: "Aceptar",
                                confirmButtonColor: "#05072e" // color del botón
                            });
                            return false;
                        }
                        return true;
                    }

                    form.addEventListener('submit', (e) => {
                        if (!validarCheckbox()) {
                            e.preventDefault(); //Evita el envio del formulario.
                        }
                    })
                </script>

                <!--AQUI VA EKL MODAL DE NORMAS DE USO Y POLITICAS DE PRIVACIDAD-->

                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-start">
                        @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" style="font-size: small; text-decoration: none;">
                            {{ __('Olvido la contraseña?') }}
                        </a>
                        @endif

                        <a style="font-size: small; text-decoration: none; margin-left: 10px;" href="{{route('register')}}">Registrarse</a>
                    </div>

                    <x-primary-button class="btn_color">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
    <footer class="footer">
        <img class="m-auto" src="{{asset('images/footer_bision.png')}}" style="width: 200px;">
    </footer>
    @include('auth.politica_privacidad')
    @endsection