<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Bitacoras extends Component
{

    public $perPage = 25;
    public $page = 1;
    protected $paginationTheme = 'bootstrap';
    use WithPagination;
    public $puros;
    public $presentaciones;
    public $marcas;
    public $alias_vitolas;
    public $vitolas;
    public $capas;
    public $filtro_accion = null;
    public $filtro_usuario = null;
    public $filtro_codigo_puro = null;
    public $filtro_presentacion = [];
    public $filtro_vitola = null;
    public $filtro_alias_vitola = null;
    public $filtro_capa = null;
    public $filtro_codigo_empaque = null;
    public $filtro_marca = null;
    public $usuarios, $acciones, $descripciones;

    public function filtrarBitacora()
    {
        $this->resetPage();
    }

    public function getDatosBitacora()
    {
        $presentacionesString = !empty($this->filtro_presentacion) ? implode(',', $this->filtro_presentacion) : null;

        $results = DB::select("CALL GetBitacora(?, ?, ?, ?, ?, ?, ?, ?)", [
            $this->filtro_accion,
            $this->filtro_usuario,
            $this->filtro_codigo_puro,
            $presentacionesString,
            $this->filtro_marca,
            $this->filtro_vitola,
            $this->filtro_alias_vitola,
            $this->filtro_capa,
        ]);

        $collection = collect($results)->map(function ($row) {
            return [
                'id_bitacora' => $row->id_bitacora ?? '',
                'descripcion' => $row->descripcion ?? '',
                'accion' => $row->accion ?? '',
                'usuario' => $row->name_usuario ?? '',
                'codigo_puro' => $row->codigo_puro ?? '',
                'presentacion_puro' => $row->presentacion_puro ?? '',
                'marca' => $row->marca ?? '',
                'alias_vitola' => $row->alias_vitola ?? '',
                'vitola' => $row->vitola ?? '',
                'capa' => $row->capa ?? '',
                'fecha_creacion' => $row->created_at ?? '',
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
        return view('livewire.bitacoras',[
            'datosPaginados' => $this->getDatosBitacora(),
            $this->descripciones = DB::table('bitacora')->distinct()->get(['descripcion']),
            $this->acciones = DB::table('bitacora')->distinct()->get(['accion']),
            $this->usuarios = DB::table('usuarios')->get(['id_usuario', 'name_usuario']),
            $this->vitolas = DB::table('vitola')->get(['vitola']),
            $this->marcas = DB::table('marca')->get(['marca']),
            $this->alias_vitolas = DB::table('alias_vitola')->get(['alias_vitola']),
            $this->capas = DB::table('capa')->get(['capa']),
            $this->puros = DB::table('info_puro')->get(['codigo_puro']),
            $this->presentaciones = DB::table('info_puro')->distinct()->get(['presentacion_puro']),
        ])->extends('layouts.app')->section('content');
    }
}
