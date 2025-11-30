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
        'status',
        'tipo' // 'hero' o 'carrusel'
    ];

    protected $casts = [
        'status' => 'boolean',
        'orden' => 'integer'
    ];
}