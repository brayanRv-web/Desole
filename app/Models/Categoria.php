<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 
        'descripcion', 
        'tipo', 
        'icono', 
        'color', 
        'orden',
        'estado' // ✅ NUEVO CAMPO
    ];

    protected $casts = [
        'orden' => 'integer'
    ];

    // ✅ SCOPE PARA CATEGORÍAS ACTIVAS
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activo');
    }

    // ✅ SCOPE PARA CATEGORÍAS CON PRODUCTOS DISPONIBLES
    public function scopeConProductosDisponibles($query)
    {
        return $query->whereHas('productos', function($q) {
            $q->where('estado', 'activo')
              ->where('stock', '>', 0)
              ->where('estado_stock', 'disponible');
        });
    }

    // Relación con productos
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    // ✅ MÉTODO PARA VERIFICAR SI LA CATEGORÍA ESTÁ ACTIVA
    public function estaActiva()
    {
        return $this->estado === 'activo';
    }

    // ✅ MÉTODO PARA ACTIVAR CATEGORÍA
    public function activar()
    {
        $this->update(['estado' => 'activo']);
    }

    // ✅ MÉTODO PARA DESACTIVAR CATEGORÍA
    public function desactivar()
    {
        $this->update(['estado' => 'inactivo']);
    }
}