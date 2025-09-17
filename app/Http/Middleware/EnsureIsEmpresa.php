<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;

class EnsureIsEmpresa
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        // El middleware 'auth' ya verificó la autenticación
        $user = $request->user();
        
        // Verificar si el usuario tiene empresa asociada
        if (!$user->empresa) { // Asumiendo que tienes una relación definida
            return response()->json([
                'message' => 'Acceso denegado. Solo para empresas registradas.'
            ], 403);
        }

        return $next($request);
    }
}
