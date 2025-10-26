<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'proveedores';

    protected $fillable = ['personas_id','estado'];

    protected $casts = [
        'estado' => 'string', // 'activo' | 'inactivo'
    ];

    // Relaciones
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'personas_id');
    }
}
