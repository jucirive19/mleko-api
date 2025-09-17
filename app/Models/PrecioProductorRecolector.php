<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrecioProductorRecolector extends Model
{
    protected $table = 'precio_productores_recolector';
    protected $primaryKey = 'id_precio_venta_productores ';
   

    protected $fillable = [
        'id_productor', 
        'id_recolector',
        'fecha_inicio',
        'fecha_fin',
        'precio_litro'
        
    ];
    
}
