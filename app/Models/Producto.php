<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productos';

    protected $fillable = [
        'nombre','descripcion','categorias_id','unidades_id','marcas_id',
        'precio','imagen_url','estado','existencias'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'existencias' => 'integer',
    ];

    // Relaciones
    public function categoria() { return $this->belongsTo(Categoria::class, 'categorias_id'); }
    public function unidad()    { return $this->belongsTo(Unidad::class,    'unidades_id'); }
    public function marca()     { return $this->belongsTo(Marca::class,     'marcas_id'); }

    // Scopes útiles
    public function scopeActivos($q) { return $q->where('estado','activo'); }
    public function asignaciones()
    {
        return $this->hasMany(AsignaProductoProveedor::class, 'productos_id');
    }

    public function proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'asigna_productos_proveedores', 'productos_id', 'proveedores_id')
            ->withPivot(['id','sku_proveedor','plazo_entrega'])
            ->using(\App\Models\AsignaProductoProveedor::class);
    }
    public function promociones()
    {
        return $this->belongsToMany(Promocion::class, 'asigna_promocion', 'id_producto', 'id_promocion');
    }

    public function promocionActiva()
    {
        return $this->promociones()
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->orderByDesc('descuento')
            ->first();
    }

}
