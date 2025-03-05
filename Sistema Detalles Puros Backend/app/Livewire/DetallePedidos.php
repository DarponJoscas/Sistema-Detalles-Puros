<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\DetallePedido;
use App\Models\InfoPuro;
use App\Models\Cliente;
use Illuminate\Console\View\Components\Info;

class DetallePedidos extends Component
{
    use WithFileUploads, WithPagination;

    public $selectedCliente = null;
    public $selectedCodigoPuro = null;
    public $estadoPedido = null;

    public $id_pedido;

    public $id_cliente;
    public $id_puro;
    public $id_empaque;
    public $descripcion_produccion;
    public $imagen_produccion;
    public $descripcion_empaque;
    public $imagen_anillado;
    public $imagen_caja;
    public $cantidad_puros;

    public $codigo_puro;
    public $presentacion_puro;
    public $marca_puro;
    public $alias_vitola;
    public $vitola;
    public $capa_puro;
    public $cantidad_caja;
    public $codigo_empaque;
    public $tipo_empaque;

    public $clientes = [];
    public $puros = [];
    public $error_puro;

    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'codigo_empaque' => 'required|string|max:15|unique:empaque,codigo_empaque',
        'tipo_empaque' => 'nullable|string|max:50',
        'descripcion_empaque' => 'nullable|string|max:255',
        'imagen_anillado' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'imagen_caja' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'cantidad_caja' => 'nullable|integer|min:1',
        'id_puro' => 'required|integer|exists:info_puro,id_puro',
        'id_cliente' => 'required|integer|exists:clientes,id_cliente',
        'descripcion_produccion' => 'nullable|string|max:255',
        'imagen_produccion' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ];

    public function mount($id_pedido = null)
    {
        $this->id_pedido = $id_pedido;
        $this->clientes = DB::table('clientes')->get(['id_cliente', 'name_cliente']);
        $this->puros = DB::table('info_puro')->get(['id_puro', 'codigo_puro']);

        if ($id_pedido) {
            $this->cargaredidos($id_pedido);
        }
    }

    public function infoPuro()
    {
        try {
            if (empty($this->codigo_puro)) {
                $this->reset([
                    'presentacion_puro',
                    'marca_puro',
                    'alias_vitola',
                    'vitola',
                    'capa_puro',
                    'id_puro'
                ]);
                $this->error_puro = null;
                return;
            }

            $puro = DB::table('info_puro')->where('codigo_puro', trim($this->codigo_puro))->first();

            if ($puro) {
                $this->id_puro = $puro->id_puro;
                $this->presentacion_puro = $puro->presentacion_puro ?? '';
                $this->marca_puro = $puro->marca_puro ?? '';
                $this->alias_vitola = $puro->alias_vitola ?? '';
                $this->vitola = $puro->vitola ?? '';
                $this->capa_puro = $puro->capa_puro ?? '';
                $this->error_puro = null;
            } else {
                $this->reset([
                    'presentacion_puro',
                    'marca_puro',
                    'alias_vitola',
                    'vitola',
                    'capa_puro',
                    'id_puro'
                ]);
                $this->error_puro = 'Código de puro no encontrado.';
                Log::error('Código de puro no encontrado', ['codigo_puro' => $this->codigo_puro]);
            }
        } catch (\Exception $e) {
            Log::error('Error en infoPuro', [
                'codigo_puro' => $this->codigo_puro,
                'error' => $e->getMessage()
            ]);
            $this->error_puro = 'Error al procesar la información del puro: ' . $e->getMessage();
            $this->reset([
                'presentacion_puro',
                'marca_puro',
                'alias_vitola',
                'vitola',
                'capa_puro',
                'id_puro'
            ]);
        }
    }

    public function filtrarPedidos($estado)
    {
        $this->estadoPedido = $estado;
    }

    public function getDatos()
    {
        $results = DB::select("CALL GetDetallePedido(?)", [$this->estadoPedido]);

        $collection = collect($results)->map(function ($row) {
            return [
                'id_pedido' => $row->id_pedido ?? '',
                'cliente' => $row->name_cliente ?? '',
                'codigo_puro' => $row->codigo_puro ?? '',
                'presentacion_puro' => $row->presentacion_puro ?? '',
                'marca_puro' => $row->marca_puro ?? '',
                'alias_vitola' => $row->alias_vitola ?? '',
                'vitola' => $row->vitola ?? '',
                'capa_puro' => $row->capa_puro ?? '',
                'descripcion_produccion' => $row->descripcion_produccion ?? '',
                'imagen_produccion' => $row->imagen_produccion ?? '',
                'id_empaque' => $row->id_empaque ?? '',
                'codigo_empaque' => $row->codigo_empaque ?? '',
                'tipo_empaque' => $row->tipo_empaque ?? '',
                'descripcion_empaque' => $row->descripcion_empaque ?? '',
                'imagen_anillado' => $row->imagen_anillado ?? '',
                'imagen_caja' => $row->imagen_caja ?? '',
                'cantidad_caja' => $row->cantidad_caja ?? '',
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



    public function crearDetallePedidoYEmpaque()
    {
        $this->validate();

        DB::statement('CALL InsertarDetallePedidoYEmpaque(?,?,?,?,?,?,?,?,?,?)', [
            $this->id_cliente,
            $this->id_puro,
            $this->descripcion_produccion,
            $this->imagen_produccion,
            $this->codigo_empaque,
            $this->tipo_empaque,
            $this->descripcion_empaque,
            $this->imagen_anillado,
            $this->imagen_caja,
            $this->cantidad_caja,
        ]);

        $this->reset([
            'id_cliente',
            'id_puro',
            'descripcion_produccion',
            'imagen_produccion',
            'codigo_empaque',
            'tipo_empaque',
            'descripcion_empaque',
            'imagen_anillado',
            'imagen_caja',
            'cantidad_caja'
        ]);

        session()->flash('message', 'Usuario insertado correctamente.');
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

    public function render()
    {
        return view('livewire.detalle-pedidos', [
            'clientes' => $this->clientes,
            'datosPaginados' => $this->getDatos(),
        ])->extends('layouts.administracion')->section('content');
    }
}
