<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompraDetalle extends Model
{
    use SoftDeletes;

    protected $table = 'compra_detalles';

    protected $fillable = [
        'compras_id',
        'asigna_productos_proveedores_id',
        'cantidad',
        'costo_unit',
        'subtotal',
    ];

    protected $casts = [
        'cantidad'  => 'integer',
        'costo_unit'=> 'decimal:2',
        'subtotal'  => 'decimal:2',
    ];

    public function compra()
    {
        return $this->belongsTo(\App\Models\Compra::class, 'compras_id');
    }

    public function asignacion()
    {
        // Modelo que ya usas para la tabla asigna_productos_proveedores
        return $this->belongsTo(\App\Models\AsignaProductoProveedor::class, 'asigna_productos_proveedores_id');
    }
}
