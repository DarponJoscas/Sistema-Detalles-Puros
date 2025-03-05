<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Usuarios;
use App\Livewire\Admin\Dashboards;

use App\Livewire\Cliente;
use App\Livewire\DetallePedidos;
use App\Livewire\InfoPuros;

use App\Livewire\Roles;
use App\Models\DetallePedido;

Route::get('/login', Usuarios::class)->name('login');
Route::post('/logout', [Usuarios::class, 'logout'])->name('logout');


Route::get('/dashboard', Dashboards::class)->name('dashboard');
Route::get('/administracion', DetallePedidos::class)->name('administracion');


Route::get('/registrarcliente', Cliente::class)->name('registrarcliente');
Route::get('/registrarusuario', Usuarios::class)->name('registrarusuario');
Route::get('/registrarpuro', InfoPuros::class)->name('crearpuro');
Route::get('/createrol', Roles::class)->name('createrol');

Route::get('/actualizarcliente', Cliente::class)->name('actualizarcliente');
Route::get('/actualizarusuario', Usuarios::class)->name('actualizarusuario');

Route::get('/actualizarpuro', InfoPuros::class)->name('crearpuro');
Route::get('/updaterol', Usuarios::class)->name('updaterol');

