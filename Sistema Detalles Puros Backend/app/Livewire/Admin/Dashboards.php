<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Dashboards extends Component
{
    public function redirectToPage($page)
    {
        switch ($page) {
            case 'administracion':
                return redirect()->route('administracion');
            case 'produccion':
                return redirect()->route('produccion');
            case 'empaque':
                return redirect()->route('empaque');
            default:
                return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboards')->extends('layouts.app')->section('content');
    }
}
