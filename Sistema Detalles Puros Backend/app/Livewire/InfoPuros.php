<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InfoPuro;
use App\Models\Capa;
use App\Models\Vitola;
use App\Models\AliasVitola;
use App\Models\Marca;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

class InfoPuros extends Component
{
    public $estadoPuro = null;
    public $perPage = 25;
    public $page = 1;
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $processedCount = 0;
    public $totalCount = 0;

    public $presentacion_puro, $marca, $alias_vitola, $vitola, $capa, $codigo_puro;
    public $id_marca, $id_vitola, $id_aliasvitola, $id_capa, $estado_puro = 1;

    public $presentaciones = [], $marcas = [], $alias_vitolas = [], $vitolas = [], $capas = [], $puros = [];
    public $editing = false;
    public $codigo_puro_busqueda = '';
    public $originalCodigo = '';
    public $showModal = false;

    public $filtro_codigo_puro = null;
    public $filtro_presentacion = null;
    public $filtro_vitola = null;
    public $filtro_alias_vitola = null;
    public $filtro_capa = null;
    public $filtro_marca = null;


    public $importing = false;
    public $importStatus = null;
    public $importedCount = 0;
    public $errors = [];

    protected function rules()
    {
        $uniqueRule = 'required|string|max:255|unique:info_puro,codigo_puro';

        if ($this->editing && $this->originalCodigo === $this->codigo_puro) {
            $uniqueRule = 'required|string|max:255';
        }

        return [
            'presentacion_puro' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'alias_vitola' => 'required|string|max:255',
            'vitola' => 'required|string|max:255',
            'capa' => 'required|string|max:255',
            'codigo_puro' => $uniqueRule,
        ];
    }

    protected $messages = [
        'codigo_puro.unique' => 'Este código de puro ya existe en la base de datos.',
    ];

    public function mount()
    {


        $this->loadCodigos();
        $this->loadSelectOptions();
    }

    private function loadCodigos()
    {
        $this->puros = DB::table('info_puro')
            ->select('codigo_puro as value', 'codigo_puro as text')
            ->get()
            ->toArray();
    }

