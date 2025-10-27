<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsignaProductoProveedor extends Model
{
    use SoftDeletes;

    protected $table = 'asigna_productos_proveedores';

    protected $fillable = [
        'productos_id',
        'proveedores_id',
        'sku_proveedor',
        'plazo_entrega',
    ];

    protected $casts = [
        'plazo_entrega' => 'integer',
    ];

    // Relaciones
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'productos_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedores_id');
    }
}
