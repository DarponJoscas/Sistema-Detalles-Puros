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

    public $filtro_descripcion = null;
    public $filtro_accion = null;
    public $filtro_usuario = null;

    public $usuarios, $acciones, $descripciones;

    public function filtrarBitacora()
    {
        $this->resetPage();
    }

    public function getDatosBitacora()
    {
        $results = DB::select("CALL GetBitacora(?, ?, ?)", [
            $this->filtro_descripcion,
            $this->filtro_accion,
            $this->filtro_usuario,
        ]);

        $collection = collect($results)->map(function ($row) {
            return [
                'id_bitacora' => $row->id_bitacora ?? '',
                'descripcion' => $row->descripcion ?? '',
                'accion' => $row->accion ?? '',
                'usuario' => $row->name_usuario ?? '',
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
        ])->extends('layouts.app')->section('content');
    }
}
