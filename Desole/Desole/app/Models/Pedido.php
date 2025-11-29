<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use Illuminate\Support\Facades\Schema;

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
        'metodo_pago',
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
        'status' => 'string'
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
     * Get the actual column name for order status (status or estado)
     */
    private static function getStatusColumn()
    {
        static $statusColumn = null;
        if ($statusColumn === null) {
            $statusColumn = Schema::hasColumn('pedidos', 'status') ? 'status' : 'estado';
        }
        return $statusColumn;
    }

    /**
     * Accesor para obtener el estado/status independientemente de la columna real en la BD
     */
    public function getStatusAttribute($value)
    {
        // Try 'status' first, then 'estado'
        if (isset($this->attributes['status'])) {
            return $this->attributes['status'];
        }
        if (isset($this->attributes['estado'])) {
            return $this->attributes['estado'];
        }
        return null;
    }

    /**
     * Accesor para obtener 'estado' si alguien lo consulta; hace fallback a 'status'
     */
    public function getEstadoAttribute($value)
    {
        // Try 'estado' first, then 'status'
        if (isset($this->attributes['estado'])) {
            return $this->attributes['estado'];
        }
        if (isset($this->attributes['status'])) {
            return $this->attributes['status'];
        }
        return null;
    }

    /**
     * Mutator para establecer el estado en la columna que exista en la BD
     */
    public function setStatusAttribute($value)
    {
        // Set in the actual column that exists in the DB
        $col = self::getStatusColumn();
        $this->attributes[$col] = $value;
    }

    /**
     * Mutator para estado (para que asignaciones a 'estado' también funcionen)
     */
    public function setEstadoAttribute($value)
    {
        // Set in the actual column that exists in the DB
        $col = self::getStatusColumn();
        $this->attributes[$col] = $value;
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