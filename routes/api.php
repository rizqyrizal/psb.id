<?php

use App\Http\Controllers\Api\SantriController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::apiResource('santris', SantriController::class);

Route::post('/login', [AuthController::class, 'login']);

// Route untuk data santri yang dilindungi
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/santris', [SantriController::class, 'index']);
    // ... tambahkan route API lain di sini
});