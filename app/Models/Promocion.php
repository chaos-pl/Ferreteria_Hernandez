<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promocion extends Model
{
    use SoftDeletes;

    protected $table = 'promociones';

    protected $fillable = [
        'nombre',
        'descuento',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin'    => 'datetime',
    ];

    public function productos()
    {
        return $this->belongsToMany(
            \App\Models\Producto::class,
            'asigna_promocion',
            'id_promocion',
            'id_producto'
        );
    }
}
