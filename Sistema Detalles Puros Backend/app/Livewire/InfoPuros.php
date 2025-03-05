<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\InfoPuro;

class InfoPuros extends Component
{
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

        // Validar en tiempo real el código de puro
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
        ]);

        session()->flash('message', 'Puro registrado correctamente.');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.info-puros')->extends('layouts.app')->section('content');
    }
}
