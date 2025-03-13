<?php

namespace App\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;


class Usuarios extends Component
{
    use WithPagination;

    public $usuario_id, $usuario, $contrasena_usuario, $id_rol, $name_usuario, $rol, $estado_usuario;
    public $page = 1;

    public $estadoUsuario = null;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    public function getDatosUsuarios()
    {
        $query = "CALL GetUsuarios(?)";
        $results = DB::select($query, [$this->estadoUsuario]);

        // Creamos la colección de resultados
        $collection = collect($results)->map(function ($row) {
            return [
                'id_usuario' => $row->id_usuario ?? '',
                'name_usuario' => $row->name_usuario ?? '',
                'password' => $row->password ?? '',
                'rol' => $row->rol ?? '',
                'estado_usuario' => $row->estado_usuario ?? '',
            ];
        });

        // Total de registros
        $registrosUsuarios = $collection->count();

        // Página actual
        $currentPage = $this->getPage();

        // Filtramos los registros de la página actual
        $items = $collection->forPage($currentPage, $this->perPage)->values();

        // Retornamos la paginación manual con LengthAwarePaginator
        return new LengthAwarePaginator(
            $items,
            $registrosUsuarios,
            $this->perPage,
            $currentPage,
            ['path' => request()->url()]
        );
    }

    public function editUser($id_usuario)
    {
        $usuario = Usuario::find($id_usuario);

        if ($usuario) {
            $this->usuario_id = $usuario->id_usuario;
            $this->name_usuario = $usuario->name_usuario;
            $this->rol = $usuario->id_rol;
            $this->estado_usuario = $usuario->estado_usuario;
        } else {
            session()->flash('error', 'Usuario no encontrado.');
        }
    }

    public function createUser()
    {
        $this->validate([
            'name_usuario' => 'required|string|max:255',
            'contrasena_usuario' => 'required|string|min:8',
            'rol' => 'required|exists:roles,id_rol'
        ]);

        try {
            Usuario::create([
                'name_usuario' => $this->name_usuario,
                'password' => Hash::make($this->contrasena_usuario),
                'estado_usuario' => 1,
                'id_rol' => $this->rol
            ]);

            $this->reset(['name_usuario', 'contrasena_usuario', 'rol']);
            $this->dispatch('close-modal');
            $this->dispatch('saved');

            session()->flash('success', 'Usuario creado exitosamente.');
            return redirect()->route('usuarios');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error');
            session()->flash('error', 'Ocurrió un error al procesar la solicitud.');
        }
    }

    public function deleteUsuario($id_usuario)
    {
        try {
            $usuario = Usuario::where('id_usuario', $id_usuario)->first();
            if ($usuario) {
                $usuario->estado_usuario = 0;
                $usuario->save();
                $this->dispatchBrowserEvent('usuario-actualizado'); // o cualquier evento de tu elección

                session()->flash('success', 'Se ha desactivado correctamente el puro.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar el puro.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    public function reactivarUsuario($id_usuario)
    {
        try {
            $usuario = Usuario::where('id_usuario', $id_usuario)->first();
            if ($usuario) {
                $usuario->estado_usuario = 1;
                $usuario->save();
                $this->dispatchBrowserEvent('usuario-actualizado'); // o cualquier evento de tu elección
                session()->flash('success', 'Se ha activado correctamente el puro.');
            } else {
                session()->flash('error', 'No se encontró el registro para activar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar el puro.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view(
            'livewire.usuarios',
            [
                'datosPaginados' => $this->getDatosUsuarios(),
                'roles' => Rol::all()
            ]
        )->extends('layouts.app')->section('content');
    }
}
