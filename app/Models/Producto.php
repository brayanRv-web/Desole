<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'stock', 'estado_stock', 
        'categoria_id', 'imagen', 'status'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'stock' => 'integer'
    ];

    // Relación con categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Scope para productos disponibles
    public function scopeDisponibles($query)
    {
        return $query->where('status', 'activo')
                    ->where('stock', '>', 0)
                    ->where('estado_stock', 'disponible');
    }

    // Método para actualizar stock automáticamente con alertas
    public function actualizarStock($cantidad)
    {
        $stockAnterior = $this->stock;
        $this->stock -= $cantidad;
        
        if ($this->stock <= 0) {
            $this->stock = 0;
            $this->estado_stock = 'agotado';
            $this->status = 'agotado';
            
            // ENVIAR ALERTA DE AGOTADO
            if ($stockAnterior > 0) {
                $this->enviarAlertaStock('agotado');
            }
        } else {
            $this->estado_stock = 'disponible';
            
            // ENVIAR ALERTA DE STOCK BAJO
            if ($this->stock <= 5 && $stockAnterior > 5) {
                $this->enviarAlertaStock('bajo');
            }
        }
        
        $this->save();
    }

    // Método para reponer stock
    public function reponerStock($cantidad)
    {
        $stockAnterior = $this->stock;
        $this->stock += $cantidad;
        
        if ($this->stock > 0 && $this->estado_stock == 'agotado') {
            $this->estado_stock = 'disponible';
            $this->status = 'activo';
        }
        
        $this->save();
    }

    // Verificar si está disponible
    public function estaDisponible()
    {
        return $this->status == 'activo' && 
               $this->estado_stock == 'disponible' && 
               $this->stock > 0;
    }

    // MÉTODO PARA ENVIAR ALERTAS DE STOCK
    public function enviarAlertaStock($tipoAlerta = 'bajo')
    {
        try {
            // Obtener el primer usuario admin (ajusta según tu sistema)
            $admin = \App\Models\User::where('role', 'admin')->first();
            
            if ($admin) {
                $admin->notify(new \App\Notifications\StockBajoNotification($this, $tipoAlerta));
            }

        } catch (\Exception $e) {
            // Si falla, solo registrar error, no detener la aplicación
            Log::error('Error enviando alerta de stock: ' . $e->getMessage());
        }
    }

    // MÉTODO PARA VERIFICAR STOCK BAJO (se ejecuta manualmente)
    public static function verificarStockBajo()
    {
        // Productos con stock bajo
        $productosStockBajo = self::where('status', 'activo')
            ->where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->get();

        foreach ($productosStockBajo as $producto) {
            $producto->enviarAlertaStock('bajo');
        }

        // Productos agotados
        $productosAgotados = self::where('status', 'agotado')
            ->where('stock', 0)
            ->get();

        foreach ($productosAgotados as $producto) {
            $producto->enviarAlertaStock('agotado');
        }
    }
}