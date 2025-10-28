<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class VentaDetalle extends Model
{
    use SoftDeletes;

    protected $table = 'venta_detalles';

    protected $fillable = [
        'ventas_id',
        'asigna_productos_proveedores_id',
        'cantidad',
        'precio_unit',
        'subtotal',
    ];

    protected $casts = [
        'cantidad'    => 'integer',
        'precio_unit' => 'decimal:2',
        'subtotal'    => 'decimal:2',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'ventas_id');
    }

    public function asignaProductoProveedor()
    {
        return $this->belongsTo(AsignaProductoProveedor::class, 'asigna_productos_proveedores_id');
    }
}
