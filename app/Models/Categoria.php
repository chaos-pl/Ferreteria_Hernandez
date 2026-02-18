<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $table = 'categorias';

    // Solo los campos que existen en tu tabla
    protected $fillable = ['nombre','descripcion'];

    protected static function booted(): void
    {
        foreach (['saved','deleted','restored'] as $event) {
            static::$event(fn () => cache()->forget('sidebar:categorias'));
        }
        static::saving(function (Categoria $m) {
            $m->nombre = trim($m->nombre);
        });

    }
    public function productos()
    {
        return $this->hasMany(\App\Models\Producto::class, 'categorias_id');
    }

}
