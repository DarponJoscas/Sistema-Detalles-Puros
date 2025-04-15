<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\InfoPuro;
use App\Models\InfoEmpaque;
use App\Models\TipoEmpaque;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class InfoEmpaques extends Component
{
    use WithPagination;

    public $totalGuardados = 0;
    public $totalNoGuardados = 0;
    public $filtro_tipo_empaque = null;
    public $empaques = [], $puros = [], $tiposEmpaque = [];
    public $perPage = 25;
    public $filtro_codigo_puro = null;
    public $filtro_presentacion = [];
    public $filtro_marca = null;
    public $filtro_vitola = null;
    public $filtro_alias_vitola = null;
    public $filtro_capa = null;
    public $filtro_codigo_empaque = null;
    public $vitolas, $marcas, $alias_vitolas, $capas, $presentaciones;
    protected $paginationTheme = 'bootstrap';
    public $imagen_anillado = [];
    public $imagen_caja = [];
    public $imagen_produccion = [];

    public function filtrarPedidos()
    {
        $this->resetPage();
        $this->dispatch('refresh');
    }

    public function getInfoEmpaque()
    {
        $presentacionesString = !empty($this->filtro_presentacion) ? implode(',', $this->filtro_presentacion) : null;

        $results = DB::select("CALL GetInfoEmpaque(?, ?, ?, ?, ?, ?, ?, ?)", [
            $this->filtro_codigo_empaque,
            $presentacionesString,
            $this->filtro_codigo_puro,
            $this->filtro_tipo_empaque,
            $this->filtro_marca,
            $this->filtro_vitola,
            $this->filtro_alias_vitola,
            $this->filtro_capa,
        ]);

        $collection = collect($results)->map(function ($row) {
            return [
                'id_empaque' => $row->id_empaque ?? '',
                'codigo_empaque' => $row->codigo_empaque ?? '',
                'codigo_puro' => $row->codigo_puro ?? '',
                'presentacion_puro' => $row->presentacion_puro ?? '',
                'marca' => $row->marca ?? '',
                'vitola' => $row->vitola ?? '',
                'alias_vitola' => $row->alias_vitola ?? '',
                'capa' => $row->capa ?? '',
                'sampler' => $row->sampler ?? '',
                'tipo_empaque' => $row->tipo_empaque ?? '',
                'descripcion_empaque' => $row->descripcion_empaque ?? '',
                'anillo' => $row->anillo ?? '',
                'imagen_anillado' => $row->imagen_anillado ? json_decode($row->imagen_anillado) : [],
                'sello' => $row->sello ?? '',
                'upc' => $row->upc ?? '',
                'codigo_caja' => $row->codigo_caja ?? '',
                'imagen_caja' => $row->imagen_caja ? json_decode($row->imagen_caja) : [],
                'estado_empaque' => $row->estado_empaque ?? '',
                'created_at' => $row->created_at ?? '',
                'updated_at' => $row->updated_at ?? ''
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

    public function importEmpaque()
    {
        try {
            set_time_limit(300);
            $apiUrl = env('APP_URL') . 'api/clase_producto/empaque';
            $response = Http::timeout(30)->get($apiUrl);

            if (!$response->successful()) {
                throw new \Exception('Error al obtener datos de la API: ' . $response->status());
            }

            $data = $response->json();
            if (!is_array($data) || empty($data)) {
                throw new \Exception('El formato de los datos recibidos no es válido o está vacío.');
            }

            $empaques = $data['data'] ?? $data;
            if (!is_array($empaques) || empty($empaques)) {
                throw new \Exception('No se encontraron productos en la respuesta de la API.');
            }

            DB::beginTransaction();
            $guardados = 0;
            $noGuardados = 0;

            foreach ($empaques as $empaque) {
                try {
                    $requiredKeys = ["id_producto", "capa", "item", "codigo_producto", "codigo_caja", "des", "presentacion", "anillo", "cello", "upc", "marca", "vitola", "nombre", "tipo_empaque", "sampler"];
                    foreach ($requiredKeys as $key) {
                        if (!array_key_exists($key, $empaque)) {
                            throw new \Exception("Falta la clave '$key' en el producto: " . json_encode($empaque));
                        }
                    }

                    $infoPuro = InfoPuro::where('codigo_puro', $empaque['codigo_producto'])->first();
                    $idPuro = $infoPuro ? $infoPuro->id_puro : null;

                    $tipoEmpaque = TipoEmpaque::firstOrCreate(
                        ['tipo_empaque' => $empaque['tipo_empaque']],
                        ['estado_tipoEmpaque' => 1, 'created_at' => now(), 'updated_at' => now()]
                    );

                    $estadoEmpaque = strtolower($empaque['sampler']) === 'si' ? 1 : 0;
                    $anillo = strtolower($empaque['anillo']) === 'si' ? 1 : 0;
                    $cello = strtolower($empaque['cello']) === 'si' ? 1 : 0;
                    $upc = strtolower($empaque['upc']) === 'si' ? 1 : 0;

                    InfoEmpaque::updateOrCreate(
                        ['id_empaque' => $empaque['id_producto']],
                        [
                            'codigo_empaque' => $empaque['item'] ?? null,
                            'id_puro' => $idPuro,
                            'sampler' => $estadoEmpaque,
                            'id_tipoempaque' => $tipoEmpaque->id_tipoempaque,
                            'descripcion_empaque' => $empaque['des'] ?? null,
                            'anillo' => $anillo,
                            'imagen_anillado' => null,
                            'sello' => $cello,
                            'upc' => $upc,
                            'codigo_caja' => $empaque['codigo_caja'] ?? null,
                            'imagen_caja' => null,
                            'estado_empaque' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    Log::info('Registro guardado exitosamente: ' . json_encode($empaque));
                    $guardados++;
                } catch (\Exception $e) {
                    Log::error('Error al procesar empaque: ' . json_encode($empaque) . ' - ' . $e->getMessage());
                    $noGuardados++;
                }
            }

            DB::commit();
            $this->dispatch('swal', json_encode([
                'title' => 'Éxito',
                'text' => 'Datos importados exitosamente.',
                'icon' => 'success',
            ]));
            $this->totalGuardados = $guardados;
            $this->totalNoGuardados = $noGuardados;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error general en la importación de empaques: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.info-empaques', [
            'datosPaginados' => $this->getInfoEmpaque(),
            'totalGuardados' => $this->totalGuardados,
            'totalNoGuardados' => $this->totalNoGuardados,
            $this->vitolas = DB::table('vitola')->get(['vitola']),
            $this->marcas = DB::table('marca')->get(['marca']),
            $this->alias_vitolas = DB::table('alias_vitola')->get(['alias_vitola']),
            $this->capas = DB::table('capa')->get(['capa']),
            $this->puros = DB::table('info_puro')->get(['codigo_puro']),
            $this->presentaciones = DB::table('info_puro')->distinct()->get(['presentacion_puro']),
            $this->empaques = DB::table('info_empaque')->get(['codigo_empaque']),
        ])->extends('layouts.app')->section('content');
    }
}
