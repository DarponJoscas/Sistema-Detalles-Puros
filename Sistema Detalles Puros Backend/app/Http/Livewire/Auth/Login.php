<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $usuario, $contrasena_usuario;

    // Reglas de validación para los campos
    protected $rules = [
        'usuario' => 'required',
        'contrasena_usuario' => 'required|min:6',
    ];

    // Método para validar y realizar el login
    public function login()
    {
        // Validación
        $this->validate();  

        // Intentar autenticar con los datos proporcionados
        if (Auth::attempt(['usuario' => $this->usuario, 'password' => $this->contrasena_usuario])) {
            session()->regenerate();  // Regenerar sesión
            return redirect()->route('welcome');  // Redirigir al welcome, cambia esta ruta por la que prefieras
        } else {
            // Si las credenciales son incorrectas, agregar error
            $this->addError('usuario', 'Las credenciales no son correctas.');
        }
    }

    // Renderiza la vista Livewire
    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.app');  // Asegúrate de que 'layouts.app' es correcto
    }

}
