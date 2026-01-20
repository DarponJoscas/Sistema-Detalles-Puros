<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProduccionController;

Route::get('/produccion', [ProduccionController::class, 'index']);
Route::post('/produccion/update', [ProduccionController::class, 'update']);
Route::get('/image-proxy', function (Request $request) {
    $path = $request->query('path');
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        return response()->json(['error' => 'Image not found'], 404);
    }
    return response()->file($fullPath);
});
