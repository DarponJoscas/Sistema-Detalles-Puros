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

    public function getHistorialImagenes()
    {
        $results = DB::select("CALL GetHistorialImagenes()");

        $collection = collect($results)->map(function ($row) {
            return [
                'id_historial' => $row->id_historial ?? '',
                'id_pedido' => $row->id_pedido ?? '',
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
        ])->extends('layouts.app')->section('content');;
    }
}
