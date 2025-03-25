<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Models\Cliente;
use App\Models\Rol;
use App\Models\Marca;
use App\Models\Capa;
use App\Models\Vitola;
use App\Models\Usuario;
use App\Models\TipoEmpaque;
use App\Models\AliasVitola;
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

    public $name_cliente, $rol, $marca, $vitola, $capa, $alias_vitola, $tipo_empaque;

    public $clientes, $roles, $marcas, $vitolas, $capas, $alias_vitolas, $tipo_empaques;
    public $currentUrl;
    public $usuario;

    public function mount()
    {
        // Utilizamos session('id_usuario') para obtener el ID de usuario desde la sesión
        $this->usuario = session('id_usuario') ? Usuario::find(session('id_usuario')) : null;
    }
    public function getDatosClientes()
    {
        $results = DB::select("CALL GetClientes(?)", [$this->filtro_cliente ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_cliente' => $row->id_cliente ?? '',
                'name_cliente' => $row->name_cliente ?? '',
            ];
        });
    }

    public function getDatosRoles()
    {
        $results = DB::select("CALL GetRoles(?)", [$this->filtro_rol ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_rol' => $row->id_rol ?? '',
                'rol' => $row->rol ?? '',
            ];
        });
    }

    public function getDatosMarca()
    {
        $results = DB::select("CALL GetMarca(?)", [$this->filtro_marca ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_marca' => $row->id_marca ?? '',
                'marca' => $row->marca ?? '',
            ];
        });
    }

    public function getDatosVitola()
    {
        $results = DB::select("CALL GetVitola(?)", [$this->filtro_vitola ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_vitola' => $row->id_vitola ?? '',
                'vitola' => $row->vitola ?? '',
            ];
        });
    }

    public function getDatosCapa()
    {
        $results = DB::select("CALL GetCapa(?)", [$this->filtro_capa ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_capa' => $row->id_capa ?? '',
                'capa' => $row->capa ?? '',
            ];
        });
    }

    public function getDatosTipoEmpaque()
    {
        $results = DB::select("CALL GetTipoEmpaque(?)", [$this->filtro_tipo_empaque ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_tipoempaque' => $row->id_tipoempaque ?? '',
                'tipo_empaque' => $row->tipo_empaque ?? '',
            ];
        });
    }

    public function getDatosAliasVitola()
    {
        $results = DB::select("CALL GetAliasVitola(?)", [$this->filtro_aliasVitola ?? '']);

        return collect($results)->map(function ($row) {
            return [
                'id_aliasvitola' => $row->id_aliasvitola ?? '',
                'alias_vitola' => $row->alias_vitola ?? '',
            ];
        });
    }

    public function filtrar()
    {
        $this->resetPage();
    }

    public function crearCliente()
    {
        DB::beginTransaction();

        try {
            $existingClient = Cliente::where('name_cliente', $this->name_cliente)->first();

            if ($existingClient) {
                session()->flash('error', 'El nombre de cliente ya está en uso.');
                return;
            }

            Cliente::create([
                'name_cliente' => $this->name_cliente,
                'estado_cliente' => 1,
            ]);

            DB::commit();
            $this->dispatch('hideClienteModal');
            $this->reset(['name_cliente']);
            $this->dispatch('refresh');
            session()->flash('success', 'Cliente creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function crearRol()
    {
        DB::beginTransaction();

        try {
            $existingRol = Rol::where('rol', $this->rol)->first();

            if ($existingRol) {
                session()->flash('error', 'El rol ya existe.');
                return;
            }

            Rol::create([
                'rol' => $this->rol,
                'estado_rol' => 1,
            ]);

            DB::commit();
            $this->dispatch('hideRolModal');
            $this->reset(['rol']);
            $this->dispatch('refresh');
            session()->flash('success', 'Rol creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function crearMarca()
{
    DB::beginTransaction();

    try {
        $existingMarca = Marca::where('marca', $this->marca)->first();

        if ($existingMarca) {
            session()->flash('error', 'La marca ya existe.');
            return;
        }

        $lastId = Marca::max('id_marca');
        $newId = $lastId + 1;

        Marca::create([
            'id_marca' =>  $newId,
            'marca' => $this->marca,
            'estado_marca' => 1,
        ]);

        DB::commit();
        $this->dispatch('hideMarcaModal');
        $this->reset(['marca']);
        $this->dispatch('refresh');
        session()->flash('success', 'Marca creada correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        session()->flash('error', 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage());
    }
}


    public function crearCapa()
    {
        DB::beginTransaction();

        try {
            $existingCapa = Capa::where('capa', $this->capa)->first();

            if ($existingCapa) {
                session()->flash('error', 'La capa ya existe.');
                return;
            }

            Capa::create([
                'capa' => $this->capa,
                'estado_capa' => 1,
            ]);

            DB::commit();
            $this->dispatch('hideCapaModal');
            $this->reset(['capa']);
            $this->dispatch('refresh');
            session()->flash('success', 'Capa creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function crearVitola()
    {
        DB::beginTransaction();

        try {
            $existingVitola = Vitola::where('vitola', $this->vitola)->first();

            if ($existingVitola) {
                session()->flash('error', 'La vitola ya existe.');
                return;
            }

            Vitola::create([
                'vitola' => $this->vitola,
                'estado_vitola' => 1,
            ]);

            DB::commit();
            $this->dispatch('hideVitolaModal');
            $this->reset(['vitola']);
            $this->dispatch('refresh');
            session()->flash('success', 'La vitola ha sido creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function crearAliasVitola()
    {
        DB::beginTransaction();

        try {
            $existingAliasVitola = AliasVitola::where('alias_vitola', $this->alias_vitola)->first();

            if ($existingAliasVitola) {
                session()->flash('error', 'El alias vitola ya existe.');
                return;
            }

            AliasVitola::create([
                'alias_vitola' => $this->alias_vitola,
                'estado_aliasVitola' => 1,
            ]);

            DB::commit();
            $this->dispatch('hideAliasVitolaModal');
            $this->reset(['alias_vitola']);
            $this->dispatch('refresh');
            session()->flash('success', 'Alias vitola creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    public function crearTipoEmpaque()
    {
        DB::beginTransaction();

        try {
            $existingTipoEmpaque = TipoEmpaque::where('tipo_empaque', $this->tipo_empaque)->first();

            if ($existingTipoEmpaque) {
                session()->flash('error', 'El tipo de empaque ya existe.');
                return;
            }

            TipoEmpaque::create([
                'tipo_empaque' => $this->tipo_empaque,
                'estado_tipoEmpaque' => 1,
            ]);

            DB::commit();
            $this->dispatch('hideTipoEmpaqueModal');
            $this->reset(['tipo_empaque']);
            $this->dispatch('refresh');
            session()->flash('success', 'Tipo de empaque creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error al procesar la solicitud: ' . $e->getMessage());
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
            $this->vitolas = DB::table('vitola')->get(['id_vitola','vitola']),
            $this->marcas = DB::table('marca')->get(['id_marca','marca']),
            $this->alias_vitolas = DB::table('alias_vitola')->get(['id_aliasvitola', 'alias_vitola']),
            $this->capas = DB::table('capa')->get(['id_capa','capa']),
            $this->clientes = DB::table('clientes')->get(['id_cliente', 'name_cliente']),
            $this->tipo_empaques = DB::table('tipo_empaques')->get(['id_tipoempaque', 'tipo_empaque']),
            $this->roles = DB::table('roles')->get(['id_rol', 'rol']),
        ])->extends('layouts.app')->section('content');
    }
}
