<div>
    <style>
        #login {
            background: rgb(0, 0, 0);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        #login .form-container {
            background: #ecf0f3;
            font-family: 'Nunito', sans-serif;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            max-width: 300px;
            width: 100%;
        }

        #login .form-icon {
            background-color: rgb(0, 0, 0);
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            box-shadow: 7px 7px 10px #cbced1, -7px -7px 10px #fff;
            overflow: hidden;
            margin: 0 auto 15px;
        }

        #login .form-icon img {
            width: 90%;
            height: auto;
            object-fit: contain;
        }

        #login .title {
            color: rgb(2, 0, 2);
            font-size: 22px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        #login .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        #login label {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            display: block;
        }

        #login .form-control {
            color: #333;
            background: #ecf0f3;
            font-size: 14px;
            height: 30px;
            padding: 10px;
            border: none;
            border-radius: 25px;
            box-shadow: inset 4px 4px 6px #cbced1, inset -4px -4px 6px #fff;
            width: 93%;
        }

        #login .form-control:focus {
            outline: none;
            box-shadow: inset 4px 4px 6px #cbced1, inset -4px -4px 6px #fff;
        }

        #login .btn {
            color: #fff;
            background-color: rgb(10, 10, 10);
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            width: 100%;
            padding: 10px;
            border-radius: 20px;
            box-shadow: 4px 4px 6px #cbced1, -4px -4px 6px #fff;
            border: none;
            transition: all 0.4s ease;
        }

        #login .btn:hover {
            letter-spacing: 1px;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .success-message {
            color: #28a745;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
    <div id="login">
        <div class="form-container">
            <div class="form-icon">
                <img src="{{ asset('images/plasencia.png') }}" alt="Logo">
            </div>
            <h2 class="title">Iniciar Sesión</h2>
            <form wire:submit.prevent="login">
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" wire:model="usuario" id="usuario" class="form-control"
                        placeholder="Ingrese su usuario">
                    @error('usuario')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contrasena_usuario">Contraseña:</label>
                    <input type="password" wire:model="contrasena_usuario" id="contrasena_usuario" class="form-control"
                        placeholder="Ingrese su contraseña">
                    @error('contrasena_usuario')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                @if ($error_message)
                    <p class="error-message text-center">{{ $error_message }}</p>
                @endif

                @if ($success_message)
                    <p class="success-message text-center">{{ $success_message }}</p>
                @endif

                <button type="submit" class="btn">Ingresar</button>
            </form>
        </div>
    </div>
</div>
