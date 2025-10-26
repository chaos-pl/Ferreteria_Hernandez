<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Municipio extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $table = 'municipios';

    protected $fillable = ['nombre','codigo_postal'];

    // Relaciones útiles (opcional)
    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'municipios_id');
    }
}
