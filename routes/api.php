<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('api')->get('/test', function () {
    return response()->json(['message' => 'API funcionando']);
});

Route::post('/login', [AuthController::class, 'login']);