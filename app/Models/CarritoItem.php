<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarritoItem extends Model
{
    use SoftDeletes;

    protected $table = 'carrito_items';
    protected $fillable = ['carritos_id','productos_id','cantidad','precio_unit'];

    public function carrito(){ return $this->belongsTo(Carrito::class,'carritos_id'); }
    public function producto(){ return $this->belongsTo(Producto::class,'productos_id'); }

    public function getSubtotalAttribute(){ return $this->cantidad * $this->precio_unit; }
}
