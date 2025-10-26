<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direccion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'direcciones';
    protected $fillable = [
        'calle',
        'colonia',
        'municipios_id',
    ];
    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipios_id');
    }
}
