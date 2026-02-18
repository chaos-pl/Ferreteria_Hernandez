<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrito extends Model
{
    use SoftDeletes;

    protected $table = 'carritos';
    protected $fillable = ['users_id','estado'];

    public function user(){ return $this->belongsTo(User::class,'users_id'); }
    public function items(){ return $this->hasMany(CarritoItem::class,'carritos_id'); }

    public function scopeAbierto($q){ return $q->where('estado','abierto'); }

    // helpers
    public function getCantidadTotalAttribute(){ return $this->items->sum('cantidad'); }
    public function getSubtotalAttribute(){ return $this->items->sum(fn($i)=>$i->cantidad*$i->precio_unit); }
}
