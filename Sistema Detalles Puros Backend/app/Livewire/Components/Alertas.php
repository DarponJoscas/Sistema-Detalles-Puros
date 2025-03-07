<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Alertas extends Component
{
    public function render()
    {
        return view('livewire.components.alertas')->extends('layouts.app')->section('content');
    }
}
