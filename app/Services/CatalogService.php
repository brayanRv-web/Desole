<?php

namespace App\Services;

use App\Models\Promocion;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection as SupportCollection;

class CatalogService
{
    /**
     * Query builder for active promotions within date range
     */
    public function activePromocionesQuery(): Builder
    {
        return Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->orderBy('fecha_inicio', 'desc');
    }

    /**
     * Get active promotions collection
     */
    public function getActivePromociones(): Collection
    {
        return $this->activePromocionesQuery()->get();
    }

    /**
     * Get active promotions with products eager loaded
     */
    public function getActivePromocionesWithProducts(): Collection
    {
        return $this->activePromocionesQuery()
            ->with(['productos', 'productosActivos'])
            ->get();
    }

    /**
     * Get promotions stats (active, valid, problems)
     */
    public function getPromocionesStats(): array
    {
        $activas = $this->getActivePromociones();
        return [
            'total' => Promocion::count(),
            'activas' => $activas->count(),
            'validas' => $activas->filter->esValida()->count(),
            'problemas' => $activas->reject->esValida()->count()
        ];
    }

    /**
     * Available products query (uses scopeDisponibles)
     */
    public function availableProductosQuery(): Builder
    {
        return Producto::disponibles();
    }

    /**
     * Get available products (optionally with category)
     */
    public function getAvailableProductos(bool $withCategoria = false): Collection
    {
        $query = $this->availableProductosQuery();
        if ($withCategoria) {
            $query = $query->with('categoria');
        }
        return $query->get();
    }

    /**
     * Get filtered products by category
     */
    public function getProductosByCategoria(?int $categoriaId = null, bool $withCategoria = false): Collection
    {
        $query = $this->availableProductosQuery();
        
        if ($withCategoria) {
            $query->with('categoria');
        }
        
        if ($categoriaId) {
            $query->where('categoria_id', $categoriaId);
        }
        
        return $query->orderBy('nombre')->get();
    }

    /**
     * Search products by name/description
     */
    public function searchProductos(string $search, bool $onlyAvailable = true): Collection
    {
        $query = $onlyAvailable ? $this->availableProductosQuery() : Producto::query();
        
        return $query->where(function($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
              ->orWhere('descripcion', 'like', "%{$search}%");
        })->get();
    }

    /**
     * Get active categories with available products
     */
    public function getActiveCategoriasWithProductos(): Collection
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
     * Get admin-specific active products (using 'estado' column)
     */
    public function getProductosActivosForAdmin(): Collection
    {
        return Producto::where('estado', 'activo')->get();
    }

    /**
     * Get product stats (total, active, low stock, out of stock)
     */
    public function getProductosStats(): array
    {
        return [
            'total' => Producto::count(),
            'activos' => Producto::where('status', 'activo')->count(),
            'stock_bajo' => $this->countLowStock(),
            'agotados' => Producto::where('stock', 0)->count()
        ];
    }

    /**
     * Count products with low stock (> 0 but <= 5)
     */
    public function countLowStock(): int
    {
        return Producto::where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->count();
    }

    /**
     * Get related products for a given product
     */
    public function getRelatedProductos(Producto $producto, int $limit = 4): Collection
    {
        return $this->availableProductosQuery()
            ->where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->take($limit)
            ->get();
    }
}