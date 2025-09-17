<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
 
   


class VentaController extends Controller
{
    public function registrarVenta(Request $request) {
    Log::info('Iniciando registro de venta.');
    $request->validate([
        'id_productor' => 'required|exists:usuarios,id_usuario',
        'id_recolector' => 'required|exists:usuarios,id_usuario',
        'cantidad_litros' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
        // Obtener el precio del litro más reciente del productor para el recolector
        $precio = DB::table('precio_productores_recolector')
            ->where('id_productor', $request->id_productor)
            ->where('id_recolector', $request->id_recolector)
            ->orderByDesc('fecha_inicio')
            ->value('precio_litro');

        if (is_null($precio)) {
            return response()->json(['error' => 'No se encontró un precio definido para esta relación.'], 404);
        }

        // Calcular el valor total de la venta
        $valorTotal = $request->cantidad_litros * $precio;

        // Registrar la venta en la tabla
        DB::table('venta_productores_recolector')->insert([
            'id_productor' => $request->id_productor,
            'id_recolector' => $request->id_recolector,
            'fecha' => now(),
            'cantidad_litros' => $request->cantidad_litros,
            'precio_litro' => $precio,
            'total_venta' => $valorTotal,
        ]);

        DB::commit();
        Log::info('Venta registrada exitosamente.');

        return response()->json([
            'message' => 'Venta registrada exitosamente',
            'total_venta' => $valorTotal
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al registrar la venta: ' . $e->getMessage());
        return response()->json(['error' => 'Error al registrar la venta'], 500);
    }
    }

    public function ventasrecolector(Request $request){
        $request->validate([
            'id_empresa' => 'required|exists:empresas,id_empresa',
            'id_recolector' => 'required|exists:usuarios,id_usuario',
            'cantidad_litros' => 'required|numeric|min:0',
            'precio_litro' => 'required|numeric|min:0',
        ]);
        DB::beginTransaction();
        try {
            DB::table('venta_recolector_empresas')->insert([
                'id_empresa' => $request->id_empresa,
                'id_recolector' => $request->id_recolector,
                'fecha' => now(),
                'cantidad_litros' => $request->cantidad_litros,
                'precio_litro' => $request->precio_litro,
                'venta_total' => $request->cantidad_litros * $request->precio_litro,
            ]);

            DB::commit();
            Log::info('Venta registrada exitosamente.');

            return response()->json([
                'message' => 'Venta registrada exitosamente',
                'total_venta' => $request->cantidad_litros * $request->precio_litro
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al registrar la venta: ' . $e->getMessage());
            return response()->json(['error' => 'Error al registrar la venta'], 500);
        }

    }
}