    private function loadSelectOptions()
    {
        $this->presentaciones = DB::table('info_puro')
            ->select('presentacion_puro')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return ['value' => $item->presentacion_puro, 'text' => $item->presentacion_puro];
            });

        $this->marcas = DB::table('marca')
            ->select('id_marca', 'marca')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return ['value' => $item->marca, 'text' => $item->marca, 'id' => $item->id_marca];
            });

        $this->vitolas = DB::table('vitola')
            ->select('id_vitola', 'vitola')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return ['value' => $item->vitola, 'text' => $item->vitola, 'id' => $item->id_vitola];
            });

        $this->alias_vitolas = DB::table('alias_vitola')
            ->select('id_aliasvitola', 'alias_vitola')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return ['value' => $item->alias_vitola, 'text' => $item->alias_vitola, 'id' => $item->id_aliasvitola];
            });

        $this->capas = DB::table('capa')
            ->select('id_capa', 'capa')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return ['value' => $item->capa, 'text' => $item->capa, 'id' => $item->id_capa];
            });
    }

    private function getOrCreateRelatedIds()
    {
        $marcaItem = collect($this->marcas)->firstWhere('value', $this->marca);
        if ($marcaItem) {
            $this->id_marca = $marcaItem['id'];
        } else {
            $this->id_marca = DB::table('marca')->insertGetId([
                'marca' => $this->marca,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $vitolaItem = collect($this->vitolas)->firstWhere('value', $this->vitola);
        if ($vitolaItem) {
            $this->id_vitola = $vitolaItem['id'];
        } else {
            $this->id_vitola = DB::table('vitola')->insertGetId([
                'vitola' => $this->vitola,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $aliasVitolaItem = collect($this->alias_vitolas)->firstWhere('value', $this->alias_vitola);
        if ($aliasVitolaItem) {
            $this->id_aliasvitola = $aliasVitolaItem['id'];
        } else {
            $this->id_aliasvitola = DB::table('alias_vitola')->insertGetId([
                'alias_vitola' => $this->alias_vitola,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $capaItem = collect($this->capas)->firstWhere('value', $this->capa);
        if ($capaItem) {
            $this->id_capa = $capaItem['id'];
        } else {
            $this->id_capa = DB::table('capa')->insertGetId([
                'capa' => $this->capa,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }


    public function openModal()
    {
        $this->showModal = true;
        $this->dispatch('open-modal');
    }

    public function createPuro()
    {
        $this->validate();

        $this->getOrCreateRelatedIds();

        try {
            DB::beginTransaction();

            if ($this->editing) {
                $puro = InfoPuro::where('codigo_puro', $this->originalCodigo)->first();
                if ($puro) {
                    $puro->codigo_puro = $this->codigo_puro;
                    $puro->presentacion_puro = $this->presentacion_puro;
                    $puro->id_marca = $this->id_marca;
                    $puro->id_vitola = $this->id_vitola;
                    $puro->id_aliasvitola = $this->id_aliasvitola;
                    $puro->id_capa = $this->id_capa;
                    $puro->updated_at = now();
                    $puro->save();

                    session()->flash('success', 'Puro actualizado correctamente.');
                } else {
                    session()->flash('error', 'No se encontró el puro para actualizar.');
                }
            } else {
                DB::statement('CALL InsertPuro(?,?,?,?,?,?,?)', [
                    $this->codigo_puro,
                    $this->presentacion_puro,
                    $this->id_marca,
                    $this->id_vitola,
                    $this->id_aliasvitola,
                    $this->id_capa,
                    $this->estado_puro,
                ]);

                session()->flash('success', 'Puro registrado correctamente.');
            }

            DB::commit();
            $this->loadSelectOptions();
            $this->loadCodigos();
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error al procesar la operación. Por favor, inténtelo de nuevo.');
            Log::error('Error en createPuro: ' . $e->getMessage());
        }
    }

    public function editPuro($codigoPuro)
    {

        $this->originalCodigo = $codigoPuro;

        $puro = InfoPuro::where('codigo_puro', $codigoPuro)->first();

        if ($puro) {
            $this->codigo_puro = $codigoPuro;
            $this->presentacion_puro = $puro->presentacion_puro;
            $this->id_marca = $puro->id_marca;
            $this->id_vitola = $puro->id_vitola;
            $this->id_aliasvitola = $puro->id_aliasvitola;
            $this->id_capa = $puro->id_capa;

            $marca = DB::table('marca')->where('id_marca', $puro->id_marca)->first();
            $vitola = DB::table('vitola')->where('id_vitola', $puro->id_vitola)->first();
            $aliasVitola = DB::table('alias_vitola')->where('id_aliasvitola', $puro->id_aliasvitola)->first();
            $capa = DB::table('capa')->where('id_capa', $puro->id_capa)->first();

            $this->marca = $marca ? $marca->marca : '';
            $this->vitola = $vitola ? $vitola->vitola : '';
            $this->alias_vitola = $aliasVitola ? $aliasVitola->alias_vitola : '';
            $this->capa = $capa ? $capa->capa : '';

            $this->openModal();
        }
    }

    public function filtrarPedidos()
    {
        $this->resetPage();
    }

    public function getDatosPuros()
    {
        $results = DB::select("CALL GetPuros(?, ?, ?, ?, ?, ?)", [
            $this->filtro_codigo_puro,
            $this->filtro_presentacion,
            $this->filtro_marca,
            $this->filtro_vitola,
            $this->filtro_alias_vitola,
            $this->filtro_capa

        ]);

        $collection = collect($results)->map(function ($row) {
            return [
                'codigo_puro' => $row->codigo_puro ?? '',
                'presentacion_puro' => $row->presentacion_puro ?? '',
                'marca' => $row->marca ?? '',
                'vitola' => $row->vitola ?? '',
                'alias_vitola' => $row->alias_vitola ?? '',
                'capa' => $row->capa ?? '',
                'estado_puro' => $row->estado_puro ?? '',
            ];
        });

        if (!empty($this->codigo_puro_busqueda)) {
            $collection = $collection->filter(function ($item) {
                return stripos($item['codigo_puro'], $this->codigo_puro_busqueda) !== false;
            });
        }

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

    public function eliminarPuros($codigoPuro)
    {
        try {
            $registro = InfoPuro::where('codigo_puro', $codigoPuro)->first();
            if ($registro) {
                $registro->estado_puro = 0;
                $registro->save();
                session()->flash('success', 'Se ha desactivado correctamente el puro.');
            } else {
                session()->flash('error', 'No se encontró el registro para desactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al desactivar el puro.');
            Log::error('Error en eliminarPuros: ' . $e->getMessage());
        }
    }

    public function importProducts()
    {
        try {
            set_time_limit(300);
            $apiUrl = env('APP_URL') . '/public/api/materia_prima/productos';
            $response = Http::timeout(30)->get($apiUrl);

            if (!$response->successful()) {
                throw new \Exception('Error al obtener datos de la API: ' . $response->status());
            }

            $data = $response->json();

            if (!is_array($data) || empty($data)) {
                throw new \Exception('El formato de los datos recibidos no es válido o está vacío.');
            }

            $products = $data['data'] ?? $data;

            if (!is_array($products) || empty($products)) {
                throw new \Exception('No se encontraron productos en la respuesta de la API.');
            }

            $this->totalCount = count($products);
            $this->processedCount = 0;

            DB::beginTransaction();

            foreach ($products as $product) {
                $this->processedCount++;
                $this->dispatch('progressUpdated', $this->processedCount, $this->totalCount);

                $requiredKeys = ["id", "codigo", "presentacion", "marca", "id_marca", "vitola", "id_vitola", "nombre", "id_nombre", "capa", "id_capa"];
                foreach ($requiredKeys as $key) {
                    if (!array_key_exists($key, $product)) {
                        throw new \Exception("Falta la clave '$key' en el producto: " . json_encode($product));
                    }
                }

                Marca::updateOrCreate(
                    ['id_marca' => $product['id_marca']],
                    [
                        'id_marca' => $product['id_marca'],
                        'marca' => $product['marca'],
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );

                Vitola::updateOrCreate(
                    ['id_vitola' => $product['id_vitola']],
                    [
                        'id_vitola' => $product['id_vitola'],
                        'vitola' => $product['vitola'],
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );

                AliasVitola::updateOrCreate(
                    ['id_aliasvitola' => $product['id_nombre']],
                    [
                        'id_aliasvitola' => $product['id_nombre'],
                        'alias_vitola' => $product['nombre'],
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );

                Capa::updateOrCreate(
                    ['id_capa' => $product['id_capa']],
                    [
                        'id_capa' => $product['id_capa'],
                        'capa' => $product['capa'],
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );

                InfoPuro::updateOrCreate(
                    ['id_puro' => $product['id']],
                    [
                        'codigo_puro' => $product['codigo'],
                        'presentacion_puro' => $product['presentacion'],
                        'id_marca' => $product['id_marca'],
                        'id_vitola' => $product['id_vitola'],
                        'id_aliasvitola' => $product['id_nombre'],
                        'id_capa' => $product['id_capa'],
                        'estado_puro' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            DB::commit();
            session()->flash('success', 'Datos importados exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en importProducts: ' . $e->getMessage());
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.info-puros', [
            'datosPaginados' => $this->getDatosPuros(),
            $this->vitolas = DB::table('vitola')->get(['vitola']),
            $this->marcas = DB::table('marca')->get(['marca']),
            $this->alias_vitolas = DB::table('alias_vitola')->get(['alias_vitola']),
            $this->capas = DB::table('capa')->get(['capa']),
            $this->puros = DB::table('info_puro')->get(['codigo_puro']),
            $this->presentaciones = DB::table('info_puro')->distinct()->get(['presentacion_puro']),

        ])->extends('layouts.app')->section('content');
    }
}
