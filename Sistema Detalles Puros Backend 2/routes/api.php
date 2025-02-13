<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rutas para gestión de usuarios
Route::post('/login', [AuthController::class, 'login']);
Route::post('/createUsuario', [AuthController::class, 'createUsuario']);
Route::put('/updateUsuario/{usuario}', [AuthController::class, 'updateUsuario']);
Route::delete('/deleteUsuario/{usuario}', [AuthController::class, 'deleteUsuario']);


