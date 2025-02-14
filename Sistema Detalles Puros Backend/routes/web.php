<?php

use App\Http\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/welcome', function () {
    return view('welcome');
})->middleware('auth')->name('welcome');

// Ruta para cerrar sesión
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');
