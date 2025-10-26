<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //
    use HasFactory;
    protected $table = 'productos';


    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'existencias',
        'estado', // 'activo' | 'inactivo'
    ];
    protected $casts = [
        'precio' => 'decimal:2',
        'existencias' => 'integer',
    ];


    protected $attributes = [
        'estado' => 'activo',
        'existencias' => 0,
    ];
}
