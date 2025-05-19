<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaProductorRecolector extends Model
{
    protected $table = 'venta_productores_recolector';
    protected $primaryKey = 'id_venta_productores';

    protected $fillable = [
        'id_productor', 
        'id_recolector',
        'fecha',
        'cantidad_litros',
        'precio_litro',
        'total_venta' 
    ];
}
