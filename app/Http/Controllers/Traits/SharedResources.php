<?php

namespace App\Http\Controllers\Traits;

use App\Models\Promocion;
use App\Models\Producto;
use App\Models\Categoria;

trait SharedResources
{
    /**
     * Query builder para promociones activas dentro del rango de fechas
     * Retorna un Builder para poder contar/filtrar si es necesario
     */
    protected function activePromocionesQuery()
    {
        return Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->orderBy('fecha_inicio', 'desc');
    }

    /**
     * Obtener promociones activas (colección)
     */
    protected function getActivePromociones()
    {
        return $this->activePromocionesQuery()->get();
    }

    /**
     * Productos disponibles (usa scopeDisponibles si existe)
     */
    protected function availableProductosQuery()
    {
        // El modelo Producto define scopeDisponibles
        return Producto::disponibles();
    }

    /**
     * Obtener productos disponibles (opcional con categoría)
     */
    protected function getAvailableProductos($withCategoria = false)
    {
        $q = $this->availableProductosQuery();
        if ($withCategoria) {
            $q = $q->with('categoria');
        }
        return $q->get();
    }

    /**
     * Obtener categorías activas que tengan productos disponibles
     */
    protected function getActiveCategoriasWithProductos()
    {
        return Categoria::where('status', 'activo')
            ->whereHas('productos', function($q) {
                $q->where('status', 'activo')
                  ->where('stock', '>', 0);
            })
            ->orderBy('orden')
            ->get();
    }

    /**
     * Usado por partes del admin que usan la columna `estado` en lugar de `status`
     */
    protected function getProductosActivosForAdmin()
    {
        return Producto::where('estado', 'activo')->get();
    }

    /**
     * Contar productos con stock bajo
     */
    protected function countLowStock()
    {
        return Producto::where('stock', '<=', 5)->where('stock', '>', 0)->count();
    }
}
