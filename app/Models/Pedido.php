<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'cliente_id',
        'cliente_nombre',
        'cliente_telefono',
        'direccion',
        'total',
        'estado',
        'items',
        'notas',
        'tiempo_estimado',
        'stock_descontado'
    ];

    protected $casts = [
        'items' => 'array',
        'total' => 'decimal:2',
        'stock_descontado' => 'boolean',
        'estado' => 'string'
    ];

    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_EN_PROCESO = 'en_proceso';
    const ESTADO_COMPLETADO = 'completado';
    const ESTADO_CANCELADO = 'cancelado';

    protected $attributes = [
        'estado' => self::ESTADO_PENDIENTE
    ];

    /**
     * Decrement stock for all items in the order
     */
    public function decrementarStock()
    {
        if ($this->stock_descontado) {
            return;
        }

        DB::transaction(function () {
            $items = $this->items ?? [];

            // First validate all stock
            foreach ($items as $item) {
                if (empty($item['producto_id']) || empty($item['cantidad'])) {
                    continue;
                }

                $producto = Producto::lockForUpdate()->find($item['producto_id']);
                if (!$producto || $producto->stock < $item['cantidad']) {
                    throw new \Exception('Stock insuficiente para ' . ($producto->nombre ?? 'producto desconocido'));
                }
            }

            // Then update all stock
            foreach ($items as $item) {
                if (empty($item['producto_id']) || empty($item['cantidad'])) {
                    continue;
                }

                $producto = Producto::lockForUpdate()->find($item['producto_id']);
                if ($producto) {
                    $producto->decrement('stock', $item['cantidad']);
                    if ($producto->stock <= 0) {
                        $producto->update([
                            'stock' => 0,
                            'estado_stock' => 'agotado',
                            'estado' => 'agotado'
                        ]);
                    } else if ($producto->stock <= 5) {
                        $producto->enviarAlertaStock('bajo');
                    }
                }
            }

            // Mark stock as decremented
            $this->update([
                'stock_descontado' => true
            ]);
        });
    }

    /**
     * Relación con cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    /**
     * Increment stock for all items in the order (return stock)
     */
    public function incrementarStock()
    {
        if (!$this->stock_descontado) {
            return;
        }

        DB::transaction(function () {
            $items = $this->items ?? [];

            foreach ($items as $item) {
                if (empty($item['producto_id']) || empty($item['cantidad'])) {
                    continue;
                }

                $producto = Producto::lockForUpdate()->find($item['producto_id']);
                if ($producto) {
                    $producto->increment('stock', $item['cantidad']);
                    if ($producto->stock > 0) {
                        $producto->update([
                            'estado_stock' => 'disponible',
                            'estado' => 'activo'
                        ]);
                    }
                }
            }

            // Mark stock as not decremented
            $this->update([
                'stock_descontado' => false
            ]);
        });
    }
}