<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class Dashboards extends Component
{
    protected $listeners = ['checkAuthStatus' => 'verifyAuthentication'];

    public function verifyAuthentication()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function redirectToPage($page)
    {
        $user = Auth::user();

        switch ($page) {
            case 'administracion':
                return redirect()->route('administracion')->with('user', $user);
            case 'produccion':
                return redirect()->route('produccion')->with('user', $user);
            case 'empaque':
                return redirect()->route('empaque')->with('user', $user);
            default:
                return redirect()->route('dashboard')->with('user', $user);
        }
    }


    public function render()
    {
        return view('livewire.admin.dashboards')->extends('layouts.app')->section('content');
    }
}
