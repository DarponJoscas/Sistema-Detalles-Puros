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

    public function filtrar()
    {
        $this->resetPage();
    }

    // Toda la funcionalidad de la tabla de Clientes
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
        }
    }

    public function crearCliente()
    {
        Cliente::updateOrCreate(
            ['id_cliente' => $this->id_cliente],
            ['name_cliente' => $this->name_cliente, 'estado_cliente' => 1]
        );

        $this->closeClienteModal();
        $this->dispatch('dispatch');
    }


    public function closeClienteModal()
    {
        $this->dispatch('hide-cliente-modal');
        $this->reset(['id_cliente', 'name_cliente']);
    }

    public function deleteCliente($clienteId)
    {
        try {
            $registro = Cliente::where('id_cliente', $clienteId)->first();
            if ($registro) {
                $registro->estado_cliente = 0;
                $registro->save();
                session()->flash('success', 'Se ha desactivado correctamente el cliente.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar el puro.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    public function reactivarCliente($clienteId)
    {
        try {
            $registro = Cliente::where('id_cliente', $clienteId)->first();
            if ($registro) {
                $registro->estado_cliente = 1;
                $registro->save();
                session()->flash('success', 'Se ha activado correctamente el cliente.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar el puro.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
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

        $rol = Rol::find($rolId);

        if ($rol) {
            $this->id_rol = $rol->id_rol;
            $this->rol = $rol->rol;
            $this->dispatch('show-rol-modal');
        } else {
            Log::error("Rol no encontrado con ID: " . $rolId);
        }
    }

    public function crearRol()
    {
        Rol::updateOrCreate(
            ['id_rol' => $this->id_rol],
            ['rol' => $this->rol, 'estado_rol' => 1]
        );

        $this->closeRolModal();
        $this->dispatch('dispatch');
    }

    public function closeRolModal()
    {
        $this->dispatch('hide-rol-modal');
        $this->reset(['id_rol', 'rol']);
    }

    public function deleteRol($rolId)
    {
        try {
            $registro = Rol::where('id_rol', $rolId)->first();
            if ($registro) {
                $registro->estado_rol = 0;
                $registro->save();
                session()->flash('success', 'Se ha desactivado correctamente el rol.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar el puro.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    public function reactivarRol($rolId)
    {
        try {
            $registro = Rol::where('id_rol', $rolId)->first();
            if ($registro) {
                $registro->estado_rol = 1;
                $registro->save();
                session()->flash('success', 'Se ha activado correctamente el rol.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar el puro.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
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
        $marca = Marca::find($marcaId);

        if ($marca) {
            $this->id_marca = $marca->id_marca;
            $this->marca = $marca->marca;
            $this->dispatch('show-marca-modal');
        } else {
            Log::error("Marca no encontrada con ID: " . $marcaId);
        }
    }

    public function crearMarca()
    {
        $id_usuario = Auth::user()->id_usuario;

        if ($this->id_marca) {
            Marca::where('id_marca', $this->id_marca)->update(['marca' => $this->marca]);
            Bitacora::create([
                'descripcion'=> 'Se realizo actualizacion de un registro: ' . $this->marca,
                'accion' => 'Actualización',
                'id_usuario' => $id_usuario,
            ]);
        } else {
            $ultimoId = Marca::max('id_marca');
            $nuevoId = $ultimoId ? $ultimoId + 1 : 1;

            Marca::create([
                'id_marca' => $nuevoId,
                'marca' => $this->marca,
                'estado_marca' => 1
            ]);

            Bitacora::create([
                'descripcion'=> 'Se realizo un nuevo registro: ' . $this->marca,
                'accion' => 'Crear',
                'id_usuario' => $id_usuario,
            ]);
        }

        $this->closeMarcaModal();
        $this->dispatch('dispatch');
    }

    public function closeMarcaModal()
    {
        $this->dispatch('hide-marca-modal');
        $this->reset(['id_marca', 'marca']);
    }

    public function deleteMarca($marcaId)
    {
        try {
            $registro = Marca::where('id_marca', $marcaId)->first();
            if ($registro) {
                $registro->estado_marca = 0;
                $registro->save();
                session()->flash('success', 'Se ha desactivado correctamente el marca.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar la marca.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    public function reactivarMarca($marcaId)
    {
        try {
            $registro = Marca::where('id_marca', $marcaId)->first();
            if ($registro) {
                $registro->estado_marca = 1;
                $registro->save();
                session()->flash('success', 'Se ha activado correctamente la marca.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al activar la marca.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    // Toda la funcionalidad de la tabla de Capas
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
        $capa = Capa::find($capaId);

        if ($capa) {
            $this->id_capa = $capa->id_capa;
            $this->capa = $capa->capa;
            $this->dispatch('show-capa-modal');
        } else {
            Log::error("Capa no encontrada con ID: " . $capaId);
        }
    }

    public function crearCapa()
    {
        if ($this->id_capa) {
            Capa::where('id_capa', $this->id_capa)->update(['capa' => $this->capa]);
        } else {
            $ultimoId = Capa::max('id_capa');
            $nuevoId = $ultimoId ? $ultimoId + 1 : 1;

            Capa::create([
                'id_capa' => $nuevoId,
                'capa' => $this->capa,
                'estado_capa' => 1
            ]);
        }

        $this->closeCapaModal();
        $this->dispatch('dispatch');
    }

    public function closeCapaModal()
    {
        $this->dispatch('hide-capa-modal');
        $this->reset(['id_capa', 'capa']);
    }

    public function deleteCapa($capaId)
    {
        try {
            $registro = Capa::where('id_capa', $capaId)->first();
            if ($registro) {
                $registro->estado_capa = 0;
                $registro->save();
                session()->flash('success', 'Se ha desactivado correctamente la capa.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar la capa.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    public function reactivarCapa($capaId)
    {
        try {
            $registro = Capa::where('id_capa', $capaId)->first();
            if ($registro) {
                $registro->estado_capa = 1;
                $registro->save();
                session()->flash('success', 'Se ha activado correctamente la capa.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al activar la capa.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    // Toda la funcionalidad de la tabla de Vitolas
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
        $vitola = Vitola::find($vitolaId);

        if ($vitola) {
            $this->id_vitola = $vitola->id_vitola;
            $this->vitola = $vitola->vitola;
            $this->dispatch('show-vitola-modal');
        } else {
            Log::error("Vitola no encontrada con ID: " . $vitolaId);
        }
    }

    public function crearVitola()
    {
        if ($this->id_vitola) {
            Vitola::where('id_vitola', $this->id_vitola)->update(['vitola' => $this->vitola]);
        } else {
            $ultimoId = Vitola::max('id_vitola');
            $nuevoId = $ultimoId ? $ultimoId + 1 : 1;

            Vitola::create([
                'id_vitola' => $nuevoId,
                'vitola' => $this->vitola,
                'estado_vitola' => 1
            ]);
        }

        $this->closeVitolaModal();
        $this->dispatch('dispatch');
    }

    public function closeVitolaModal()
    {
        $this->dispatch('hide-vitola-modal');
        $this->reset(['id_vitola', 'vitola']);
    }

    public function deleteVitola($vitolaId)
    {
        try {
            $registro = Vitola::where('id_vitola', $vitolaId)->first();
            if ($registro) {
                $registro->estado_vitola = 0;
                $registro->save();
                session()->flash('success', 'Se ha desactivado correctamente la vitola.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar la vitola.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    public function reactivarVitola($vitolaId)
    {
        try {
            $registro = Vitola::where('id_vitola', $vitolaId)->first();
            if ($registro) {
                $registro->estado_vitola = 1;
                $registro->save();
                session()->flash('success', 'Se ha activado correctamente la vitola.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al activar la vitola.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    // Toda la funcionalidad de la tabla de Alias Vitolas
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
        $aliasVitola = AliasVitola::find($aliasVitolaId);

        if ($aliasVitola) {
            $this->id_aliasvitola = $aliasVitola->id_aliasvitola;
            $this->alias_vitola = $aliasVitola->alias_vitola;
            $this->dispatch('show-aliasVitola-modal');
        } else {
            Log::error("Alias Vitola no encontrado con ID: " . $aliasVitolaId);
        }
    }

    public function crearAliasVitola()
    {
        if ($this->id_aliasvitola) {
            AliasVitola::where('id_aliasvitola', $this->id_aliasvitola)->update(['alias_vitola' => $this->alias_vitola]);
        } else {
            $ultimoId = AliasVitola::max('id_aliasvitola');
            $nuevoId = $ultimoId ? $ultimoId + 1 : 1;

            Vitola::create([
                'id_aliasvitola' => $nuevoId,
                'alias_vitola' => $this->alias_vitola,
                'estado_aliasVitola' => 1,
            ]);
        }

        $this->closeVitolaModal();
        $this->dispatch('dispatch');
    }

    public function closeAliasVitolaModal()
    {
        $this->dispatch('hide-aliasVitola-modal');
        $this->reset(['id_aliasvitola', 'alias_vitola']);
    }

    public function deleteAliasVitola($aliasVitolaId)
    {
        try {
            $registro = AliasVitola::where('id_aliasvitola', $aliasVitolaId)->first();
            if ($registro) {
                $registro->estado_aliasVitola = 0;
                $registro->save();
                session()->flash('success', 'Se ha desactivado correctamente el alias vitola.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar el alias vitola.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    public function reactivarAliasVitola($aliasVitolaId)
    {
        try {
            $registro = AliasVitola::where('id_aliasvitola', $aliasVitolaId)->first();
            if ($registro) {
                $registro->estado_aliasVitola = 1;
                $registro->save();
                session()->flash('success', 'Se ha activado correctamente el alias vitola.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al activar el alias vitola.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    // Toda la funcionalidad de la tabla de Tipo Empaque
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
        $tipoEmpaque = TipoEmpaque::find($tipoEmpaqueId);

        if ($tipoEmpaque) {
            $this->id_tipoempaque = $tipoEmpaque->id_tipoempaque;
            $this->tipo_empaque = $tipoEmpaque->tipo_empaque;
            $this->dispatch('show-tipoEmpaque-modal');
        } else {
            Log::error("Tipo empaque no encontrado con ID: " . $tipoEmpaqueId);
        }
    }

    public function crearTipoEmpaque()
    {
        if ($this->id_tipoempaque) {
            TipoEmpaque::where('id_tipoempaque', $this->id_tipoempaque)->update(['tipo_empaque' => $this->tipo_empaque]);
        } else {
            $ultimoId = TipoEmpaque::max('id_tipoempaque');
            $nuevoId = $ultimoId ? $ultimoId + 1 : 1;

            TipoEmpaque::create([
                'id_tipoempaque' => $nuevoId,
                'tipo_empaque' => $this->tipo_empaque,
                'estado_tipoEmpaque' => 1
            ]);
        }

        $this->closeTipoEmpaqueModal();
        $this->dispatch('dispatch');
    }

    public function closeTipoEmpaqueModal()
    {
        $this->dispatch('hide-tipoEmpaque-modal');
        $this->reset(['id_tipoempaque', 'tipo_empaque']);
    }

    public function deleteTipoEmpaque($tipoEmpaqueId)
    {
        try {
            $registro = TipoEmpaque::where('id_tipoempaque', $tipoEmpaqueId)->first();
            if ($registro) {
                $registro->estado_tipoEmpaque = 0;
                $registro->save();
                session()->flash('success', 'Se ha desactivado correctamente el tipo empaque.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar el tipo empaque.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    public function reactivarTipoEmpaque($tipoEmpaqueId)
    {
        try {
            $registro = TipoEmpaque::where('id_tipoempaque', $tipoEmpaqueId)->first();
            if ($registro) {
                $registro->estado_tipoEmpaque = 0;
                $registro->save();
                session()->flash('success', 'Se ha activado correctamente el tipo empaque.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al activar el tipo empaque.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
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
