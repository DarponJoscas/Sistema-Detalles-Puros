<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Usuario;
use App\Models\Rol;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

class RegistroUsuarios extends Component
{
    use WithPagination;

    public $usuario, $contrasena_usuario, $id_rol, $estado_usuario;
    public $id_usuario, $name_usuario, $rol, $puros, $usuarios;

    public $page = 1;
    public $estadoUsuario = null;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    public $error_menssage;
    public $showModal = false;

    public $filtro_usuario = null;
    public $filtro_roles = null;
    public $showUpdateModal = false;
    public $showPasswordModal = false;

    public function getDatosUsuarios()
    {
        $results = DB::select("CALL GetUsuarios(?, ?)", [
            $this->filtro_usuario,
            $this->filtro_roles
        ]);

        $collection = collect($results)->map(function ($row) {
            return [
                'id_usuario' => $row->id_usuario ?? '',
                'name_usuario' => $row->name_usuario ?? '',
                'password' => $row->password ?? '',
                'rol' => $row->rol ?? '',
                'estado_usuario' => $row->estado_usuario ?? '',
            ];
        });

        $maxRecords = $collection->count();
        $currentPage = $this->getPage();
        $items = $collection->forPage($currentPage, $this->perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $maxRecords,
            $this->perPage,
            $currentPage,
            ['path' => request()->url()]
        );
    }

    public function editUser($id_usuario)
    {
        $this->id_usuario = $id_usuario;
        $usuario = Usuario::where('id_usuario', $id_usuario)->first();

        if ($usuario) {
            $this->id_rol = $usuario->id_rol;
            $this->name_usuario = $usuario->name_usuario;

            $rol = DB::table('roles')->where('id_rol', $usuario->id_rol)->first();
            if ($rol) {
                $this->rol = $rol->rol;
            }

            $this->showUpdateModal = true;
            $this->dispatch('open-update-modal');
        }
    }

    public function update()
    {
        $this->validate([
            'name_usuario' => 'required|string|max:255',
            'id_rol' => 'required|exists:roles,id_rol'
        ]);

        try {
            $usuario = Usuario::find($this->id_usuario);

            if ($usuario) {
                $usuario->update([
                    'name_usuario' => $this->name_usuario,
                    'id_rol' => $this->id_rol,
                    'updated_at' => now(),
                ]);

                $this->reset(['name_usuario', 'id_rol']);
                $this->showUpdateModal = false;
                $this->dispatch('hide-update-modal');
                $this->dispatch('refresh');
                session()->flash('success', 'Usuario actualizado correctamente.');
            } else {
                session()->flash('error', 'Usuario no encontrado para actualizar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function filtrarUsuario()
    {
        $this->resetPage();
    }

    public function updatePassword()
    {
        $this->validate([
            'contrasena_usuario' => 'required|min:8'
        ]);

        try {
            $usuario = Usuario::find($this->id_usuario);

            if ($usuario) {
                $usuario->update([
                    'password' => Hash::make($this->contrasena_usuario),
                    'updated_at' => now(),
                ]);

                $this->reset(['contrasena_usuario']);
                $this->showPasswordModal = false;
                $this->dispatch('hide-password-modal');
                $this->dispatch('refresh');
                session()->flash('success', 'Contraseña actualizada correctamente.');
            } else {
                session()->flash('error', 'Usuario no encontrado para actualizar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function openModalPassword($id_usuario)
    {
        $this->id_usuario = $id_usuario;
        $this->showPasswordModal = true;
        $this->dispatch('open-password-modal');
    }

    public function register()
    {
        DB::beginTransaction();

        try {
            $existingUser = Usuario::where('name_usuario', $this->name_usuario)->first();

            if ($existingUser) {
                session()->flash('error', 'El nombre de usuario ya está en uso.');
                return;
            }

            Usuario::create([
                'name_usuario' => $this->name_usuario,
                'password' => Hash::make($this->contrasena_usuario),
                'estado_usuario' => 1,
                'id_rol' => $this->id_rol
            ]);

            DB::commit();
            $this->reset(['name_usuario', 'contrasena_usuario', 'id_rol']);
            $this->dispatch('refresh');
            session()->flash('success', 'Usuario creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function deleteUsuario($id_usuario)
    {
        try {
            $usuario = Usuario::where('id_usuario', $id_usuario)->first();
            if ($usuario) {
                $usuario->estado_usuario = 0;
                $usuario->save();
                session()->flash('success', 'Se ha desactivado correctamente el usuario.');
                $this->dispatch('refresh');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar el usuario.');
            Log::error('Error en deleteUsuario: ' . $e->getMessage());
        }
    }

    public function reactivarUsuario($id_usuario)
    {
        try {
            $usuario = Usuario::where('id_usuario', $id_usuario)->first();
            if ($usuario) {
                $usuario->estado_usuario = 1;
                $usuario->save();
                session()->flash('success', 'Se ha activado correctamente el usuario.');
                $this->dispatch('refresh');
            } else {
                session()->flash('error', 'No se encontró el registro para activar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al activar el usuario.');
            Log::error('Error en reactivarUsuario: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view(
            'livewire.registro-usuarios',
            [
                'datosPaginados' => $this->getDatosUsuarios(),
                'roles' => Rol::all(),
                $this->usuarios = DB::table('usuarios')->get(['id_usuario', 'name_usuario']),
            ]
        )->extends('layouts.app')->section('content');
    }
}
