<?php

namespace App\Livewire;

use Livewire\Component;

class Empaque extends Component
{
    public function render()
    {
        return view('livewire.empaque')->extends('layouts.app')->section('content');;
    }
}
