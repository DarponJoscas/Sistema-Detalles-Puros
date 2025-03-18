<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Usuarios;
use App\Livewire\Admin\Dashboards;

use App\Livewire\Cliente;
use App\Livewire\DetallePedidos;
use App\Livewire\InfoPuros;
use App\Livewire\RegistroUsuarios;
use App\Livewire\Roles;



Route::get('/login', Usuarios::class)->name('login');
Route::post('/logout', [Usuarios::class, 'logout'])->name('logout');

Route::get('/dashboard', Dashboards::class)->name('dashboard');
Route::get('/produccion', DetallePedidos::class)->name('produccion');
Route::get('/empaque', DetallePedidos::class)->name('empaque');
Route::get('/administracion', DetallePedidos::class)->name('administracion');

Route::get('/usuarios', RegistroUsuarios::class)->name('usuarios');
Route::get('/registrarusuario', Usuarios::class)->name('registrarusuario');
Route::get('/puros', InfoPuros::class)->name('puros');
