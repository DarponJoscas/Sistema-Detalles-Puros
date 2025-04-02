<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\DetallePedido;
use App\Models\InfoEmpaque;
use App\Models\InfoPuro;

class DetallePedidos extends Component
{
    use WithFileUploads, WithPagination;

    public $estadoPedido = "";

    public $currentUrl;

    public $id_cliente, $descripcion_produccion, $imagen_produccion, $descripcion_empaque, $imagen_anillado;
    public $imagen_caja;
    public $cantidad_puros;

    public $id_empaque, $sampler, $anillo, $upc, $codigo_caja, $sello;
    public $id_puro;
    public $codigo_puro;
    public $presentacion_puro;
    public $marca;
    public $alias_vitola;
    public $vitola;
    public $capa;
    public $cantidad_caja;
    public $codigo_empaque;
    public $id_tipoempaque;
    public $tipo_empaque;


    public $filtro_cliente = null;
    public $filtro_codigo_puro = null;
    public $filtro_presentacion = null;
    public $filtro_vitola = null;
    public $filtro_alias_vitola = null;
    public $filtro_capa = null;
    public $filtro_codigo_empaque = null;
    public $filtro_marca = null;
    public $clientes = [];
    public $puros = [];
    public $error_puro;

    public $editing = false;
    public $id_pedido = null;
    public $imagenProduccionActual = null;
    public $imagenAnilladoActual = null;
    public $imagenCajaActual = null;

    public $vitolas, $capas, $empaques, $presentaciones, $marcas, $alias_vitolas;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';


    protected $rules = [
        'id_empaque' => 'required|string|exists:info_empaque,id_empaque',
        'descripcion_empaque' => 'nullable|string|max:255',
        'imagen_anillado' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'imagen_caja' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'id_puro' => 'nullable|integer|exists:info_puro,id_puro',
        'id_cliente' => 'required|integer|exists:clientes,id_cliente',
        'descripcion_produccion' => 'nullable|string|max:255',
        'imagen_produccion' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ];


    public function infoEmpaque()
    {
        if (empty($this->codigo_empaque)) {
            return;
        }

        $connection = DB::connection();

        $result = $connection->select('CALL ObtenerInfoEmpaque(?)', [$this->codigo_empaque]);

        if ($result) {

            $this->id_empaque = $result[0]->id_empaque ?? '';
            $this->codigo_empaque = $result[0]->codigo_empaque ?? '';
            $this->codigo_puro = $result[0]->codigo_puro ?? '';
            $this->presentacion_puro = $result[0]->presentacion_puro ?? '';
            $this->marca = $result[0]->marca ?? '';
            $this->alias_vitola = $result[0]->alias_vitola ?? '';
            $this->vitola = $result[0]->vitola ?? '';
            $this->capa = $result[0]->capa ?? '';
            $this->tipo_empaque = $result[0]->tipo_empaque ?? '';
            $this->sampler = $result[0]->sampler ?? '';
            $this->descripcion_empaque = $result[0]->descripcion_empaque ?? '';
            $this->anillo = $result[0]->anillo ?? '';
            $this->imagen_anillado = $result[0]->imagen_anillado ?? '';
            $this->sello = $result[0]->sello ?? '';
            $this->upc = $result[0]->upc ?? '';
            $this->codigo_caja = $result[0]->codigo_caja ?? '';
            $this->imagen_caja = $result[0]->imagen_caja ?? '';

            $this->error_puro = null;
        } else {
            $this->error_puro = 'Código de empaque no encontrado.';
        }
    }

    public function filtrarPedidos()
    {
        $this->resetPage();
    }

    protected $listeners = ['resetFilters' => 'resetFilters'];

    public function resetFilters()
    {
        $this->filtro_cliente = null;
        $this->filtro_codigo_puro = null;
        $this->filtro_presentacion = null;
        $this->filtro_marca = null;
        $this->filtro_vitola = null;
        $this->filtro_alias_vitola = null;
        $this->filtro_capa = null;
        $this->filtro_codigo_empaque = null;
    }

