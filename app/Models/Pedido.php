<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'cliente_nombre',
        'cliente_telefono',
        'direccion',
        'total',
        'status',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
        'total' => 'decimal:2',
    ];
}
