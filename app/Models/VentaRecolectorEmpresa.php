<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaRecolectorEmpresa extends Model
{
    protected $table = 'venta_recolector_empresa';

    protected $fillable = [
        'venta_id',
        'recolector_id',
        'empresa_id',
        'cantidad',
        'precio_unitario',
        'total'
    ];
}
