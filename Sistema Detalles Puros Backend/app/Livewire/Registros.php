<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use App\Models\Cliente;
use App\Models\Rol;
use App\Models\Marca;
use App\Models\Capa;
use App\Models\Vitola;
use App\Models\TipoEmpaque;
use App\Models\AliasVitola;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Registros extends Component
{
    use WithPagination;

    public $filtro_cliente = '';
    public $filtro_rol = '';
    public $filtro_marca = '';
    public $filtro_vitola = '';
    public $filtro_capa = '';
    public $filtro_aliasVitola = '';
    public $filtro_tipoEmpaque = '';

    public $name_cliente, $rol, $id_rol, $marca, $id_capa, $id_marca, $id_tipoempaque, $id_vitola, $id_aliasvitola, $vitola, $capa, $alias_vitola, $tipo_empaque, $usuario;
    public $clientes, $roles, $marcas, $vitolas, $capas, $alias_vitolas, $tipo_empaques;
    public $currentUrl;
    public $id_cliente;

    protected $listeners = [
        'confirmarEliminacionCliente' => 'confirmarEliminacionCliente',
        'confirmarEliminacionRol' => 'confirmarEliminacionRol',
        'confirmarEliminacionMarca' => 'confirmarEliminacionMarca',
        'confirmarEliminacionVitola' => 'confirmarEliminacionVitola',
        'confirmarEliminacionCapa' => 'confirmarEliminacionCapa',
        'confirmarEliminacionAliasVitola' => 'confirmarEliminacionAlias',
        'confirmarEliminacionTipoEmpaque' => 'confirmarEliminacionTipoEmpaque',
    ];

    public function filtrar()
    {
        $this->resetPage();
    }

    public function getAuthUserId()
    {
        return Auth::user()->id_usuario;
    }

    public function getDatosClientes()
    {
        $results = DB::select("CALL GetClientes(?)", [$this->filtro_cliente ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_cliente' => $row->id_cliente ?? '',
                'name_cliente' => $row->name_cliente ?? '',
                'estado_cliente' => $row->estado_cliente ?? '',
            ];
        });
    }

    public function editCliente($clienteId)
    {
        $cliente = Cliente::find($clienteId);

        if ($cliente) {
            $this->id_cliente = $cliente->id_cliente;
            $this->name_cliente = $cliente->name_cliente;
            $this->dispatch('show-cliente-modal');
        } else {
            Log::error("Cliente no encontrado con ID: " . $clienteId);

            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'No se encontró el cliente con el ID proporcionado.',
                'icon' => 'error',
            ]));
        }
    }

    public function crearCliente()
    {
        $validator = Validator::make(
            [
                'id_cliente' => $this->id_cliente,
                'name_cliente' => $this->name_cliente,
            ],
            [
                'id_cliente' => 'required|integer|exists:clientes,id_cliente',
                'name_cliente' => 'required|string|max:255',
            ],
            [
                'id_cliente.required' => 'El ID del cliente es obligatorio.',
                'id_cliente.integer' => 'El ID del cliente debe ser un número entero.',
                'id_cliente.exists' => 'El cliente no existe en la base de datos.',
                'name_cliente.required' => 'El nombre del cliente es obligatorio.',
                'name_cliente.string' => 'El nombre del cliente debe ser una cadena de texto.',
                'name_cliente.max' => 'El nombre del cliente no puede superar los 255 caracteres.',
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

        try {
            $clienteExistente = Cliente::find($this->id_cliente);

            $cliente = Cliente::updateOrCreate(
                ['id_cliente' => $this->id_cliente],
                ['name_cliente' => $this->name_cliente, 'estado_cliente' => 1]
            );

            if ($clienteExistente) {
                Bitacora::create([
                    'descripcion' => 'Se actualizó el cliente: ' . $this->name_cliente,
                    'accion' => 'Actualización',
                    'id_usuario' => $this->getAuthUserId(),
                ]);
                $mensaje = 'Cliente actualizado correctamente.';
            } else {
                Bitacora::create([
                    'descripcion' => 'Se creó un nuevo cliente: ' . $this->name_cliente,
                    'accion' => 'Crear',
                    'id_usuario' => $this->getAuthUserId(),
                ]);
                $mensaje = 'Cliente creado correctamente.';
            }

            $this->dispatch('swal', json_encode([
                'title' => 'Éxito',
                'text' => $mensaje,
                'icon' => 'success',
            ]));

            $this->closeClienteModal();
            $this->dispatch('dispatch');
        } catch (\Exception $e) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al crear o actualizar el cliente. Inténtalo de nuevo.',
                'icon' => 'error',
            ]));
        }
    }


    public function closeClienteModal()
    {
        $this->dispatch('hide-cliente-modal');
        $this->reset(['id_cliente', 'name_cliente']);
    }

    public function deleteCliente($clienteId)
    {
        $validator = Validator::make(
            ['id_cliente' => $clienteId],
            ['id_cliente' => 'required|integer|exists:clientes,id_cliente']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'ID no válido',
                'text' => 'No se encontró el cliente especificado.',
                'icon' => 'error',
            ]));
            return;
        }

        $this->dispatch('swalConfirmDelete', json_encode([
            'title' => '¿Estás seguro?',
            'text' => '¿Realmente deseas desactivar este cliente?',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'confirmButtonText' => 'Sí, desactivar',
            'cancelButtonText' => 'No, cancelar',
            'idCliente' => $clienteId
        ]));
    }

    public function confirmarEliminacionCliente($clienteId)
    {
        try {
            $registro = Cliente::find($clienteId);

            if ($registro) {
                $registro->estado_cliente = 0;
                $registro->save();

                $this->dispatch('refresh');

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha desactivado correctamente el cliente.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Ocurrió un error al desactivar el cliente.',
                'icon' => 'error',
            ]));
        }
    }

    public function reactivarCliente($clienteId)
    {
        try {
            $registro = Cliente::where('id_cliente', $clienteId)->first();

            if ($registro) {
                $registro->estado_cliente = 1;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha activado correctamente el cliente.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Error al activar el cliente.',
                'icon' => 'error',
            ]));

            Log::error('Error en reactivarCliente: ' . $e->getMessage());
        }
    }

    // Toda la funcionalidad de la tabla de Roles
    public function getDatosRoles()
    {
        $results = DB::select("CALL GetRoles(?)", [$this->filtro_rol ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_rol' => $row->id_rol ?? '',
                'rol' => $row->rol ?? '',
                'estado_rol' => $row->estado_rol ?? '',
            ];
        });
    }

    public function editRol($rolId)
    {
        $validator = Validator::make(
            ['id_rol' => $rolId],
            ['id_rol' => 'required|integer|exists:roles,id_rol']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'El ID de rol no es válido o no existe.',
                'icon' => 'error',
            ]));
            return;
        }

        $rol = Rol::find($rolId);

        if ($rol) {
            $this->id_rol = $rol->id_rol;
            $this->rol = $rol->rol;
            $this->dispatch('show-rol-modal');
        } else {
            Log::error("Rol no encontrado con ID: " . $rolId);
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Rol no encontrado.',
                'icon' => 'error',
            ]));
        }
    }

    public function crearRol()
    {
        $validator = Validator::make(
            ['rol' => $this->rol],
            ['rol' => 'required|string|max:255']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'El nombre del rol es obligatorio y no debe exceder los 255 caracteres.',
                'icon' => 'error',
            ]));
            return;
        }

        try {
            $rolExistente = Rol::find($this->id_rol);

            Rol::updateOrCreate(
                ['id_rol' => $this->id_rol],
                ['rol' => $this->rol, 'estado_rol' => 1]
            );

            if ($rolExistente) {
                Bitacora::create([
                    'descripcion' => 'Se actualizó el rol: ' . $this->rol,
                    'accion' => 'Actualización',
                    'id_usuario' => $this->getAuthUserId(),
                ]);
                $mensaje = 'Rol actualizado correctamente.';
            } else {
                Bitacora::create([
                    'descripcion' => 'Se creó un nuevo rol: ' . $this->rol,
                    'accion' => 'Crear',
                    'id_usuario' => $this->getAuthUserId(),
                ]);
                $mensaje = 'Rol creado correctamente.';
            }

            $this->dispatch('swal', json_encode([
                'title' => 'Éxito',
                'text' => $mensaje,
                'icon' => 'success',
            ]));

            $this->closeRolModal();
            $this->dispatch('dispatch');
        } catch (\Exception $e) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al crear o actualizar el rol. Inténtalo de nuevo.',
                'icon' => 'error',
            ]));
        }
    }

    public function closeRolModal()
    {
        $this->dispatch('hide-rol-modal');
        $this->reset(['id_rol', 'rol']);
    }

    public function deleteRol($rolId)
    {
        $validator = Validator::make(
            ['id_rol' => $rolId],
            ['id_rol' => 'required|integer|exists:roles,id_rol']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'ID no válido',
                'text' => 'No se encontró el rol especificado.',
                'icon' => 'error',
            ]));
            return;
        }

        $this->dispatch('swalConfirmDelete', json_encode([
            'title' => '¿Estás seguro?',
            'text' => '¿Realmente deseas desactivar este rol?',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'confirmButtonText' => 'Sí, desactivar',
            'cancelButtonText' => 'No, cancelar',
            'idRol' => $rolId,
        ]));
    }

    public function confirmarEliminacionRol($rolId)
    {
        try {
            $registro = Rol::where('id_rol', $rolId)->first();
            if ($registro) {
                $registro->estado_rol = 0;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha desactivado correctamente el rol.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Ocurrió un error al desactivar el rol.',
                'icon' => 'error',
            ]));

            Log::error('Error en confirmarEliminacionRol: ' . $e->getMessage());
        }
    }

    public function reactivarRol($rolId)
    {
        $validator = Validator::make(
            ['id_rol' => $rolId],
            ['id_rol' => 'required|integer|exists:roles,id_rol']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'El ID de rol no es válido o no existe.',
                'icon' => 'error',
            ]));
            return;
        }

        try {
            $registro = Rol::where('id_rol', $rolId)->first();
            if ($registro) {
                $registro->estado_rol = 1;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha activado correctamente el rol.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Ocurrió un error al activar el rol.',
                'icon' => 'error',
            ]));

            Log::error('Error en reactivarRol: ' . $e->getMessage());
        }
    }

    // Toda la funcionalidad de la tabla de Marcas
    public function getDatosMarca()
    {
        $results = DB::select("CALL GetMarca(?)", [$this->filtro_marca ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_marca' => $row->id_marca ?? '',
                'marca' => $row->marca ?? '',
                'estado_marca' => $row->estado_marca ?? '',
            ];
        });
    }
    public function editMarca($marcaId)
    {
        $validator = Validator::make(
            ['id_marca' => $marcaId],
            ['id_marca' => 'required|integer|exists:marcas,id_marca']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'El ID de la marca no es válido o no existe.',
                'icon' => 'error',
            ]));
            return;
        }

        $marca = Marca::find($marcaId);

        if ($marca) {
            $this->id_marca = $marca->id_marca;
            $this->marca = $marca->marca;
            $this->dispatch('show-marca-modal');
        } else {
            Log::error("Marca no encontrada con ID: " . $marcaId);
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Marca no encontrada.',
                'icon' => 'error',
            ]));
        }
    }

    public function crearMarca()
    {
        $validator = Validator::make(
            ['marca' => $this->marca],
            ['marca' => 'required|string|max:255']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error de Validación',
                'text' => 'El nombre de la marca es obligatorio y no debe exceder los 255 caracteres.',
                'icon' => 'error',
            ]));
            return;
        }

        try {
            if ($this->id_marca) {
                Marca::where('id_marca', $this->id_marca)->update(['marca' => $this->marca]);

                Bitacora::create([
                    'descripcion' => 'Se realizó actualización de un registro: ' . $this->marca,
                    'accion' => 'Actualización',
                    'id_usuario' => $this->getAuthUserId(),
                ]);

                $mensaje = 'Marca actualizada correctamente.';
            } else {

                $ultimoId = Marca::max('id_marca');
                $nuevoId = $ultimoId ? $ultimoId + 1 : 1;

                Marca::create([
                    'id_marca' => $nuevoId,
                    'marca' => $this->marca,
                    'estado_marca' => 1
                ]);

                Bitacora::create([
                    'descripcion' => 'Se realizó un nuevo registro: ' . $this->marca,
                    'accion' => 'Crear',
                    'id_usuario' => $this->getAuthUserId(),
                ]);

                $mensaje = 'Marca creada correctamente.';
            }

            $this->dispatch('swal', json_encode([
                'title' => 'Éxito',
                'text' => $mensaje,
                'icon' => 'success',
            ]));

            $this->closeMarcaModal();
            $this->dispatch('dispatch');
        } catch (\Exception $e) {

            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al crear o actualizar la marca. Inténtalo de nuevo.',
                'icon' => 'error',
            ]));
        }
    }


    public function closeMarcaModal()
    {
        $this->dispatch('hide-marca-modal');
        $this->reset(['id_marca', 'marca']);
    }

    public function deleteMarca($marcaId)
    {

        $validator = Validator::make(
            ['id_marca' => $marcaId],
            ['id_marca' => 'required|integer|exists:marca,id_marca']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'ID no válido',
                'text' => 'No se encontró la marca especificada.',
                'icon' => 'error',
            ]));
            return;
        }

        $this->dispatch('swalConfirmDelete', json_encode([
            'title' => '¿Estás seguro?',
            'text' => '¿Realmente deseas desactivar esta marca?',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'confirmButtonText' => 'Sí, desactivar',
            'cancelButtonText' => 'No, cancelar',
            'idMarca' => $marcaId,
        ]));
    }

    public function confirmarEliminacionMarca($marcaId)
    {
        try {
            $registro = Marca::where('id_marca', $marcaId)->first();
            if ($registro) {

                $registro->estado_marca = 0;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha desactivado correctamente la marca.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Ocurrió un error al desactivar la marca.',
                'icon' => 'error',
            ]));

            Log::error('Error en confirmarEliminacionMarca: ' . $e->getMessage());
        }
    }


    public function reactivarMarca($marcaId)
    {
        try {
            $registro = Marca::where('id_marca', $marcaId)->first();

            if ($registro) {
                $registro->estado_marca = 1;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha activado correctamente la marca.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Error al activar la marca.',
                'icon' => 'error',
            ]));

            Log::error('Error en reactivarMarca: ' . $e->getMessage());
        }
    }

    public function getDatosCapa()
    {
        $results = DB::select("CALL GetCapa(?)", [$this->filtro_capa ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_capa' => $row->id_capa ?? '',
                'capa' => $row->capa ?? '',
                'estado_capa' => $row->estado_capa ?? '',
            ];
        });
    }

    public function editCapa($capaId)
    {
        $validator = Validator::make(
            ['id_capa' => $capaId],
            ['id_capa' => 'required|integer|exists:capas,id_capa']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'El ID de la capa no es válido o no existe.',
                'icon' => 'error',
            ]));
            return;
        }

        $capa = Capa::find($capaId);

        if ($capa) {
            $this->id_capa = $capa->id_capa;
            $this->capa = $capa->capa;
            $this->dispatch('show-capa-modal');
        } else {
            Log::error("Capa no encontrada con ID: " . $capaId);
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Capa no encontrada.',
                'icon' => 'error',
            ]));
        }
    }


    public function crearCapa()
    {
        $validator = Validator::make(
            ['capa' => $this->capa],
            ['capa' => 'required|string|max:255']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error de Validación',
                'text' => 'El nombre de la capa es obligatorio y no debe exceder los 255 caracteres.',
                'icon' => 'error',
            ]));
            return;
        }

        try {
            if ($this->id_capa) {
                Capa::where('id_capa', $this->id_capa)->update(['capa' => $this->capa]);

                Bitacora::create([
                    'descripcion' => 'Se realizó actualización de un registro: ' . $this->capa,
                    'accion' => 'Actualización',
                    'id_usuario' => $this->getAuthUserId(),
                ]);

                $mensaje = 'Capa actualizada correctamente.';
            } else {
                $ultimoId = Capa::max('id_capa');
                $nuevoId = $ultimoId ? $ultimoId + 1 : 1;

                Capa::create([
                    'id_capa' => $nuevoId,
                    'capa' => $this->capa,
                    'estado_capa' => 1
                ]);
                Bitacora::create([
                    'descripcion' => 'Se realizó un nuevo registro: ' . $this->capa,
                    'accion' => 'Crear',
                    'id_usuario' => $this->getAuthUserId(),
                ]);

                $mensaje = 'Capa creada correctamente.';
            }

            $this->dispatch('swal', json_encode([
                'title' => 'Éxito',
                'text' => $mensaje,
                'icon' => 'success',
            ]));

            $this->closeCapaModal();
            $this->dispatch('dispatch');
        } catch (\Exception $e) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al crear o actualizar la capa. Inténtalo de nuevo.',
                'icon' => 'error',
            ]));
        }
    }

    public function closeCapaModal()
    {
        $this->dispatch('hide-capa-modal');
        $this->reset(['id_capa', 'capa']);
    }

    public function deleteCapa($capaId)
    {
        $validator = Validator::make(
            ['id_capa' => $capaId],
            ['id_capa' => 'required|integer|exists:capa,id_capa']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'ID no válido',
                'text' => 'No se encontró la capa especificada.',
                'icon' => 'error',
            ]));
            return;
        }

        $this->dispatch('swalConfirmDelete', json_encode([
            'title' => '¿Estás seguro?',
            'text' => '¿Realmente deseas desactivar esta capa?',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'confirmButtonText' => 'Sí, desactivar',
            'cancelButtonText' => 'No, cancelar',
            'idCapa' => $capaId,
        ]));
    }

    public function confirmarEliminacionCapa($capaId)
    {
        try {
            $registro = Capa::where('id_capa', $capaId)->first();
            if ($registro) {
                $registro->estado_capa = 0;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha desactivado correctamente la capa.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Ocurrió un error al desactivar la capa.',
                'icon' => 'error',
            ]));

            Log::error('Error en confirmarEliminacionCapa: ' . $e->getMessage());
        }
    }

    public function reactivarCapa($capaId)
    {
        try {
            $registro = Capa::where('id_capa', $capaId)->first();

            if ($registro) {
                $registro->estado_capa = 1;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha activado correctamente la capa.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Error al activar la capa.',
                'icon' => 'error',
            ]));

            Log::error('Error en reactivarCapa: ' . $e->getMessage());
        }
    }

    public function getDatosVitola()
    {
        $results = DB::select("CALL GetVitola(?)", [$this->filtro_vitola ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_vitola' => $row->id_vitola ?? '',
                'vitola' => $row->vitola ?? '',
                'estado_vitola' => $row->estado_vitola ?? '',
            ];
        });
    }

    public function editVitola($vitolaId)
    {
        $validator = Validator::make(
            ['id_vitola' => $vitolaId],
            ['id_vitola' => 'required|integer|exists:vitola,id_vitola']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'El ID de la vitola no es válido o no existe.',
                'icon' => 'error',
            ]));
            return;
        }

        $vitola = Vitola::find($vitolaId);

        if ($vitola) {
            $this->id_vitola = $vitola->id_vitola;
            $this->vitola = $vitola->vitola;
            $this->dispatch('show-vitola-modal');
        } else {
            Log::error("Vitola no encontrada con ID: " . $vitolaId);
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Vitola no encontrada.',
                'icon' => 'error',
            ]));
        }
    }


    public function crearVitola()
    {
        $validator = Validator::make(
            ['vitola' => $this->vitola],
            ['vitola' => 'required|string|max:255']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error de Validación',
                'text' => 'El nombre de la vitola es obligatorio y no debe exceder los 255 caracteres.',
                'icon' => 'error',
            ]));
            return;
        }

        try {
            if ($this->id_vitola) {
                Vitola::where('id_vitola', $this->id_vitola)->update(['vitola' => $this->vitola]);

                Bitacora::create([
                    'descripcion' => 'Se realizó actualización de un registro: ' . $this->vitola,
                    'accion' => 'Actualización',
                    'id_usuario' => $this->getAuthUserId(),
                ]);

                $mensaje = 'Vitola actualizada correctamente.';
            } else {
                $ultimoId = Vitola::max('id_vitola');
                $nuevoId = $ultimoId ? $ultimoId + 1 : 1;

                Vitola::create([
                    'id_vitola' => $nuevoId,
                    'vitola' => $this->vitola,
                    'estado_vitola' => 1
                ]);

                Bitacora::create([
                    'descripcion' => 'Se realizó un nuevo registro: ' . $this->vitola,
                    'accion' => 'Crear',
                    'id_usuario' => $this->getAuthUserId(),
                ]);

                $mensaje = 'Vitola creada correctamente.';
            }

            $this->dispatch('swal', json_encode([
                'title' => 'Éxito',
                'text' => $mensaje,
                'icon' => 'success',
            ]));

            $this->closeVitolaModal();
            $this->dispatch('dispatch');
        } catch (\Exception $e) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al crear o actualizar la vitola. Inténtalo de nuevo.',
                'icon' => 'error',
            ]));
        }
    }

    public function closeVitolaModal()
    {
        $this->dispatch('hide-vitola-modal');
        $this->reset(['id_vitola', 'vitola']);
    }

    public function deleteVitola($vitolaId)
    {
        $validator = Validator::make(
            ['id_vitola' => $vitolaId],
            ['id_vitola' => 'required|integer|exists:vitolas,id_vitola']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'ID no válido',
                'text' => 'No se encontró la vitola especificada.',
                'icon' => 'error',
            ]));
            return;
        }

        $this->dispatch('swalConfirmDelete', json_encode([
            'title' => '¿Estás seguro?',
            'text' => '¿Realmente deseas desactivar esta vitola?',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'confirmButtonText' => 'Sí, desactivar',
            'cancelButtonText' => 'No, cancelar',
            'idVitola' => $vitolaId,
        ]));
    }

    public function confirmarEliminacionVitola($vitolaId)
    {
        try {
            $registro = Vitola::where('id_vitola', $vitolaId)->first();
            if ($registro) {
                $registro->estado_vitola = 0;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha desactivado correctamente la vitola.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Ocurrió un error al desactivar la vitola.',
                'icon' => 'error',
            ]));

            Log::error('Error en confirmarEliminacionVitola: ' . $e->getMessage());
        }
    }

    public function reactivarVitola($vitolaId)
    {
        try {
            $registro = Vitola::where('id_vitola', $vitolaId)->first();

            if ($registro) {
                $registro->estado_vitola = 1;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha activado correctamente la vitola.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Error al activar la vitola.',
                'icon' => 'error',
            ]));

            Log::error('Error en reactivarVitola: ' . $e->getMessage());
        }
    }


    public function getDatosAliasVitola()
    {
        $results = DB::select("CALL GetAliasVitola(?)", [$this->filtro_aliasVitola ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_aliasvitola' => $row->id_aliasvitola ?? '',
                'alias_vitola' => $row->alias_vitola ?? '',
                'estado_aliasVitola' => $row->estado_aliasVitola ?? '',
            ];
        });
    }

    public function editAliasVitola($aliasVitolaId)
    {
        $validator = Validator::make(
            ['id_aliasvitola' => $aliasVitolaId],
            ['id_aliasvitola' => 'required|integer|exists:alias_vitola,id_aliasvitola']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'El ID de aliasVitola no es válido o no existe.',
                'icon' => 'error',
            ]));
            return;
        }

        $aliasVitola = AliasVitola::find($aliasVitolaId);

        if ($aliasVitola) {
            $this->id_aliasvitola = $aliasVitola->id_aliasvitola;
            $this->alias_vitola = $aliasVitola->alias_vitola;
            $this->dispatch('show-aliasVitola-modal');
        } else {
            Log::error("Alias Vitola no encontrado con ID: " . $aliasVitolaId);
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Alias Vitola no encontrado.',
                'icon' => 'error',
            ]));
        }
    }


    public function crearAliasVitola()
    {
        $validator = Validator::make(
            ['alias_vitola' => $this->alias_vitola],
            ['alias_vitola' => 'required|string|max:255']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error de Validación',
                'text' => 'El alias de la vitola es obligatorio y no debe exceder los 255 caracteres.',
                'icon' => 'error',
            ]));
            return;
        }

        try {
            if ($this->id_aliasvitola) {
                AliasVitola::where('id_aliasvitola', $this->id_aliasvitola)->update(['alias_vitola' => $this->alias_vitola]);

                Bitacora::create([
                    'descripcion' => 'Se realizó actualización de un alias de vitola: ' . $this->alias_vitola,
                    'accion' => 'Actualización',
                    'id_usuario' => $this->getAuthUserId(),
                ]);

                $mensaje = 'Alias de vitola actualizado correctamente.';
            } else {
                $ultimoId = AliasVitola::max('id_aliasvitola');
                $nuevoId = $ultimoId ? $ultimoId + 1 : 1;

                AliasVitola::create([
                    'id_aliasvitola' => $nuevoId,
                    'alias_vitola' => $this->alias_vitola,
                    'estado_aliasVitola' => 1
                ]);

                Bitacora::create([
                    'descripcion' => 'Se realizó un nuevo registro de alias de vitola: ' . $this->alias_vitola,
                    'accion' => 'Crear',
                    'id_usuario' => $this->getAuthUserId(),
                ]);

                $mensaje = 'Alias de vitola creado correctamente.';
            }

            $this->dispatch('swal', json_encode([
                'title' => 'Éxito',
                'text' => $mensaje,
                'icon' => 'success',
            ]));

            $this->closeVitolaModal();
            $this->dispatch('dispatch');
        } catch (\Exception $e) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al crear o actualizar el alias de vitola. Inténtalo de nuevo.',
                'icon' => 'error',
            ]));
        }
    }

    public function closeAliasVitolaModal()
    {
        $this->dispatch('hide-aliasVitola-modal');
        $this->reset(['id_aliasvitola', 'alias_vitola']);
    }

    public function deleteAliasVitola($aliasVitolaId)
    {
        $validator = Validator::make(
            ['id_aliasvitola' => $aliasVitolaId],
            ['id_aliasvitola' => 'required|integer|exists:alias_vitolas,id_aliasvitola']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'ID no válido',
                'text' => 'No se encontró el alias vitola especificado.',
                'icon' => 'error',
            ]));
            return;
        }

        $this->dispatch('swalConfirmDelete', json_encode([
            'title' => '¿Estás seguro?',
            'text' => '¿Realmente deseas desactivar este alias vitola?',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'confirmButtonText' => 'Sí, desactivar',
            'cancelButtonText' => 'No, cancelar',
            'idAliasVitola' => $aliasVitolaId,
        ]));
    }

    public function confirmarEliminacionAliasVitola($aliasVitolaId)
    {
        try {
            $registro = AliasVitola::where('id_aliasvitola', $aliasVitolaId)->first();
            if ($registro) {
                $registro->estado_aliasVitola = 0;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha desactivado correctamente el alias vitola.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Ocurrió un error al desactivar el alias vitola.',
                'icon' => 'error',
            ]));

            Log::error('Error en confirmarEliminacionAliasVitola: ' . $e->getMessage());
        }
    }

    public function reactivarAliasVitola($aliasVitolaId)
    {
        try {
            $registro = AliasVitola::where('id_aliasvitola', $aliasVitolaId)->first();

            if ($registro) {
                $registro->estado_aliasVitola = 1;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha activado correctamente el alias vitola.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Error al activar el alias vitola.',
                'icon' => 'error',
            ]));

            Log::error('Error en reactivarAliasVitola: ' . $e->getMessage());
        }
    }


    public function getDatosTipoEmpaque()
    {
        $results = DB::select("CALL GetTipoEmpaque(?)", [$this->filtro_tipo_empaque ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_tipoempaque' => $row->id_tipoempaque ?? '',
                'tipo_empaque' => $row->tipo_empaque ?? '',
                'estado_tipoEmpaque' => $row->estado_tipoEmpaque ?? '',
            ];
        });
    }

    public function editTipoEmpaque($tipoEmpaqueId)
    {
        $validator = Validator::make(
            ['id_tipoempaque' => $tipoEmpaqueId],
            ['id_tipoempaque' => 'required|integer|exists:tipo_empques,id_tipoempaque']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'El ID de tipo de empaque no es válido o no existe.',
                'icon' => 'error',
            ]));
            return;
        }

        $tipoEmpaque = TipoEmpaque::find($tipoEmpaqueId);

        if ($tipoEmpaque) {
            $this->id_tipoempaque = $tipoEmpaque->id_tipoempaque;
            $this->tipo_empaque = $tipoEmpaque->tipo_empaque;
            $this->dispatch('show-tipoEmpaque-modal');
        } else {
            Log::error("Tipo de empaque no encontrado con ID: " . $tipoEmpaqueId);
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Tipo de empaque no encontrado.',
                'icon' => 'error',
            ]));
        }
    }

    public function crearTipoEmpaque()
    {
        $validator = Validator::make(
            ['tipo_empaque' => $this->tipo_empaque],
            ['tipo_empaque' => 'required|string|max:255']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error de Validación',
                'text' => 'El tipo de empaque es obligatorio y no debe exceder los 255 caracteres.',
                'icon' => 'error',
            ]));
            return;
        }

        try {
            if ($this->id_tipoempaque) {
                TipoEmpaque::where('id_tipoempaque', $this->id_tipoempaque)->update(['tipo_empaque' => $this->tipo_empaque]);

                Bitacora::create([
                    'descripcion' => 'Se realizó actualización de un tipo de empaque: ' . $this->tipo_empaque,
                    'accion' => 'Actualización',
                    'id_usuario' => $this->getAuthUserId(),
                ]);

                $mensaje = 'Tipo de empaque actualizado correctamente.';
            } else {

                $ultimoId = TipoEmpaque::max('id_tipoempaque');
                $nuevoId = $ultimoId ? $ultimoId + 1 : 1;

                TipoEmpaque::create([
                    'id_tipoempaque' => $nuevoId,
                    'tipo_empaque' => $this->tipo_empaque,
                    'estado_tipoEmpaque' => 1
                ]);

                Bitacora::create([
                    'descripcion' => 'Se realizó un nuevo registro de tipo de empaque: ' . $this->tipo_empaque,
                    'accion' => 'Crear',
                    'id_usuario' => $this->getAuthUserId(),
                ]);

                $mensaje = 'Tipo de empaque creado correctamente.';
            }

            $this->dispatch('swal', json_encode([
                'title' => 'Éxito',
                'text' => $mensaje,
                'icon' => 'success',
            ]));

            $this->closeTipoEmpaqueModal();
            $this->dispatch('dispatch');
        } catch (\Exception $e) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al crear o actualizar el tipo de empaque. Inténtalo de nuevo.',
                'icon' => 'error',
            ]));
        }
    }


    public function closeTipoEmpaqueModal()
    {
        $this->dispatch('hide-tipoEmpaque-modal');
        $this->reset(['id_tipoempaque', 'tipo_empaque']);
    }

    public function deleteTipoEmpaque($tipoEmpaqueId)
    {
        $validator = Validator::make(
            ['id_tipoempaque' => $tipoEmpaqueId],
            ['id_tipoempaque' => 'required|integer|exists:tipo_empaque,id_tipoempaque']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'ID no válido',
                'text' => 'No se encontró el tipo de empaque especificado.',
                'icon' => 'error',
            ]));
            return;
        }
        $this->dispatch('swalConfirmDelete', json_encode([
            'title' => '¿Estás seguro?',
            'text' => '¿Realmente deseas desactivar este tipo de empaque?',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'confirmButtonText' => 'Sí, desactivar',
            'cancelButtonText' => 'No, cancelar',
            'idTipoEmpaque' => $tipoEmpaqueId,
        ]));
    }

    public function confirmarEliminacionTipoEmpaque($tipoEmpaqueId)
    {
        try {
            $registro = TipoEmpaque::where('id_tipoempaque', $tipoEmpaqueId)->first();
            if ($registro) {
                $registro->estado_tipoEmpaque = 0;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha desactivado correctamente el tipo de empaque.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Ocurrió un error al desactivar el tipo de empaque.',
                'icon' => 'error',
            ]));

            Log::error('Error en confirmarEliminacionTipoEmpaque: ' . $e->getMessage());
        }
    }


    public function reactivarTipoEmpaque($tipoEmpaqueId)
    {
        try {
            $registro = TipoEmpaque::where('id_tipoempaque', $tipoEmpaqueId)->first();

            if ($registro) {
                $registro->estado_tipoEmpaque = 1;
                $registro->save();

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha activado correctamente el tipo empaque.',
                    'icon' => 'success',
                ]));
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
                'text' => 'Error al activar el tipo empaque.',
                'icon' => 'error',
            ]));

            Log::error('Error en reactivarTipoEmpaque: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.registros', [
            'datosClientes' => $this->getDatosClientes(),
            'datosRoles' => $this->getDatosRoles(),
            'datosMarcas' => $this->getDatosMarca(),
            'datosVitolas' => $this->getDatosVitola(),
            'datosCapas' => $this->getDatosCapa(),
            'datosTipoEmpaque' => $this->getDatosTipoEmpaque(),
            'datosAliasVitolas' => $this->getDatosAliasVitola(),
            'usuario' => $this->usuario,
            $this->vitolas = DB::table('vitola')->get(['id_vitola', 'vitola']),
            $this->marcas = DB::table('marca')->get(['id_marca', 'marca']),
            $this->alias_vitolas = DB::table('alias_vitola')->get(['id_aliasvitola', 'alias_vitola']),
            $this->capas = DB::table('capa')->get(['id_capa', 'capa']),
            $this->clientes = DB::table('clientes')->get(['id_cliente', 'name_cliente']),
            $this->tipo_empaques = DB::table('tipo_empaques')->get(['id_tipoempaque', 'tipo_empaque']),
            $this->roles = DB::table('roles')->get(['id_rol', 'rol']),
        ])->extends('layouts.app')->section('content');
    }
}
