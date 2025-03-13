<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InfoPuro;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

class InfoPuros extends Component
{
    public $estadoPuro = null;
    public $perPage = 10;
    public $page = 1;
    protected $paginationTheme = 'bootstrap';
    use WithPagination;


    public $presentacion_puro, $marca, $alias_vitola, $vitola, $capa, $codigo_puro;
    public $id_marca, $id_vitola, $id_aliasvitola, $id_capa, $estado_puro = 1;

    public $presentaciones = [], $marcas = [], $alias_vitolas = [], $vitolas = [], $capas = [], $codigos_puros = [];
    public $editing = false;
    public $codigo_puro_busqueda = '';
    public $originalCodigo = '';
    public $showModal = false;

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
        $this->codigos_puros = DB::table('info_puro')
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

    public function buscarPorCodigo()
    {
        if (!empty($this->codigo_puro_busqueda)) {
            $this->resetPage();
        }
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->dispatch('open-modal');
    }

    public function closeModal()
    {
        $this->resetForm();
        $this->showModal = false;
        $this->dispatch('close-modal');
    }

    public function resetForm()
    {
        $this->reset(['codigo_puro', 'presentacion_puro', 'marca', 'alias_vitola', 'vitola', 'capa', 'editing', 'originalCodigo']);
        $this->resetErrorBag();
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
        $this->editing = true;
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

            $this->resetErrorBag();
            $this->openModal();
        }
    }


    public function getDatosPuros()
    {
        $query = "CALL GetPuros(?)";
        $results = DB::select($query, [$this->estadoPuro]);

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

    public function filtrarPuros($estado)
    {
        $this->estadoPuro = $estado;
        $this->resetPage();
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

    public function reactivarPuro($codigoPuro)
    {
        try {
            $registro = InfoPuro::where('codigo_puro', $codigoPuro)->first();
            if ($registro) {
                $registro->estado_puro = 1;
                $registro->save();
                session()->flash('success', 'Se ha reactivado correctamente el puro.');
            } else {
                session()->flash('error', 'No se encontró el registro para reactivar.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error al reactivar el puro.');
            Log::error('Error en reactivarPuro: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.info-puros', [
            'datosPaginados' => $this->getDatosPuros()
        ])->extends('layouts.app')->section('content');
    }
}
