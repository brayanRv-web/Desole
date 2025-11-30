<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoDetalle extends Model
{
    use HasFactory;

    protected $table = 'pedido_detalles';

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio'
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio' => 'decimal:2'
    ];

    /**
     * Relación con el pedido
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Relación con el producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
