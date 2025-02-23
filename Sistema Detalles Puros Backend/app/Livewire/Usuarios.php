<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Log;

class Usuarios extends Component
{
    public $usuario;
    public $contrasena_usuario;

    protected $rules = [
        'usuario' => 'required|string',
        'contrasena_usuario' => 'required|min:8',
    ];

    public function login()
    {
        // Registro del intento de inicio de sesión
        Log::info('Intento de inicio de sesión', ['usuario' => $this->usuario]);

        // Validación de datos
        $this->validate();

        // Verificación de existencia de usuario
        $user = Usuario::where('name_usuario', $this->usuario)->first();

        if ($user) {
            Log::info('Usuario encontrado', ['usuario' => $this->usuario]);

            // Validación de estado activo del usuario
            if ($user->estado_usuario == 0) {
                Log::warning('Usuario inactivo', ['usuario' => $this->usuario]);
                session()->flash('error', 'El usuario está inactivo.');
                return;
            }

            // Verificación de contraseña
            if (Hash::check($this->contrasena_usuario, $user->password)) {
                Log::info('Contraseña correcta', ['usuario' => $this->usuario]);

                // Autenticación del usuario
                Auth::login($user);

                // Redirección a dashboard sin generar un token JWT
                session()->flash('mensaje', 'Bienvenido, ' . $user->name_usuario);
                return redirect()->route('dashboard');
            } else {
                Log::warning('Contraseña incorrecta', ['usuario' => $this->usuario]);
                session()->flash('error', 'Las credenciales no son correctas');
            }
        } else {
            Log::warning('Usuario no encontrado', ['usuario' => $this->usuario]);
            session()->flash('error', 'Las credenciales no son correctas');
        }
    }

    public function render()
    {
        return view('livewire.usuarios')->layout('layouts.app');
    }
}

