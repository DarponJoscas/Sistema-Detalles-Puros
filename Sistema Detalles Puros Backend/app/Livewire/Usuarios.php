<?php

namespace App\Livewire;

use Livewire\Component;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuario;
use App\Models\Rol;

class Usuarios extends Component
{
    public $usuario = '';
    public $contrasena_usuario = '';
    public $id_rol = '';
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

        try {
            if ($token = JWTAuth::attempt(['name_usuario' => $this->usuario, 'password' => $this->contrasena_usuario])) {
                session(['token' => $token]);

                switch ($user->id_rol) {
                    case 1:
                        return redirect()->route('dashboard');
                    case 2:
                        return redirect()->route('produccion');
                    case 3:
                        return redirect()->route('empaque');
                    default:
                        return redirect()->route('login');
                }
            } else {
                $this->error_message = 'Las credenciales no son correctas.';
                $this->dispatch('swal', json_encode([
                    'icon'  => 'error',
                    'title' => 'Error',
                    'text'  => $this->error_message
                ]));
            }
        } catch (\Exception $e) {
            Log::error('Error al generar el token: ' . $e->getMessage());
            $this->error_message = 'Hubo un problema al generar el token.';
            $this->dispatch('swal', json_encode([
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $this->error_message
            ]));
        }
    }

    public function register()
    {
        if (Auth::user()->id_rol !== 1) {
            $this->error_message = 'No tienes permisos para registrar usuarios.';
            $this->dispatch('swal', json_encode([
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $this->error_message
            ]));
            return;
        }

        $validated = $this->validate([
            'usuario' => 'required|string|unique:usuarios,name_usuario',
            'contrasena_usuario' => 'required|min:8',
            'id_rol' => 'required|exists:roles,id_rol',
        ]);

        DB::beginTransaction();

        try {
            Usuario::create([
                'name_usuario' => $this->usuario,
                'password' => Hash::make($this->contrasena_usuario),
                'estado_usuario' => 1,
                'id_rol' => $this->id_rol,
            ]);

            DB::commit();

            $this->success_message = 'Usuario registrado con éxito. Inicia sesión.';
            $this->dispatch('swal', json_encode([
                'icon'  => 'success',
                'title' => 'Éxito',
                'text'  => $this->success_message
            ]));
            $this->reset(['usuario', 'contrasena_usuario', 'id_rol']);
            $this->dispatch('close-modal');

            return redirect()->route('login');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar el usuario: ' . $e->getMessage());
            $this->error_message = 'Hubo un problema al registrar el usuario.';
            $this->dispatch('swal', json_encode([
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $this->error_message
            ]));
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
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

    public function render() { return view('livewire.usuarios',
        ['roles' => Rol::all()])
        ->extends('layouts.app')->section('content'); }
}
