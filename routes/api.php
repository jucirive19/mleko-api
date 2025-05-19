<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentaController;

Route::middleware('api')->group(function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'API funcionando']);
    });

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/register/recolector', [UserController::class, 'registerRecolector']);
    Route::post('/register/productor', [UserController::class, 'registerProductor']);
    Route::post('/venta', [VentaController::class, 'registrarVenta']);
    Route::get('/informes/recolector', [InformeController::class, 'obtenerInformeRecolector']);
    Route::get('/informe/detallado', [InformeController::class, 'obtenerInformeDetallado']);
});
