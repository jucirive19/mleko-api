<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\UsuarioRol;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function registerRecolector(Request $request)
    {
        Log::info('Iniciando registro de recolector.');
        $startTime = microtime(true);

        $request->validate([
            'name' => 'required|string|max:255',
            'cedula' => 'required|string|max:20|unique:usuarios',
            'numero' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);
        Log::info('Validación completada.');

        DB::beginTransaction();
        try {
            // Crear el usuario en la tabla 'users'
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(10),
                'email_verified_at' => now()
            ]);
            Log::info('Usuario creado.');
            // Crear el registro en la tabla 'usuarios' y obtener su ID
            $usuarioId = DB::table('usuarios')->insertGetId([
                'nombre' => $request->name,
                'cedula' => $request->cedula,
                'numero' => $request->numero
            ]);
            Log::info('Recolector registrado con ID: ' . $usuarioId);
            // Asignar el rol de recolector en la tabla 'usuario_rol'
            UsuarioRol::create([
                'id_usuario' => $usuarioId,
                'id_roles' => 2
            ]);
            Log::info('Rol de recolector asignado a ID: ' . $usuarioId);
            DB::commit();
            Log::info('Registro de recolector finalizado correctamente.');
            $endTime = microtime(true);
            Log::info('Tiempo de ejecución registro recolector: ' . ($endTime - $startTime) . ' segundos');

            return response()->json([
                'message' => 'Recolector registrado exitosamente',
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar recolector: ' . $e->getMessage());
            return response()->json(['message' => 'Error al registrar recolector'], 500);
        }
    }

    public function registerProductor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cedula' => 'required|string|max:20',
            'numero' => 'required|string|max:20',
            'precio_litro' => 'required|numeric|min:0',
            'id_recolector' => 'required|exists:usuarios,id_usuario'
        ]);
    
        try {
            DB::beginTransaction();
    
            // Verificar si el productor ya existe
            $productor = DB::table('usuarios')->where('cedula', $request->cedula)->first();
    
            if (!$productor) {
                // Si no existe, crearlo
                $productorId = DB::table('usuarios')->insertGetId([
                    'nombre' => $request->name,
                    'cedula' => $request->cedula,
                    'numero' => $request->numero
                ]);
    
                // Asignar rol productor
                UsuarioRol::create([
                    'id_usuario' => $productorId,
                    'id_roles' => 3
                ]);
            } else {
                $productorId = $productor->id_usuario;
            }
    
            // Crear la relación en `precio_productores_recolector`
            DB::table('precio_productores_recolector')->insert([
                'id_productor' => $productorId,
                'id_recolector' => $request->id_recolector,
                'precio_litro' => $request->precio_litro,
                'fecha_inicio' => now(),
                'fecha_fin' => null
            ]);
    
            DB::commit();
    
            return response()->json([
                'message' => 'Productor registrado exitosamente',
                'productor_id' => $productorId
            ], 201);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error registrando productor: ' . $e->getMessage());
            return response()->json(['error' => 'Error al registrar el productor'], 500);
        }
    }
    
    

}
