<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\EmpresaController;


// Ruta de prueba para verificar que la API está activa
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando']);
});

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Rutas de Usuarios
|--------------------------------------------------------------------------
*/
Route::prefix('users')->group(function () {
    Route::post('/register/recolector', [UserController::class, 'registerRecolector']);
    Route::post('/register/productor', [UserController::class, 'registerProductor']);
});

/*
|--------------------------------------------------------------------------
| Rutas de Ventas
|--------------------------------------------------------------------------
*/
Route::prefix('ventas')->group(function () {
    Route::post('/', [VentaController::class, 'registrarVenta']);
});

/*
|--------------------------------------------------------------------------
| Rutas de Informes
|--------------------------------------------------------------------------
*/
Route::prefix('informes')->group(function () {
    Route::get('/recolector', [InformeController::class, 'obtenerInformeRecolector']);
    Route::get('/recolector/detallado', [InformeController::class, 'obtenerInformeDetallado']);
    Route::get('/recolector/general', [InformeController::class, 'obtenerInformeGeneralRecolector']);
});

/*
|--------------------------------------------------------------------------
| Rutas de empresas
|--------------------------------------------------------------------------
*/
Route::prefix('empresa')->group(function () {
    Route::post('/register', [EmpresaController::class, 'register']);
    Route::post('/login', [EmpresaController::class, 'login']);
});