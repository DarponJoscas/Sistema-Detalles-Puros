<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\DetallePedido;
use App\Models\InfoEmpaque;
use App\Models\InfoPuro;
use Illuminate\Support\Facades\Validator;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;

class Produccion extends Component
{
    use WithFileUploads, WithPagination;

    public $estadoPedido = "";

    public $currentUrl;

    public $id_cliente, $descripcion_produccion, $id_empaque, $codigo_empaque;
    public $id_puro;
    public $codigo_puro;
    public $presentacion_puro;
    public $marca;
    public $alias_vitola;
    public $vitola;
    public $capa;

    public $imagen_produccion_existentes = [];
    public $imagen_anillado_existentes = [];
    public $imagen_caja_existentes = [];
    public $imagen_produccion_nuevas = [];
    public $imagen_anillado_nuevas = [];
    public $imagen_caja_nuevas = [];

    public $imagen_anillado = [];
    public $imagen_caja = [];
    public $imagen_produccion = [];

    public $filtro_cliente = null;
    public $filtro_codigo_puro = null;
    public $filtro_codigo_empaque = null;
    public $filtro_presentacion = null;
    public $filtro_vitola = null;
    public $filtro_alias_vitola = null;
    public $filtro_capa = null;
    public $filtro_marca = null;
    public $clientes = [];
    public $puros = [];
    public $error_puro;

    public $id_pedido;
    public $imagenProduccionActual = null;
    public $imagenAnilladoActual = null;
    public $imagenCajaActual = null;

    public $vitolas, $capas, $empaques, $presentaciones, $marcas, $alias_vitolas;
    public $perPage = 25;
    protected $paginationTheme = 'bootstrap';


    public function getAuthUserId()
    {
        return Auth::user()->id_usuario;
    }

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


