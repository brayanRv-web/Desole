<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'imagen',
        'titulo',
        'subtitulo', 
        'orden',
        'estado',
        'tipo' // 'hero' o 'carrusel'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer'
    ];
}