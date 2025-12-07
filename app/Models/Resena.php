<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'email',
        'calificacion',
        'comentario',
        'cliente_id',
        'tipo_cliente',
    ];

    // Relación con Cliente (usa la tabla/model Cliente)
    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id');
    }
}
