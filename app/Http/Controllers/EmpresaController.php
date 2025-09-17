<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Support\Str;

class EmpresaController extends Controller
{
    /**
     * Registrar una empresa.
     */
    public function register(Request $request)
    {
       
        $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'nit' => 'required|string|unique:empresas,nit',
            'matricula_mercantil' => 'required|string',
            'nombre_usuario' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            
        ]);

        // Crear usuario
        $user = User::create([
            'name' => $request->nombre_usuario,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(10),
            'email_verified_at' => now()
        ]);

        // Crear empresa
        Empresa::create([
            'nombre' => $request->nombre_empresa,
            'nit' => $request->nit,
            'matricula_mercantil' => $request->matricula_mercantil,
            'user_id' => $user->id,
        ]);

        return response()->json(['message' => 'Empresa registrada exitosamente'], 201);
    }

    /**
     * Login para empresas solamente.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $empresa = Empresa::where('user_id', $user->id)->first();
        if (!$empresa) {
            return response()->json(['message' => 'Este usuario no está registrado como empresa'], 403);
        }

        $token = $user->createToken('empresa-token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'token' => $token,
            'empresa' => $empresa,
        ]);
    }
}

