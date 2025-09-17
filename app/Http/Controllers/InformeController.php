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

            $ventas = DB::table('venta_productores_recolector as v')
            ->select('v.id_productor','p.nombre as productor_nombre',
                DB::raw('SUM(v.cantidad_litros) as total_litros'),
                DB::raw('SUM(v.total_venta) as total_venta')
            )
            ->join('usuarios as p', 'p.id_usuario', '=', 'v.id_productor')
            ->where('v.id_recolector', $idRecolector)
            ->whereBetween('v.fecha', [$fechaInicio, $fechaFin])
            ->groupBy('v.id_productor', 'p.nombre')
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

    public function obtenerInformeDetallado(Request $request)
    {
    Log::info('Iniciando obtención del informe detallado.');

    $request->validate([
        'id_recolector' => 'required|exists:usuarios,id_usuario',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
    ]);

    try {
        $idRecolector = $request->id_recolector;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        Log::info("Recolector ID: $idRecolector, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");

         $ventas = DB::table('venta_productores_recolector as v')
            ->select(
                'v.id_productor',
                'p.nombre as productor_nombre',
                'v.fecha',
                'v.cantidad_litros',
                'v.precio_litro',
                'v.total_venta',
                DB::raw('(SELECT precio_litro FROM precio_productores_recolector WHERE id_productor = v.id_productor AND fecha_fin IS NULL ORDER BY fecha_inicio DESC LIMIT 1) as precio_actual')
            )
            ->join('usuarios as p', 'p.id_usuario', '=', 'v.id_productor')
            ->where('v.id_recolector', $idRecolector)
            ->whereBetween('v.fecha', [$fechaInicio, $fechaFin])
            ->orderBy('v.id_productor')
            ->orderBy('v.fecha')
            ->get();

        Log::info('Informe detallado obtenido con éxito.');

        return response()->json([
            'message' => 'Informe detallado obtenido con éxito',
            'data' => $ventas
        ], 200);

    } catch (\Exception $e) {
        Log::error('Error al obtener el informe detallado: ' . $e->getMessage());
        return response()->json(['error' => 'Error al obtener el informe detallado'], 500);
    }
    }    
    
    public function obtenerInformeGeneralRecolector(Request $request)
    {
    Log::info('Iniciando obtención del informe general del recolector.');

    $request->validate([
        'id_recolector' => 'required|exists:usuarios,id_usuario',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
    ]);

    try {
        $idRecolector = $request->id_recolector;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;

        Log::info("Recolector ID: $idRecolector, Fecha Inicio: $fechaInicio, Fecha Fin: $fechaFin");

        $informe = DB::table('venta_productores_recolector as v')
            ->select(
                DB::raw('SUM(v.cantidad_litros) as total_litros'),
                DB::raw('SUM(v.total_venta) as total_pagado'),
                DB::raw('AVG(v.precio_litro) as precio_promedio'),
                DB::raw('COUNT(DISTINCT v.id_productor) as productores_activos')
            )
            ->where('v.id_recolector', $idRecolector)
            ->whereBetween('v.fecha', [$fechaInicio, $fechaFin])
            ->first();

        return response()->json([
            'message' => 'Informe general del recolector generado con éxito',
            'data' => $informe
        ], 200);

    } catch (\Exception $e) {
        Log::error('Error al obtener el informe general del recolector: ' . $e->getMessage());
        return response()->json(['error' => 'Error al obtener el informe general del recolector'], 500);
    }
    }
    
}
