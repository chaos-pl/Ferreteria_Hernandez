<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Persona extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'personas';

    protected $fillable = [
        'nombre','ap','am','telefono','email','direcciones_id',
    ];

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'direcciones_id');
    }
    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'personas_id');
    }

    // Helper para mostrar el nombre completo
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->ap} {$this->am}");
    }
}
