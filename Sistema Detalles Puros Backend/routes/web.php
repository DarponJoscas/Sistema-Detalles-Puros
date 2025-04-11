<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Usuarios;
use App\Livewire\Admin\Dashboards;

use App\Livewire\Registros;
use App\Livewire\DetallePedidos;
use App\Livewire\InfoPuros;
use App\Livewire\RegistroUsuarios;
use App\Livewire\InfoEmpaques;
use App\Livewire\Bitacoras;
use App\Livewire\Empaque;
use App\Livewire\Produccion;
use App\Livewire\HistorialImagenes;

Route::get('/', Usuarios::class)->name('login');
Route::post('/logout', [Usuarios::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/infoempaque', InfoEmpaques::class)->name('infoempaque');
    Route::get('/dashboard', Dashboards::class)->name('dashboard');
    Route::get('/produccion', Produccion::class)->name('produccion');
    Route::get('/empaque', Empaque::class)->name('empaque');
    Route::get('/administracion', DetallePedidos::class)->name('administracion');
    Route::get('/historialimagenes', HistorialImagenes::class)->name('historialimagenes');
    Route::get('/bitacora', Bitacoras::class)->name('bitacora');
    Route::get('/registros', Registros::class)->name('registros');
    Route::get('/usuarios', RegistroUsuarios::class)->name('usuarios');
    Route::get('/puros', InfoPuros::class)->name('puros');
});
