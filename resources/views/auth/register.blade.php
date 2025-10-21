@extends('layouts.login')
@section('content')
<x-guest-layout>
    <form method="POST" id="formulario" action="{{ route('register') }}" class="col-12">
        @csrf

        <!-- Name (opcional) -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" class="block mt-1 w-full validar" type="text" name="name" :value="old('name')" autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
       

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full validar"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="username" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" class="fw-bold" />

            <div class="input-group">
                <x-text-input type="password" name="password" autocomplete="new-password" class="form-control password validar" />
                <span class="input-group-text toggle-password" style="cursor: pointer;">
                    <i class="bi bi-eye-fill"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label  for="password_confirmation" :value="__('Confirmar contraseña')" />
            <div class="input-group">
                <x-text-input type="password" name="password_confirmation" autocomplete="new-password" class="form-control password validar" />
                <span class="input-group-text toggle-password" style="cursor: pointer;">
                    <i class="bi bi-eye-fill"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!---SWEETALERT PARA EL CORREO-->
        @if ($errors->has('email'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    iconColor: "#05072e",
                    title: 'Correo no existe en la BD',
                    confirmButtonColor: "#05072e"
                });
            });
        </script>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle de contraseña
                const toggles = document.querySelectorAll('.toggle-password');
                toggles.forEach(toggle => {
                    toggle.addEventListener('click', function() {
                        const input = this.previousElementSibling;
                        const icon = this.querySelector('i');

                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.remove('bi-eye-fill');
                            icon.classList.add('bi-eye-slash-fill');
                        } else {
                            input.type = 'password';
                            icon.classList.remove('bi-eye-slash-fill');
                            icon.classList.add('bi-eye-fill');
                        }
                    });
                });

                // Validación del formulario
                const form = document.getElementById('formulario');
                const inputs = document.querySelectorAll('.validar');

                form.addEventListener('submit', function(e) {
                    // validación front (rápida) para UX; el back manda de verdad
                    let valido = true;

                    inputs.forEach(function(input) {
                        if (!input.value || input.value.trim() === "") {
                            input.classList.add('validacion-mal');
                            input.classList.remove('validacion-bien');
                            valido = false;
                        } else {
                            input.classList.remove('validacion-mal');
                            input.classList.add('validacion-bien');
                        }
                    });

                    if (!valido) {
                        e.preventDefault();
                    }
                });

                // Eliminar clase de error en tiempo real
                inputs.forEach(function(input) {
                    input.addEventListener('input', function() {
                        if (input.value.trim() !== "") {
                            input.classList.remove('validacion-mal');
                        }
                    });
                });
            });
        </script>

        <div class="flex items-center justify-end mt-4 gap-2">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Ya registrado?') }}
            </a>

            <button type="submit" class="ms-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 btn_color">
                {{ __('Registrarse') }}
            </button>
        </div>
    </form>
</x-guest-layout>
@endsection