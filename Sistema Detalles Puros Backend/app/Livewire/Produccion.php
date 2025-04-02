<?php

namespace App\Livewire;

use Livewire\Component;

class Produccion extends Component
{
    public function render()
    {
        return view('livewire.produccion')->extends('layouts.app')->section('content');;
    }
}