    public function getDatos()
    {

        $results = DB::select("CALL GetDetallePedido(?, ?, ?, ?, ?, ?, ?)", [
            $this->filtro_cliente,
            $this->filtro_codigo_puro,
            $this->filtro_presentacion,
            $this->filtro_vitola,
            $this->filtro_alias_vitola,
            $this->filtro_capa,
            $this->filtro_codigo_empaque
        ]);

        $collection = collect($results)->map(function ($row) {
            return [
                'id_pedido' => $row->id_pedido ?? '',
                'cliente' => $row->name_cliente ?? '',
                'codigo_puro' => $row->codigo_puro ?? '',
                'presentacion_puro' => $row->presentacion_puro ?? '',
                'marca' => $row->marca ?? '',
                'alias_vitola' => $row->alias_vitola ?? '',
                'vitola' => $row->vitola ?? '',
                'capa' => $row->capa ?? '',
                'descripcion_produccion' => $row->descripcion_produccion ?? '',
                'imagen_produccion' => $row->imagen_produccion ?? '',
                'codigo_empaque' => $row->codigo_empaque ?? '',
                'tipo_empaque' => $row->tipo_empaque ?? '',
                'descripcion_empaque' => $row->descripcion_empaque ?? '',
                'sampler' => $row->sampler ?? '',
                'imagen_anillado' => $row->imagen_anillado ?? '',
                'codigo_caja' => $row->codigo_caja ?? '',
                'imagen_caja' => $row->imagen_caja ?? '',
                'anillo' => $row->anillo ?? '',
                'sello' => $row->sello ?? '',
                'upc' => $row->upc ?? '',
                'estado_pedido' => $row->estado_pedido ?? '',
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

    public function removeImage($field, $index)
    {
        if (isset($this->$field[$index])) {
            unset($this->$field[$index]);
            $this->$field = array_values($this->$field);
        }
    }

    public function crearDetallePedido()
    {
        $this->validate();

        $imagenProduccionPath = null;
        $imagenAnilladoPath = null;
        $imagenCajaPath = null;

        try {
            $ultimoPedido = DB::table('detalle_pedido')->orderBy('id_pedido', 'desc')->first();
            $id_pedido = $ultimoPedido ? $ultimoPedido->id_pedido + 1 : 1;

            $codigo_puro = $this->codigo_puro;
            $codigo_caja = $this->codigo_caja;

            $numeroIncremental = 1;
            $fechaActual = date('Y-m-d');
            $nombreBase = "{$id_pedido}-{$codigo_puro}-{$numeroIncremental}-{$fechaActual}";

            if ($this->imagen_produccion) {
                $extension = $this->imagen_produccion->getClientOriginalExtension();
                $nombreArchivo = "{$nombreBase}.{$extension}";

                $imagenProduccionPath = $this->imagen_produccion->storeAs(
                    'imagenes/produccion',
                    $nombreArchivo,
                    'public'
                );
            }

            if ($this->imagen_anillado) {
                $extension = $this->imagen_anillado->getClientOriginalExtension();
                $numeroIncremental++;
                $nombreArchivo = "{$id_pedido}-{$codigo_puro}-{$numeroIncremental}-{$fechaActual}.{$extension}";

                $imagenAnilladoPath = $this->imagen_anillado->storeAs(
                    'imagenes/anillado',
                    $nombreArchivo,
                    'public'
                );
            }

            if ($this->imagen_caja) {
                $extension = $this->imagen_caja->getClientOriginalExtension();
                $numeroIncremental++;
                $nombreArchivo = "{$id_pedido}-{$codigo_caja}-{$numeroIncremental}-{$fechaActual}.{$extension}";

                $imagenCajaPath = $this->imagen_caja->storeAs(
                    'imagenes/caja',
                    $nombreArchivo,
                    'public'
                );
            }

            $puroDB = DB::table('info_puro')
                ->where('codigo_puro', $this->codigo_puro)
                ->first();

            if (!$puroDB) {
                session()->flash('error', 'Código de puro no encontrado.');
                return;
            }

            $id_puro = $puroDB->id_puro;

            $empaqueDB = DB::table('info_empaque')
                ->where('codigo_empaque', $this->codigo_empaque)
                ->first();

            if (!$empaqueDB) {
                session()->flash('error', 'Código de empaque no encontrado.');
                return;
            }

            $id_empaque = $empaqueDB->id_empaque;
            $descripcion_empaque = isset($empaqueDB->descripcion) ? $empaqueDB->descripcion : null;

            $id_detalle_pedido = DB::table('detalle_pedido')->insertGetId([
                'id_cliente' => $this->id_cliente,
                'id_puro' => $id_puro,
                'id_empaque' => $id_empaque,
                'descripcion_produccion' => $this->descripcion_produccion,
                'imagen_produccion' => $imagenProduccionPath,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if ($imagenAnilladoPath || $imagenCajaPath) {
                DB::table('info_empaque')->where('id_empaque', $id_empaque)->update([
                    'descripcion_empaque' => $descripcion_empaque,
                    'imagen_anillado' => $imagenAnilladoPath,
                    'imagen_caja' => $imagenCajaPath,
                    'updated_at' => now()
                ]);
            }


            session()->flash('message', 'Pedido insertado correctamente.');

            $this->dispatch('dispatch');
        } catch (\Exception $e) {

            if ($imagenProduccionPath && Storage::disk('public')->exists($imagenProduccionPath)) {
                Storage::disk('public')->delete($imagenProduccionPath);
            }
            if ($imagenAnilladoPath && Storage::disk('public')->exists($imagenAnilladoPath)) {
                Storage::disk('public')->delete($imagenAnilladoPath);
            }
            if ($imagenCajaPath && Storage::disk('public')->exists($imagenCajaPath)) {
                Storage::disk('public')->delete($imagenCajaPath);
            }

            Log::error('Error al crear detalle de pedido y empaque', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error al insertar el pedido: ' . $e->getMessage());
        }
    }

    public function closeEmpaqueModal()
    {
        $this->dispatch('hide-edit-modal');
        $this->reset(['id_cliente', 'codigo_puro']);
    }

    public function eliminarDetallePedido($id_pedido)
    {
        $registro = DetallePedido::find($id_pedido);

        if ($registro) {
            $registro->estado_pedido = 0;
            $registro->save();

            session()->flash('message', 'Registro desactivado correctamente.');
        } else {
            session()->flash('error', 'Registro no encontrado.');
        }
    }

    public function activarDetallePedido($id_pedido)
    {
        $registro = DetallePedido::find($id_pedido);

        if ($registro) {
            $registro->estado_pedido = 1;
            $registro->save();

            session()->flash('message', 'Registro activado correctamente.');
        } else {
            session()->flash('error', 'Registro no encontrado.');
        }
    }

    public function render()
    {
        return view('livewire.detalle-pedidos', [
            'datosPaginados' => $this->getDatos(),
            $this->vitolas = DB::table('vitola')->get(['vitola']),
            $this->marcas = DB::table('marca')->get(['marca']),
            $this->alias_vitolas = DB::table('alias_vitola')->get(['alias_vitola']),
            $this->capas = DB::table('capa')->get(['capa']),
            $this->puros = DB::table('info_puro')->get(['codigo_puro']),
            $this->presentaciones = DB::table('info_puro')->distinct()->get(['presentacion_puro']),
            $this->clientes = DB::table('clientes')->get(['id_cliente', 'name_cliente']),
            $this->empaques = DB::table('info_empaque')->get(['codigo_empaque']),
        ])->extends('layouts.app')->section('content');
    }
}
