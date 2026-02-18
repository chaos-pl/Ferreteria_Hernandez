<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Compra extends Model
{
    use SoftDeletes;

    protected $table = 'compras';

    protected $fillable = [
        'proveedores_id',
        'users_id',
        'fecha_compra',
        'estado',
        'total',
    ];

    protected $casts = [
        'fecha_compra' => 'datetime',
        'total' => 'decimal:2',
    ];

    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(\App\Models\Proveedor::class, 'proveedores_id');
    }

    public function usuario()
    {
        return $this->belongsTo(\App\Models\User::class, 'users_id');
    }

    public function detalles()
    {
        return $this->hasMany(\App\Models\CompraDetalle::class, 'compras_id');
    }


    // Conviene recalcular total al tocar detalles
    public function recalcularTotal(): void
    {
        $this->loadMissing('detalles');
        $this->total = $this->detalles->sum('subtotal');
        $this->save();
    }
}
