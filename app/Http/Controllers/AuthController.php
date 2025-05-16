<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    public function login(Request $request)
{
    $startTime = microtime(true);

    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        $endTime = microtime(true);
        Log::info('Tiempo de ejecución login (error): ' . ($endTime - $startTime) . ' segundos');
        return response()->json(['error' => 'Credenciales no válidas'], 401);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    $endTime = microtime(true);
    Log::info('Tiempo de ejecución login (exitoso): ' . ($endTime - $startTime) . ' segundos');

    return response()->json([
        'message' => 'Inicio de sesión exitoso',
        'token' => $token,
    ]);
}

public function register(Request $request)
{
    $startTime = microtime(true);

    // Validar los datos del registro
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed'
    ]);

    // Crear el nuevo usuario
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'remember_token' => \Illuminate\Support\Str::random(10),
        'email_verified_at' => now()
    ]);

    // Generar token de autenticación
    $token = $user->createToken('auth_token')->plainTextToken;

    $endTime = microtime(true);
    Log::info('Tiempo de ejecución registro: ' . ($endTime - $startTime) . ' segundos');

    return response()->json([
        'message' => 'Usuario registrado exitosamente',
        'user' => $user,
        'token' => $token
    ], 201);
}

}
