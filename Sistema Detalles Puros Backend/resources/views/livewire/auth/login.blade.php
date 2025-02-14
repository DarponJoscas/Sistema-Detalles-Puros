<!-- resources/views/livewire/auth/login.blade.php -->
@extends('layouts.app') <!-- Esto extiende el layout 'app.blade.php' -->

@section('content') <!-- Aquí colocas el contenido dentro de la sección 'content' del layout -->
    <div class="login-container">
        <div class="login-box">
            <h2>Bienvenido de nuevo</h2>
            <p class="subtitle">Inicia sesión para continuar</p>
            <form wire:submit.prevent="login">
                <div class="input-group">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" wire:model="usuario" placeholder="Ingresa tu usuario" autocomplete="off">
                </div>
                <div class="input-group">
                    <label for="contrasena_usuario">Contraseña</label>
                    <input type="password" id="contrasena_usuario" wire:model="contrasena_usuario" placeholder="Ingresa tu contraseña" autocomplete="off">
                </div>
                <button type="submit" class="btn-submit">Iniciar sesión</button>
            </form>

            @error('usuario') <span class="error">{{ $message }}</span> @enderror
            @error('contrasena_usuario') <span class="error">{{ $message }}</span> @enderror
        </div>
    </div>

    <style>
        /* Fondo de la página */
        body {
            background: linear-gradient(135deg, #56CCF2, #2F80ED);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Contenedor del formulario */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
        }

        /* Caja de inicio de sesión */
        .login-box {
            background-color: white;
            padding: 40px 35px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Títulos */
        .login-box h2 {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
        }

        /* Grupos de campos de entrada */
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: #2F80ED;
            outline: none;
        }

        /* Botón de inicio de sesión */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #2F80ED;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #1D6BB4;
            transform: translateY(-2px);
        }

        .btn-submit:active {
            transform: translateY(1px);
        }

        /* Mensajes de error */
        .error {
            display: block;
            color: #f44336;
            font-size: 14px;
            margin-top: 10px;
        }

    </style>
@endsection
