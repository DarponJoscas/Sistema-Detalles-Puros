<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class HistorialImagenes extends Component
{
    use WithPagination;
    public $currentUrl;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';
    public $puros;
    public $presentaciones;
    public $marcas;
    public $alias_vitolas;
    public $vitolas;
    public $capas;
    public $codigo_puro, $marca, $vitola, $alias_vitola, $capa, $presentacion, $puro;
    public $filtro_codigo_puro = null;
    public $filtro_presentacion = [];
    public $filtro_vitola = null;
    public $filtro_alias_vitola = null;
    public $filtro_capa = null;
    public $filtro_marca = null;

    public function filtrarPedidos()
    {
        $this->resetPage();
    }

    public function getHistorialImagenes()
    {
        $presentacionesString = !empty($this->filtro_presentacion) ? implode(',', $this->filtro_presentacion) : null;

        $results = DB::select("CALL GetHistorialImagenes(?, ?, ?, ?, ?, ?)", [
            $this->filtro_codigo_puro,
            $this->filtro_marca,
            $this->filtro_vitola,
            $this->filtro_alias_vitola,
            $this->filtro_capa,
            $presentacionesString
        ]);


        $collection = collect($results)->map(function ($row) {
            return [
                'id_historial' => $row->id_historial ?? '',
                'id_pedido' => $row->id_pedido ?? '',
                'codigo_puro' => $row->codigo_puro ?? '',
                'presentacion_puro' => $row->presentacion_puro ?? '',
                'marca' => $row->marca ?? '',
                'vitola' => $row->vitola ?? '',
                'alias_vitola' => $row->alias_vitola ?? '',
                'capa' => $row->capa ?? '',
                'imagen_produccion' => $row->imagen_produccion ? json_decode($row->imagen_produccion) : [],
                'imagen_anillado' => $row->imagen_anillado ? json_decode($row->imagen_anillado) : [],
                'imagen_caja' => $row->imagen_caja ? json_decode($row->imagen_caja) : [],
                'fecha_cambio' => $row->fecha_cambio ?? '',
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

    public function render()
    {
        return view('livewire.historial-imagenes', [
            'historialImagenes' => $this->getHistorialImagenes(),
            $this->vitolas = DB::table('vitola')->get(['vitola']),
            $this->marcas = DB::table('marca')->get(['marca']),
            $this->alias_vitolas = DB::table('alias_vitola')->get(['alias_vitola']),
            $this->capas = DB::table('capa')->get(['capa']),
            $this->puros = DB::table('info_puro')->get(['codigo_puro']),
            $this->presentaciones = DB::table('info_puro')->distinct()->get(['presentacion_puro']),
        ])->extends('layouts.app')->section('content');;
    }
}
