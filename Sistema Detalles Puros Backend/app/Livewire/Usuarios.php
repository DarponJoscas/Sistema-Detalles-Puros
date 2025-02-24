<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Usuarios extends Component
{
    #[Reactive] 
    public $usuario = '';

    #[Reactive] 
    public $contrasena_usuario = '';

    public function login()
    {
        $validated = $this->validate([
            'usuario' => 'required|string',
            'contrasena_usuario' => 'required|min:8',
        ]);

        $user = Usuario::where('name_usuario', $this->usuario)->first();

        if (!$user) {
            Log::warning('Usuario no encontrado', ['usuario' => $this->usuario]);
            $this->addError('usuario', 'Las credenciales no son correctas.');
            return;
        }

        if ($user->estado_usuario == 0) {
            Log::warning('Usuario inactivo', ['usuario' => $this->usuario]);
            $this->addError('usuario', 'El usuario está inactivo.');
            return;
        }

        if (!Hash::check($this->contrasena_usuario, $user->password)) {
            Log::warning('Contraseña incorrecta', ['usuario' => $this->usuario]);
            $this->addError('contrasena_usuario', 'Las credenciales no son correctas.');
            return;
        }

        Auth::login($user);

        session()->flash('mensaje', 'Bienvenido, ' . $user->name_usuario);
        return redirect()->route('dashboard');
    }

    public function register()
    {
        $validated = $this->validate([
            'usuario' => 'required|string|unique:usuarios,name_usuario',
            'contrasena_usuario' => 'required|min:8',
        ]);

        DB::beginTransaction();

        try {
            Usuario::create([
                'name_usuario' => $this->usuario,
                'password' => Hash::make($this->contrasena_usuario),
                'estado_usuario' => 1,
                'id_rol' => 1,
            ]);

            DB::commit();

            session()->flash('mensaje', 'Usuario registrado con éxito. Inicia sesión.');
            return redirect()->route('login');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar el usuario: ' . $e->getMessage());
            session()->flash('error', 'Hubo un problema al registrar el usuario.');
        }
    }

    public function render()
    {
        return view('livewire.usuarios')->layout('layouts.app');
    }
}
