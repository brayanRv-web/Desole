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
        'estado',
        'notas',
        'tiempo_estimado',
        'stock_descontado',
        'metodo_pago'
    ];

    protected $casts = [
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
     * Relación con los detalles del pedido
     */
    public function detalles()
    {
        return $this->hasMany(PedidoDetalle::class);
    }

    /**
     * Decrement stock for all items in the order
     */
    public function decrementarStock()
    {
        if ($this->stock_descontado) {
            return;
        }

        DB::transaction(function () {
            // Cargar detalles si no están cargados
            if (!$this->relationLoaded('detalles')) {
                $this->load('detalles');
            }

            $detalles = $this->detalles;

            // First validate all stock
            foreach ($detalles as $detalle) {
                $producto = Producto::lockForUpdate()->find($detalle->producto_id);
                if (!$producto || $producto->stock < $detalle->cantidad) {
                    throw new \Exception('Stock insuficiente para ' . ($producto->nombre ?? 'producto desconocido'));
                }
            }

            // Then update all stock
            foreach ($detalles as $detalle) {
                $producto = Producto::lockForUpdate()->find($detalle->producto_id);
                if ($producto) {
                    $producto->decrement('stock', $detalle->cantidad);
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
     * Accesor para determinar si el pedido está pagado.
     * Como no hay columna 'pagado', asumimos que está pagado si el estado es 'entregado' o 'completado'.
     */
    public function getPagadoAttribute()
    {
        $estado = $this->estado ?? $this->status;
        return in_array($estado, ['entregado', 'completado']);
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
            // Cargar detalles si no están cargados
            if (!$this->relationLoaded('detalles')) {
                $this->load('detalles');
            }

            $detalles = $this->detalles;

            foreach ($detalles as $detalle) {
                $producto = Producto::lockForUpdate()->find($detalle->producto_id);
                if ($producto) {
                    $producto->increment('stock', $detalle->cantidad);
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