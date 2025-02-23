<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Usuarios;
use App\Livewire\Admin\Dashboard;

// Ruta para el login
Route::get('/login', Usuarios::class)->name('login');

Route::get('/dashboard', Dashboard::class)->name('dashboard');