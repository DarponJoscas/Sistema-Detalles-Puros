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


class DetallePedidos extends Component
{
    use WithFileUploads, WithPagination;

    public $selectedCliente = null;
    public $selectedCodigoPuro = null;
    public $estadoPedido = null;

    public $currentUrl;

    public $id_cliente;
    public $descripcion_produccion;
    public $imagen_produccion;
    public $descripcion_empaque;
    public $imagen_anillado;
    public $imagen_caja;
    public $cantidad_puros;

    public $id_emapaque;
    public $id_puro;
    public $codigo_puro;
    public $presentacion_puro;
    public $marca;
    public $alias_vitola;
    public $vitola;
    public $capa;
    public $cantidad_caja;
    public $codigo_empaque;
    public $tipo_empaque;


    public $clientes = [];
    public $puros = [];
    public $error_puro;

    public $editing = false;
    public $id_pedido = null;
    public $imagenProduccionActual = null;
    public $imagenAnilladoActual = null;
    public $imagenCajaActual = null;

    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'codigo_empaque' => 'required|string|max:15|unique:empaque,codigo_empaque',
        'tipo_empaque' => 'nullable|string|max:50',
        'descripcion_empaque' => 'nullable|string|max:255',
        'imagen_anillado' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'imagen_caja' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'cantidad_caja' => 'nullable|integer|min:1',
        'codigo_puro' => 'required|string|exists:info_puro,codigo_puro',
        'id_cliente' => 'required|integer|exists:clientes,id_cliente',
        'descripcion_produccion' => 'nullable|string|max:255',
        'imagen_produccion' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ];

    public function mount($id_pedido = null)
    {
        $this->id_pedido = $id_pedido;
        $this->clientes = DB::table('clientes')->get(['id_cliente', 'name_cliente']);
        $this->puros = DB::table('info_puro')->get(['id_puro', 'codigo_puro']);

        $this->currentUrl = request()->url();

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
                    'marca',
                    'alias_vitola',
                    'vitola',
                    'capa',
                    'codigo_puro'
                ]);
                $this->error_puro = null;
                return;
            }

            $puro = DB::table('info_puro')
                ->where('codigo_puro', trim($this->codigo_puro))
                ->first();

            if ($puro) {

                $this->codigo_puro = $puro->codigo_puro;
                $this->presentacion_puro = $puro->presentacion_puro ?? '';

                $marca = DB::table('marca')
                    ->where('id_marca', $puro->id_marca)
                    ->first();
                $this->marca = $marca ? $marca->marca : '';

                $aliasVitola = DB::table('alias_vitola')
                    ->where('id_aliasvitola', $puro->id_aliasvitola)
                    ->first();
                $this->alias_vitola = $aliasVitola ? $aliasVitola->alias_vitola : '';

                $vitola = DB::table('vitola')
                    ->where('id_vitola', $puro->id_vitola)
                    ->first();
                $this->vitola = $vitola ? $vitola->vitola : '';

                $capa = DB::table('capa')
                    ->where('id_capa', $puro->id_capa)
                    ->first();
                $this->capa = $capa ? $capa->capa : '';

                $this->error_puro = null;
            } else {
                $this->reset([
                    'presentacion_puro',
                    'marca_puro',
                    'alias_vitola',
                    'vitola',
                    'capa',
                    'codigo_puro',
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
                'marca',
                'alias_vitola',
                'vitola',
                'capa',
                'codigo_puro',
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
                'marca' => $row->marca ?? '',
                'alias_vitola' => $row->alias_vitola ?? '',
                'vitola' => $row->vitola ?? '',
                'capa' => $row->capa ?? '',
                'descripcion_produccion' => $row->descripcion_produccion ?? '',
                'imagen_produccion' => $row->imagen_produccion,
                'codigo_empaque' => $row->codigo_empaque ?? '',
                'tipo_empaque' => $row->tipo_empaque ?? '',
                'descripcion_empaque' => $row->descripcion_empaque ?? '',
                'imagen_anillado' => $row->imagen_anillado,
                'imagen_caja' => $row->imagen_caja,
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

        $imagenProduccionPath = null;
        $imagenAnilladoPath = null;
        $imagenCajaPath = null;

        try {
            $ultimoPedido = DB::table('detalle_pedido')->orderBy('id_pedido', 'desc')->first();
            $id_pedido = $ultimoPedido ? $ultimoPedido->id_pedido + 1 : 1;

            $codigo_puro = $this->codigo_puro;

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
                $nombreArchivo = "{$id_pedido}-{$codigo_puro}-{$numeroIncremental}-{$fechaActual}.{$extension}";

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

            DB::statement('CALL InsertarDetallePedidoYEmpaque(?,?,?,?,?,?,?,?,?,?)', [
                $this->id_cliente,
                $id_puro,
                $this->descripcion_produccion,
                $imagenProduccionPath,
                $this->codigo_empaque,
                $this->tipo_empaque,
                $this->descripcion_empaque,
                $imagenAnilladoPath,
                $imagenCajaPath,
                $this->cantidad_caja,
            ]);

            session()->flash('message', 'Pedido insertado correctamente.');

            $this->reset([
                'id_cliente',
                'codigo_puro',
                'descripcion_produccion',
                'imagen_produccion',
                'codigo_empaque',
                'tipo_empaque',
                'descripcion_empaque',
                'imagen_anillado',
                'imagen_caja',
                'cantidad_caja'
            ]);
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

    public function isEmpaquePage()
    {
        return str_contains($this->currentUrl, '/empaque');
    }

    public function isProduccionPage()
    {
        return str_contains($this->currentUrl, '/produccion');
    }

    private function loadSelectOptions()
    {
        $this->codigo_puro = DB::table('info_puro')
            ->select('codigo_puro')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return ['value' => $item->codigo_puro, 'text' => $item->codigo_puro];
            });
    }

    public function editarPedido($id_pedido)
    {
        // Obtener los datos del pedido
        $pedido = DB::table('detalle_pedido')
            ->join('empaque', 'detalle_pedido.id_empaque', '=', 'empaque.id_empaque')
            ->join('info_puro', 'detalle_pedido.id_puro', '=', 'info_puro.id_puro')
            ->where('detalle_pedido.id_pedido', $id_pedido)
            ->first();

        if (!$pedido) {
            session()->flash('error', 'Pedido no encontrado.');
            return;
        }

        // Establecer los valores actuales para editar
        $this->id_pedido = $pedido->id_pedido;
        $this->id_cliente = $pedido->id_cliente;
        $this->codigo_puro = $pedido->codigo_puro;
        $this->descripcion_produccion = $pedido->descripcion_produccion;
        $this->codigo_empaque = $pedido->codigo_empaque;
        $this->tipo_empaque = $pedido->tipo_empaque;
        $this->descripcion_empaque = $pedido->descripcion_empaque;
        $this->cantidad_caja = $pedido->cantidad_caja;

        // Almacenar las rutas de las imágenes actuales
        $this->imagenProduccionActual = $pedido->imagen_produccion;
        $this->imagenAnilladoActual = $pedido->imagen_anillado;
        $this->imagenCajaActual = $pedido->imagen_caja;

        // Cambiar el modo a edición
        $this->editing = true;
    }

    public function actualizarPedido()
    {
        $this->validate();

        try {
            $imagenProduccionPath = $this->imagenProduccionActual;
            $imagenAnilladoPath = $this->imagenAnilladoActual;
            $imagenCajaPath = $this->imagenCajaActual;

            // Manejar las nuevas imágenes si se han cargado
            if ($this->imagen_produccion) {
                // Eliminar la imagen anterior si existe
                if ($imagenProduccionPath && Storage::disk('public')->exists($imagenProduccionPath)) {
                    Storage::disk('public')->delete($imagenProduccionPath);
                }

                $numeroIncremental = 1;
                $fechaActual = date('Y-m-d');
                $nombreBase = "{$this->id_pedido}-{$this->codigo_puro}-{$numeroIncremental}-{$fechaActual}";
                $extension = $this->imagen_produccion->getClientOriginalExtension();
                $nombreArchivo = "{$nombreBase}.{$extension}";

                $imagenProduccionPath = $this->imagen_produccion->storeAs(
                    'imagenes/produccion',
                    $nombreArchivo,
                    'public'
                );
            }

            // Procesar imagen_anillado si se ha subido una nueva
            if ($this->imagen_anillado) {
                if ($imagenAnilladoPath && Storage::disk('public')->exists($imagenAnilladoPath)) {
                    Storage::disk('public')->delete($imagenAnilladoPath);
                }

                $numeroIncremental = 2;
                $fechaActual = date('Y-m-d');
                $nombreArchivo = "{$this->id_pedido}-{$this->codigo_puro}-{$numeroIncremental}-{$fechaActual}.{$this->imagen_anillado->getClientOriginalExtension()}";

                $imagenAnilladoPath = $this->imagen_anillado->storeAs(
                    'imagenes/anillado',
                    $nombreArchivo,
                    'public'
                );
            }

            // Procesar imagen_caja si se ha subido una nueva
            if ($this->imagen_caja) {
                if ($imagenCajaPath && Storage::disk('public')->exists($imagenCajaPath)) {
                    Storage::disk('public')->delete($imagenCajaPath);
                }

                $numeroIncremental = 3;
                $fechaActual = date('Y-m-d');
                $nombreArchivo = "{$this->id_pedido}-{$this->codigo_puro}-{$numeroIncremental}-{$fechaActual}.{$this->imagen_caja->getClientOriginalExtension()}";

                $imagenCajaPath = $this->imagen_caja->storeAs(
                    'imagenes/caja',
                    $nombreArchivo,
                    'public'
                );
            }

            // Actualizar el detalle del pedido
            DB::table('detalle_pedido')
                ->where('id_pedido', $this->id_pedido)
                ->update([
                    'id_cliente' => $this->id_cliente,
                    'descripcion_produccion' => $this->descripcion_produccion,
                    'imagen_produccion' => $imagenProduccionPath,
                    'updated_at' => now()
                ]);

            // Actualizar el empaque
            $empaque = DB::table('detalle_pedido')
                ->where('id_pedido', $this->id_pedido)
                ->first();

            if ($empaque && $empaque->id_empaque) {
                DB::table('empaque')
                    ->where('id_empaque', $empaque->id_empaque)
                    ->update([
                        'codigo_empaque' => $this->codigo_empaque,
                        'tipo_empaque' => $this->tipo_empaque,
                        'descripcion_empaque' => $this->descripcion_empaque,
                        'imagen_anillado' => $imagenAnilladoPath,
                        'imagen_caja' => $imagenCajaPath,
                        'cantidad_caja' => $this->cantidad_caja,
                        'updated_at' => now()
                    ]);
            }

            session()->flash('message', 'Pedido actualizado correctamente.');
            $this->cancelarEdicion();

        } catch (\Exception $e) {
            Log::error('Error al actualizar detalle de pedido y empaque', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error al actualizar el pedido: ' . $e->getMessage());
        }
    }

    public function cancelarEdicion()
    {
        $this->reset([
            'id_pedido',
            'id_cliente',
            'codigo_puro',
            'descripcion_produccion',
            'imagen_produccion',
            'codigo_empaque',
            'tipo_empaque',
            'descripcion_empaque',
            'imagen_anillado',
            'imagen_caja',
            'cantidad_caja',
            'imagenProduccionActual',
            'imagenAnilladoActual',
            'imagenCajaActual'
        ]);

        $this->editing = false;
    }
    public function render()
    {
        return view('livewire.detalle-pedidos', [
            'datosPaginados' => $this->getDatos(),
        ])->extends('layouts.app')->section('content');
    }
}