            $this->error_puro = null;
        } else {

            $this->error_puro = 'Código de empaque no encontrado.';

            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'No se encontró el empaque con el código proporcionado.',
                'icon' => 'error',
            ]));
        }
    }


    public function filtrarPedidos()
    {
        $this->resetPage();
    }


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
                'id_empaque' => $row->id_empaque ?? '',
                'id_cliente' => $row->id_cliente ?? '',
                'cliente' => $row->name_cliente ?? '',
                'codigo_puro' => $row->codigo_puro ?? '',
                'presentacion_puro' => $row->presentacion_puro ?? '',
                'marca' => $row->marca ?? '',
                'alias_vitola' => $row->alias_vitola ?? '',
                'vitola' => $row->vitola ?? '',
                'capa' => $row->capa ?? '',
                'descripcion_produccion' => $row->descripcion_produccion ?? '',
                'imagen_produccion' => $row->imagen_produccion ? json_decode($row->imagen_produccion) : [],
                'codigo_empaque' => $row->codigo_empaque ?? '',
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

    public function editarPedido($id_pedido)
    {
        $pedido = DetallePedido::find($id_pedido);

        if ($pedido) {

            $this->id_pedido = $pedido->id_pedido;
            $this->id_cliente = $pedido->id_cliente;
            $this->id_puro = $pedido->id_puro;
            $this->codigo_empaque = $pedido->codigo_empaque;
            $this->descripcion_produccion = $pedido->descripcion_produccion;

            $empaque = InfoEmpaque::find($pedido->id_empaque);
            if ($empaque) {
                $this->codigo_empaque = $empaque->codigo_empaque;
            } else {
                $this->codigo_empaque = 'Código no encontrado';
            }

            $puro = InfoPuro::find($pedido->id_puro);
            if ($puro) {
                $this->codigo_puro = $puro->codigo_puro;
            } else {
                $this->codigo_puro = 'Código no encontrado';
            }

            $this->infoEmpaque();
            $this->obtenerImagenesHistorial($id_pedido);
            $this->dispatch('show-edit-modal');
        } else {
            Log::error("Pedido no encontrado con ID: " . $id_pedido);

            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'No se encontró el pedido con el ID proporcionado.',
                'icon' => 'error',
            ]));
        }
    }


    public function obtenerImagenesHistorial($id_pedido)
    {
        $historial = DB::table('historial_imagenes')
            ->where('id_pedido', $id_pedido)
            ->orderBy('fecha_cambio', 'desc')
            ->first();
        if ($historial) {
            $this->imagen_anillado = json_decode($historial->imagen_anillado) ?? [];
            $this->imagen_produccion = json_decode($historial->imagen_produccion) ?? [];
            $this->imagen_caja = json_decode($historial->imagen_caja) ?? [];

            $this->imagen_anillado_existentes = $this->imagen_anillado;
            $this->imagen_produccion_existentes = $this->imagen_produccion;
            $this->imagen_caja_existentes = $this->imagen_caja;
        } else {
            $this->imagen_produccion = [];
            $this->imagen_anillado = [];
            $this->imagen_caja = [];
            $this->imagen_produccion_existentes = [];
            $this->imagen_anillado_existentes = [];
            $this->imagen_caja_existentes = [];
        }
    }



    public function crearDetallePedido()
    {
        $validator = Validator::make(
            [
                'id_empaque' => $this->id_empaque,
                'id_cliente' => $this->id_cliente,
                'descripcion_produccion' => $this->descripcion_produccion,
                'imagen_produccion' => $this->imagen_produccion,
            ],
            []
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => $validator->errors()->first(),
                'icon' => 'error',
            ]));
            return;
        }

        try {
            DB::beginTransaction();

            $puroDB = DB::table('info_puro')->where('codigo_puro', $this->codigo_puro)->first();
            $empaqueDB = DB::table('info_empaque')->where('codigo_empaque', $this->codigo_empaque)->first();

            if (!$puroDB || !$empaqueDB) {
                $this->dispatch('swal', json_encode([
                    'title' => 'Error',
                    'text' => 'Código de puro o empaque no encontrado.',
                    'icon' => 'error',
                ]));
                return;
            }

            $id_puro = $puroDB->id_puro;
            $id_empaque = $empaqueDB->id_empaque;
            $codigo_puro = $this->codigo_puro;
            $codigo_caja = $this->codigo_caja ?? '';
            $fechaActual = now()->format('Ymd_His');

            DetallePedido::where('id_pedido', $this->id_pedido)->update([
                'descripcion_produccion' => $this->descripcion_produccion,
                'updated_at' => now()
            ]);

            Bitacora::create([
                'descripcion' => 'Se actualizó la descripción del pedido con ID: ' . $this->id_pedido . 'La descripción de la producción es: ' . $this->descripcion_produccion,
                'accion' => 'Actualización',
                'id_usuario' => $this->getAuthUserId(),
            ]);

            $historialExistente = DB::table('historial_imagenes')
                ->where('id_pedido', $this->id_pedido)
                ->orderBy('fecha_cambio', 'desc')
                ->first();

            $imagenesAnilladoPaths = $historialExistente ? json_decode($historialExistente->imagen_anillado) : [];
            $imagenesCajaPaths = $historialExistente ? json_decode($historialExistente->imagen_caja) : [];

            $imagenesProduccionPaths = $this->imagen_produccion_existentes;

            $ultimoIndexProduccion = 0;
            if (!empty($imagenesProduccionPaths)) {
                foreach ($imagenesProduccionPaths as $imagenPath) {
                    if (preg_match('/PROD-(\d+)-/', $imagenPath, $matches)) {
                        $index = (int) $matches[1];
                        if ($index >= $ultimoIndexProduccion) {
                            $ultimoIndexProduccion = $index + 1;
                        }
                    }
                }
            }

            if (!empty($this->imagen_produccion_nuevas) && is_array($this->imagen_produccion_nuevas)) {
                foreach ($this->imagen_produccion_nuevas as $imagen) {
                    if ($imagen instanceof UploadedFile) {
                        $extension = 'jpg';
                        $nombreArchivo = "{$this->id_pedido}-{$codigo_puro}-PROD-{$ultimoIndexProduccion}-{$fechaActual}.{$extension}";
                        $path = $imagen->storeAs("imagenes/produccion", $nombreArchivo, 'public');
                        $imagenesProduccionPaths[] = $path;

                        logger("Nueva imagen de producción guardada: {$nombreArchivo}");
                        $ultimoIndexProduccion++;
                    }
                }
            }

            // Almacenar en el historial
            DB::table('historial_imagenes')->insert([
                'id_pedido' => $this->id_pedido,
                'imagen_produccion' => !empty($imagenesProduccionPaths) ? json_encode($imagenesProduccionPaths) : null,
                'imagen_anillado' => !empty($imagenesAnilladoPaths) ? json_encode($imagenesAnilladoPaths) : null,
                'imagen_caja' => !empty($imagenesCajaPaths) ? json_encode($imagenesCajaPaths) : null,
                'fecha_cambio' => now()
            ]);

            DB::commit();

            $this->dispatch('swal', json_encode([
                'title' => 'Éxito',
                'text' => 'Pedido actualizado correctamente.',
                'icon' => 'success',
            ]));

            $this->dispatch('hide-edit-modal');
            $this->reset([
                'id_pedido',
                'id_cliente',
                'id_puro',
                'clientes',
                'id_empaque',
                'codigo_puro',
                'codigo_empaque',
                'presentacion_puro',
                'marca',
                'alias_vitola',
                'vitola',
                'capa',
                'upc',
                'tipo_empaque',
                'anillo',
                'sampler',
                'sello',
                'upc',
                'descripcion_produccion',
                'descripcion_empaque',
                'imagen_produccion',
                'imagen_anillado',
                'imagen_caja',
                'imagen_produccion_existentes',
                'imagen_anillado_existentes',
                'imagen_caja_existentes',
                'imagen_produccion_nuevas',
                'imagen_anillado_nuevas',
                'imagen_caja_nuevas',
                'imagenProduccionActual',
                'imagenAnilladoActual',
                'imagenCajaActual',
                'error_puro'
            ]);

            $this->dispatch('reset-select-cliente');
            $this->dispatch('dispatch');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error al actualizar pedido: ' . $e->getMessage());

            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Ocurrió un error al actualizar el pedido.',
                'icon' => 'error',
            ]));
        }
    }

    public function eliminarImagenExistente($tipo, $index)
    {
        switch ($tipo) {

            case 'produccion':
                if (isset($this->imagen_produccion[$index])) {
                    unset($this->imagen_produccion[$index]);
                    $this->imagen_produccion = array_values($this->imagen_produccion);

                    if (isset($this->imagen_produccion_existentes[$index])) {
                        unset($this->imagen_produccion_existentes[$index]);
                        $this->imagen_produccion_existentes = array_values($this->imagen_produccion_existentes);
                    }
                }
                break;
        }
    }

    public function closeEmpaqueModal()
    {
        $this->dispatch('hide-edit-modal');

        $this->reset([
            'id_pedido',
            'id_cliente',
            'id_puro',
            'clientes',
            'id_empaque',
            'codigo_puro',
            'codigo_empaque',
            'presentacion_puro',
            'marca',
            'alias_vitola',
            'vitola',
            'capa',

            'descripcion_produccion',
            'imagen_produccion',
            'imagen_anillado',
            'imagen_caja',
            'imagen_produccion_existentes',
            'imagen_anillado_existentes',
            'imagen_caja_existentes',
            'imagen_produccion_nuevas',
            'imagen_anillado_nuevas',
            'imagen_caja_nuevas',
            'imagenProduccionActual',
            'imagenAnilladoActual',
            'imagenCajaActual',
            'error_puro'
        ]);

        $this->dispatch('reset-select-cliente');
    }


    public function render()
    {
        return view('livewire.produccion', [
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
