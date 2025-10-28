<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    use SoftDeletes;

    protected $table = 'ventas';

    protected $fillable = [
        'users_id','fecha_venta','estado',
        'subtotal','descuentos','impuestos','total',
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
        'subtotal'    => 'decimal:2',
        'descuentos'  => 'decimal:2',
        'impuestos'   => 'decimal:2',
        'total'       => 'decimal:2',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'ventas_id');
    }

    // Recalcular totales con base en detalles
    public function recalcTotales(): void
    {
        $this->subtotal   = $this->detalles()->sum('subtotal');
        $this->total      = max(0, ($this->subtotal - $this->descuentos) + $this->impuestos);
        $this->save();
    }
}
