<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InformeController extends Controller
{
     public function obtenerInformeRecolector(Request $request)
    {
        log::info('Iniciando obtención de informe del recolector.');
        // Validar los parámetros de entrada
        $request->validate([
            'id_recolector' => 'required|exists:usuarios,id_usuario',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        try {
            $idRecolector = $request->id_recolector;
            $fechaInicio = $request->fecha_inicio;
            $fechaFin = $request->fecha_fin;

            Log::info("Obteniendo informe para el recolector con ID: {$idRecolector}, desde {$fechaInicio} hasta {$fechaFin}");

            $ventas = DB::table('venta_productores_recolector')
                ->select('id_productor', DB::raw('SUM(cantidad_litros) as total_litros'), DB::raw('SUM(total_venta) as total_venta'))
                ->where('id_recolector', $idRecolector)
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->groupBy('id_productor')
                ->get();

            return response()->json([
                'message' => 'Informe generado correctamente',
                'data' => $ventas
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener informe del recolector: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener el informe'], 500);
        }
    }
}
