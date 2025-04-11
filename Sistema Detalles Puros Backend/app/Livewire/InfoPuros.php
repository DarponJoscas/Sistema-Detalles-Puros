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
use Illuminate\Support\Facades\Validator;

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
    public $id_marca, $id_vitola, $id_aliasvitola, $id_capa, $estado_puro = 1, $id_puro;

    public $presentaciones = [], $marcas = [], $alias_vitolas = [], $vitolas = [], $capas = [], $puros = [];
    public $editing = false;
    public $codigo_puro_busqueda = '';
    public $originalCodigo = '';
    public $showModal = false;

    public $filtro_codigo_puro = null;
    public $filtro_presentacion = [];
    public $filtro_vitola = null;
    public $filtro_alias_vitola = null;
    public $filtro_capa = null;
    public $filtro_marca = null;


    public $importing = false;
    public $importStatus = null;
    public $importedCount = 0;
    public $errors = [];


    protected $listeners = [
        'confirmarEliminacionPuro' => 'confirmarEliminacionPuro',
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

        $validator = Validator::make(
            [
                'presentacion_puro' => $this->presentacion_puro,
                'marca' => $this->marca,
                'alias_vitola' => $this->alias_vitola,
                'vitola' => $this->vitola,
                'capa' => $this->capa,
                'codigo_puro' => $this->codigo_puro,
            ],
            [
                'presentacion_puro' => 'required|string|max:255',
                'marca' => 'required|string|max:255',
                'alias_vitola' => 'required|string|max:255',
                'vitola' => 'required|string|max:255',
                'capa' => 'required|string|max:255',
                'codigo_puro' => 'required|string|max:255|unique:info_puro,codigo_puro',
            ],
            [
                'presentacion_puro.required' => 'La presentación del puro es obligatoria.',
                'presentacion_puro.string' => 'La presentación del puro debe ser una cadena de texto.',
                'presentacion_puro.max' => 'La presentación del puro no puede tener más de 255 caracteres.',

                'marca.required' => 'La marca es obligatoria.',
                'marca.string' => 'La marca debe ser una cadena de texto.',
                'marca.max' => 'La marca no puede tener más de 255 caracteres.',

                'alias_vitola.required' => 'El alias de la vitola es obligatorio.',
                'alias_vitola.string' => 'El alias de la vitola debe ser una cadena de texto.',
                'alias_vitola.max' => 'El alias de la vitola no puede tener más de 255 caracteres.',

                'vitola.required' => 'La vitola es obligatoria.',
                'vitola.string' => 'La vitola debe ser una cadena de texto.',
                'vitola.max' => 'La vitola no puede tener más de 255 caracteres.',

                'capa.required' => 'La capa es obligatoria.',
                'capa.string' => 'La capa debe ser una cadena de texto.',
                'capa.max' => 'La capa no puede tener más de 255 caracteres.',

                'codigo_puro.required' => 'El código del puro es obligatorio.',
                'codigo_puro.string' => 'El código del puro debe ser una cadena de texto.',
                'codigo_puro.max' => 'El código del puro no puede tener más de 255 caracteres.',
                'codigo_puro.unique' => 'El código del puro ya está registrado. Por favor, elija otro.',
            ]
        );

        if ($this->editing && $this->originalCodigo === $this->codigo_puro) {
            $validator->sometimes('codigo_puro', 'required|string|max:255', function () {
                return $this->editing && $this->originalCodigo === $this->codigo_puro;
            });
        }

        $validator->setAttributeNames([
            'codigo_puro' => 'Código de Puro'
        ]);

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => $validator->errors()->first(),
                'icon' => 'error',
            ]));
            return;
        }

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

                    $this->dispatch('swal', json_encode([
                        'title' => 'Éxito',
                        'text' => 'Puro actualizado correctamente.',
                        'icon' => 'success',
                    ]));
                } else {

                    $this->dispatch('swal', json_encode([
                        'title' => 'Error',
                        'text' => 'No se encontró el puro para actualizar.',
                        'icon' => 'error',
                    ]));
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

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Puro registrado correctamente.',
                    'icon' => 'success',
                ]));
            }

            DB::commit();
            $this->loadSelectOptions();
            $this->loadCodigos();
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Error al procesar la operación. Por favor, inténtelo de nuevo.',
                'icon' => 'error',
            ]));
        }
    }


    public function editPuro($codigoPuro)
    {
        $this->originalCodigo = $codigoPuro;

        $result = DB::select('CALL editPuro(?)', [$codigoPuro]);

        if (count($result) > 0) {
            $puro = $result[0];

            $this->codigo_puro = $puro->codigo_puro;
            $this->presentacion_puro = $puro->presentacion_puro;
            $this->id_marca = $puro->id_marca;
            $this->id_vitola = $puro->id_vitola;
            $this->id_aliasvitola = $puro->id_aliasvitola;
            $this->id_capa = $puro->id_capa;

            $this->marca = $puro->marca ?? '';
            $this->vitola = $puro->vitola ?? '';
            $this->alias_vitola = $puro->alias_vitola ?? '';
            $this->capa = $puro->capa ?? '';

            $this->openModal();
        } else {

            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'El código de puro no existe.',
                'icon' => 'error',
            ]));
        }
    }


    public function filtrarPedidos()
    {
        $this->resetPage();
    }

    public function getDatosPuros()
    {
        $presentacionesString = !empty($this->filtro_presentacion) ? implode(',', $this->filtro_presentacion) : null;

        $results = DB::select("CALL GetPuros(?, ?, ?, ?, ?, ?)", [
            $this->filtro_codigo_puro,
            $presentacionesString,
            $this->filtro_marca,
            $this->filtro_vitola,
            $this->filtro_alias_vitola,
            $this->filtro_capa

        ]);

        $collection = collect($results)->map(function ($row) {
            return [
                'id_puro' => $row->id_puro,
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

    public function eliminarPuros($idPuro)
    {
        $validator = Validator::make(
            ['id_puro' => $idPuro],
            ['id_puro' => 'required|integer|exists:info_puro,id_puro']
        );

        if ($validator->fails()) {
            $this->dispatch('swal', json_encode([
                'title' => 'ID no válido',
                'text' => 'No se encontró el puro especificado.',
                'icon' => 'error',
            ]));
            return;
        }

        $this->dispatch('swalConfirmDelete', json_encode([
            'title' => '¿Estás seguro?',
            'text' => '¿Realmente deseas desactivar este puro?',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'confirmButtonText' => 'Sí, desactivar',
            'cancelButtonText' => 'No, cancelar',
            'idPuro' => $idPuro
        ]));
    }


    public function confirmarEliminacionPuro($idPuro)
    {
        try {
            $eliminar = InfoPuro::find($idPuro);

            if ($eliminar) {

                $eliminar->estado_puro = 0;
                $eliminar->save();

                $this->dispatch('refresh');

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha desactivado correctamente el puro.',
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
                'text' => 'Ocurrió un error al desactivar el puro.',
                'icon' => 'error',
            ]));
        }
    }


    public function reactivarPuro($codigoPuro)
    {
        try {

            $reactivar = InfoPuro::where('codigo_puro', $codigoPuro)->update(['estado_puro' => 1]);

            if ($reactivar) {

                $this->dispatch('refresh');

                $this->dispatch('swal', json_encode([
                    'title' => 'Éxito',
                    'text' => 'Se ha activado correctamente el puro.',
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
                'text' => 'Error al activar el puro.',
                'icon' => 'error',
            ]));
        }
    }


    public function importProducts()
    {
        try {
            set_time_limit(300);
            $apiUrl = env('APP_URL') . 'public/api/materia_prima/productos';
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
                        'estado_marca' => 1,
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );

                Vitola::updateOrCreate(
                    ['id_vitola' => $product['id_vitola']],
                    [
                        'id_vitola' => $product['id_vitola'],
                        'vitola' => $product['vitola'],
                        'estado_vitola' => 1,
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );

                AliasVitola::updateOrCreate(
                    ['id_aliasvitola' => $product['id_nombre']],
                    [
                        'id_aliasvitola' => $product['id_nombre'],
                        'alias_vitola' => $product['nombre'],
                        'estado_aliasVitola' => 1,
                        'updated_at' => now(),
                        'created_at' => now()
                    ]
                );

                Capa::updateOrCreate(
                    ['id_capa' => $product['id_capa']],
                    [
                        'id_capa' => $product['id_capa'],
                        'capa' => $product['capa'],
                        'estado_capa' => 1,
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

            $this->dispatch('swal', json_encode([
                'title' => 'Éxito',
                'text' => 'Datos importados exitosamente.',
                'icon' => 'success',
            ]));
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('swal', json_encode([
                'title' => 'Error',
                'text' => 'Error: ' . $e->getMessage(),
                'icon' => 'error',
            ]));
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
