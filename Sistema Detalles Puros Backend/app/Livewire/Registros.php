<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;


class Registros extends Component
{
    public $filtro_cliente = '';
    public $filtro_rol = '';
    public $clientes;
    public $roles;
    public $currentUrl;

    use WithPagination;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    private function getRoles()
    {
        $results = DB::select("CALL GetRoles(?)", [
            $this->filtro_rol
        ]);

        $collection = collect($results)->map(function ($row) {
            return [
                'id_rol' => $row->id_rol ?? '',
                'rol' => $row->rol ?? ''
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

    private function getClientes()
    {
        $results = DB::select("CALL GetClientes(?)", [$this->filtro_cliente ?? '']);

        $collection = collect($results)->map(function ($row) {
            return [
                'id_cliente' => $row->id_cliente ?? '',
                'name_cliente' => $row->name_cliente ?? ''
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
        return view('livewire.registros', [
            'clientes' => $this->getClientes(),
            'roles' => $this->getRoles(),
        ])->extends('layouts.app')->section('content');
    }
}
