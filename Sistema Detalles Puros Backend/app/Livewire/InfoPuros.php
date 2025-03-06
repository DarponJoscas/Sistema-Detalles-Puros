<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InfoPuro;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;

class InfoPuros extends Component
{
    public $estadoPuro = null;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    use WithPagination;

    public $presentacion_puro, $marca_puro, $alias_vitola, $vitola, $capa_puro, $codigo_puro;
    public $presentaciones = [], $marcas = [], $alias_vitolas = [], $vitolas = [], $capas = [];

    protected $rules = [
        'presentacion_puro' => 'required|string|max:255',
        'marca_puro' => 'required|string|max:255',
        'alias_vitola' => 'required|string|max:255',
        'vitola' => 'required|string|max:255',
        'capa_puro' => 'required|string|max:255',
        'codigo_puro' => 'required|string|max:255|unique:info_puros,codigo_puro',
    ];

    protected $messages = [
        'codigo_puro.unique' => 'Este código de puro ya existe en la base de datos.',
    ];

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['presentacion_puro', 'marca_puro', 'alias_vitola', 'vitola', 'capa_puro'])) {
            $this->filterOptions();
        }

        if ($propertyName === 'codigo_puro') {
            $this->validateOnly('codigo_puro');
        }
    }

    public function filterOptions()
    {
        $this->presentaciones = InfoPuro::select('presentacion_puro')->distinct()->where('presentacion_puro', 'like', "%{$this->presentacion_puro}%")->pluck('presentacion_puro');
        $this->marcas = InfoPuro::select('marca_puro')->distinct()->where('marca_puro', 'like', "%{$this->marca_puro}%")->pluck('marca_puro');
        $this->alias_vitolas = InfoPuro::select('alias_vitola')->distinct()->where('alias_vitola', 'like', "%{$this->alias_vitola}%")->pluck('alias_vitola');
        $this->vitolas = InfoPuro::select('vitola')->distinct()->where('vitola', 'like', "%{$this->vitola}%")->pluck('vitola');
        $this->capas = InfoPuro::select('capa_puro')->distinct()->where('capa_puro', 'like', "%{$this->capa_puro}%")->pluck('capa_puro');
    }

    public function createPuro()
    {
        $this->validate();

        $existingPuro = InfoPuro::where('codigo_puro', $this->codigo_puro)->first();
        if ($existingPuro) {
            session()->flash('error', 'No se pudo registrar el puro. El código ya existe en la base de datos.');
            return;
        }

        InfoPuro::create([
            'presentacion_puro' => $this->presentacion_puro,
            'marca_puro' => $this->marca_puro,
            'alias_vitola' => $this->alias_vitola,
            'vitola' => $this->vitola,
            'capa_puro' => $this->capa_puro,
            'codigo_puro' => $this->codigo_puro,
            'estado_puro' => 1
        ]);

        session()->flash('message', 'Puro registrado correctamente.');
        $this->reset();
    }

    public function getDatosPuros()
    {
        $results = DB::select("CALL GetPuros(?)", [$this->estadoPuro]);

        $collection = collect($results)->map(function ($row) {
            return [
                'codigo_puro' => $row->codigo_puro ?? '',
                'presentacion_puro' => $row->presentacion_puro ?? '',
                'marca_puro' => $row->marca_puro ?? '',
                'vitola' => $row->vitola ?? '',
                'alias_vitola' => $row->alias_vitola ?? '',
                'capa_puro' => $row->capa_puro ?? '',
                'estado_puro' => $row->estado_puro ?? '',
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

    public function eliminarPuros($codigo_puro)
    {
        $registro = InfoPuro::find($codigo_puro);

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
        return view('livewire.info-puros', [
            'datosPaginados' => $this->getDatosPuros(),
        ])->extends('layouts.app')->section('content');
    }
}
