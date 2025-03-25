<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;

class Usuarios extends Component
{
    public $usuario = '';
    public $contrasena_usuario = '';
    public $error_message = '';
    public $success_message = '';

    public function login()
    {
        $validator = Validator::make(
            [
                'usuario' => $this->usuario,
                'contrasena_usuario' => $this->contrasena_usuario,
            ],
            [
                'usuario' => 'required|string',
                'contrasena_usuario' => 'required|min:8',
            ]
        );

        if ($validator->fails()) {
            $this->error_message = 'Las credenciales no son correctas.';
            $this->dispatch('swal', json_encode([
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $this->error_message
            ]));
            return;
        }

        $user = Usuario::where('name_usuario', $this->usuario)->first();

        if (!$user) {
            Log::warning('Intento de inicio de sesión con usuario no registrado', ['usuario' => $this->usuario]);
            $this->error_message = 'Las credenciales no son correctas.';
            $this->dispatch('swal', json_encode([
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $this->error_message
            ]));
            return;
        }

        if ($user->estado_usuario == 0) {
            Log::warning('Intento de inicio de sesión con usuario inactivo', ['usuario' => $this->usuario]);
            $this->error_message = 'El usuario está inactivo.';
            $this->dispatch('swal', json_encode([
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $this->error_message
            ]));
            return;
        }

        if (!Hash::check($this->contrasena_usuario, $user->password)) {
            Log::warning('Contraseña incorrecta para el usuario', ['usuario' => $this->usuario]);
            $this->error_message = 'Las credenciales no son correctas.';
            $this->dispatch('swal', json_encode([
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $this->error_message
            ]));
            return;
        }

        Auth::login($user);

        switch ($user->id_rol){
            case 1:
                return redirect()->route('dashboard');
            case 2:
                return redirect()->route('produccion');
            case 3:
                return redirect()->route('empaque');
            default:
                return redirect()->route('login');
        }
    }

    public function logout()
    {
        try {
            Auth::logout();

            $this->success_message = 'Has cerrado sesión exitosamente.';
            $this->dispatch('swal', json_encode([
                'icon'  => 'success',
                'title' => 'Éxito',
                'text'  => $this->success_message
            ]));
        } catch (\Exception $e) {
            Log::error('Error al cerrar sesión: ' . $e->getMessage());
            $this->error_message = 'Hubo un problema al cerrar sesión.';
            $this->dispatch('swal', json_encode([
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $this->error_message
            ]));
        }
        return redirect()->route('login');
    }

    public function render()
    {
        return view(
            'livewire.usuarios'
        )
            ->extends('layouts.app')->section('content');
    }
}
