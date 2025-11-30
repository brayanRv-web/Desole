<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_descuento',
        'valor_descuento',
        'fecha_inicio',
        'fecha_fin',
        'activa'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'activa' => 'boolean',
        'valor_descuento' => 'decimal:2'
    ];

    // Relación con productos
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_promocion')
                    ->withTimestamps();
    }

    public function productosActivos()
    {
        return $this->belongsToMany(Producto::class, 'producto_promocion')
                    ->where('status', 'activo')
                    ->withTimestamps();
    }

    // Scope para promociones activas (fechas y flag)
    public function scopeActiva($query)
    {
        return $query->where('activa', true)
                    ->where('fecha_inicio', '<=', now())
                    ->where('fecha_fin', '>=', now());
    }

    // Scope para promociones completamente disponibles (todos los productos activos)
    public function scopeFullyAvailable($query)
    {
        return $query->activa()
                     ->has('productos') // Debe tener productos
                     ->whereDoesntHave('productos', function($q) {
                         $q->where('status', '!=', 'activo');
                     });
    }

    // Verifica si la promoción está vigente
    public function esValida()
    {
        if (!$this->activa) {
            return false;
        }

        // Usa las fechas reales sin alterar el año
        $now = now();

        if ($now->lt($this->fecha_inicio) || $now->gt($this->fecha_fin)) {
            return false;
        }

        // Si tiene productos activos, sigue siendo válida
        return $this->productosActivos()->exists();
    }

    // Retorna los productos inactivos
    public function getProductosInactivosAttribute()
    {
        return $this->productos()->where('status', '!=', 'activo')->get();
    }

    // Calcula el precio con descuento
    public function calcularPrecioConDescuento($precioOriginal)
    {
        if ($this->tipo_descuento === 'porcentaje') {
            return $precioOriginal * (1 - ($this->valor_descuento / 100));
        }

        return max(0, $precioOriginal - $this->valor_descuento);
    }
}
