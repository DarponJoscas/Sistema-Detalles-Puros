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

    protected $listeners = [
        'confirmarEliminacionUsuario' => 'confirmarEliminacionUsuario',
    ];

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
        } else {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Usuario no encontrado.',
                'icon' => 'error',
            ]));
        }
    }

    public function update()
    {
        $validator = Validator::make(
            [
                'name_usuario' => $this->name_usuario,
                'id_rol' => $this->id_rol,
            ],
            [
                'name_usuario' => 'required|string|max:255',
                'id_rol' => 'required|exists:roles,id_rol',
            ],
            [
                'name_usuario.required' => 'El nombre de usuario es obligatorio.',
                'name_usuario.string' => 'El nombre de usuario debe ser una cadena de texto.',
                'name_usuario.max' => 'El nombre de usuario no puede exceder los 255 caracteres.',
                'id_rol.required' => 'El rol es obligatorio.',
                'id_rol.exists' => 'El rol seleccionado no existe.',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error de Validación',
                'text' => $validator->errors()->first(),
                'icon' => 'error',
            ]));
            return;
        }

        DB::beginTransaction();

        try {
            $usuario = Usuario::find($this->id_usuario);

            if ($usuario) {
                $usuario->update([
                    'name_usuario' => $this->name_usuario,
                    'id_rol' => $this->id_rol,
                    'updated_at' => now(),
                ]);

                DB::commit();

                $this->reset(['name_usuario', 'id_rol']);
                $this->showUpdateModal = false;
                $this->dispatch('hide-update-modal');
                $this->dispatch('refresh');

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Usuario actualizado correctamente.',
                    'icon' => 'success',
                ]));
            } else {
                $this->dispatch('swal', json_encode([
                    'title' => 'Error',
                    'text' => 'Usuario no encontrado para actualizar.',
                    'icon' => 'error',
                ]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage(),
                'icon' => 'error',
            ]));
        }
    }


    public function filtrarUsuario()
    {
        $this->resetPage();
    }

    public function updatePassword()
    {
        $validator = Validator::make(
            [
                'contrasena_usuario' => $this->contrasena_usuario,
            ],
            [
                'contrasena_usuario' => 'required|string|min:8',
            ],
            [
                'contrasena_usuario.required' => 'La contraseña es obligatoria.',
                'contrasena_usuario.string' => 'La contraseña debe ser una cadena de texto.',
                'contrasena_usuario.min' => 'La contraseña debe tener al menos 8 caracteres.',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error de Validación',
                'text' => $validator->errors()->first(),
                'icon' => 'error',
            ]));
            return;
        }

        DB::beginTransaction();

        try {
            $usuario = Usuario::find($this->id_usuario);

            if ($usuario) {
                $usuario->update([
                    'password' => Hash::make($this->contrasena_usuario),
                    'updated_at' => now(),
                ]);

                DB::commit();

                $this->reset(['contrasena_usuario']);
                $this->showPasswordModal = false;
                $this->dispatch('hide-password-modal');
                $this->dispatch('refresh');

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Contraseña actualizada correctamente.',
                    'icon' => 'success',
                ]));
            } else {
                $this->dispatch('swal', json_encode([
                    'title' => 'Error',
                    'text' => 'Usuario no encontrado para actualizar.',
                    'icon' => 'error',
                ]));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage(),
                'icon' => 'error',
            ]));
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
        $validator = Validator::make(
            [
                'name_usuario' => $this->name_usuario,
                'contrasena_usuario' => $this->contrasena_usuario,
                'id_rol' => $this->id_rol,
            ],
            [
                'name_usuario' => 'required|string|max:255|unique:usuarios,name_usuario',
                'contrasena_usuario' => 'required|string|min:8',
                'id_rol' => 'required|integer|exists:roles,id_rol',
            ],
            [
                'name_usuario.required' => 'El nombre de usuario es obligatorio.',
                'name_usuario.string' => 'El nombre de usuario debe ser una cadena de texto.',
                'name_usuario.max' => 'El nombre de usuario no puede superar los 255 caracteres.',
                'name_usuario.unique' => 'El nombre de usuario ya está en uso.',
                'contrasena_usuario.required' => 'La contraseña es obligatoria.',
                'contrasena_usuario.string' => 'La contraseña debe ser una cadena de texto.',
                'contrasena_usuario.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'id_rol.required' => 'El ID de rol es obligatorio.',
                'id_rol.integer' => 'El ID de rol debe ser un número entero.',
                'id_rol.exists' => 'El rol seleccionado no existe.',
            ]
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error de Validación',
                'text' => $validator->errors()->first(),
                'icon' => 'error',
            ]));
            return;
        }

        DB::beginTransaction();

        try {
            $existingUser = Usuario::where('name_usuario', $this->name_usuario)->first();

            if ($existingUser) {
                $this->dispatch('swal', json_encode([
                    'title' => 'Error',
                    'text' => 'El nombre de usuario ya está en uso.',
                    'icon' => 'error',
                ]));
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
            $this->dispatch('swal', json_encode([
                'title' => 'Éxito',
                'text' => 'Usuario creado correctamente.',
                'icon' => 'success',
            ]));
            $this->dispatch('refresh');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage(),
                'icon' => 'error',
            ]));
        }
    }


    public function deleteUsuario($id_usuario)
    {
        $validator = Validator::make(
            ['id_usuario' => $id_usuario],
            ['id_usuario' => 'required|integer|exists:usuarios,id_usuario']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'ID no válido',
                'text' => 'No se encontró el usuario especificado.',
                'icon' => 'error',
            ]));
            return;
        }

        $this->dispatch('swalConfirmDelete', json_encode([
            'title' => '¿Estás seguro?',
            'text' => '¿Realmente deseas desactivar este usuario?',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'confirmButtonText' => 'Sí, desactivar',
            'cancelButtonText' => 'No, cancelar',
            'idUsuario' => $id_usuario,
        ]));
    }

    public function confirmarEliminacionUsuario($id_usuario)
    {
        try {
            $usuario = Usuario::where('id_usuario', $id_usuario)->first();
            if ($usuario) {
                $usuario->estado_usuario = 0;
                $usuario->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha desactivado correctamente el usuario.',
                    'icon' => 'success',
                ]));

                $this->dispatch('refresh');
            } else {
                $this->dispatch('swal', json_encode([
                    'title' => 'Error',
                    'text' => 'No se encontró el registro para desactivar.',
                    'icon' => 'error',
                ]));
            }
        } catch (\Exception $e) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al desactivar el usuario.',
                'icon' => 'error',
            ]));

            Log::error('Error en confirmarEliminacionUsuario: ' . $e->getMessage());
        }
    }


    public function reactivarUsuario($id_usuario)
    {
        try {
            $usuario = Usuario::where('id_usuario', $id_usuario)->first();

            if ($usuario) {
                $usuario->estado_usuario = 1;
                $usuario->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha activado correctamente el usuario.',
                    'icon' => 'success',
                ]));
                $this->dispatch('refresh');
            } else {
                $this->dispatch('swal', json_encode([
                    'title' => 'Error',
                    'text' => 'No se encontró el registro para activar.',
                    'icon' => 'error',
                ]));
            }
        } catch (\Exception $e) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Error al activar el usuario.',
                'icon' => 'error',
            ]));
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
