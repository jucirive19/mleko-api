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
    $user = Auth::user();

    // Si no hay usuario autenticado, devolver error
    if (!$user) {
        return response()->json(['error' => 'No autenticado.'], 401);
    }

    // Verificar si el usuario tiene un registro asociado en la tabla 'empresa'
    $empresa = Empresa::where('user_id', $user->id)->first();

    if (!$empresa) {
        return response()->json(['error' => 'Acceso no autorizado. No es una empresa registrada.'], 403);
    }

    return $next($request);
}

}
