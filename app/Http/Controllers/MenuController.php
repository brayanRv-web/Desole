<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Promocion;
use Illuminate\Http\Request;
use App\Services\CatalogService;

class MenuController extends Controller
{
    protected CatalogService $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }
    public function index(Request $request)
    {
        $categoriaId = $request->get('categoria');
        $productos = $this->catalogService->getProductosByCategoria($categoriaId, true)
            ->simplePaginate(12);

        $categorias = $this->catalogService->getActiveCategoriasWithProductos();
        
        // Obtener promociones activas
        // Obtener promociones activas y válidas
        $promociones = Promocion::fullyAvailable()
            ->with('productos')
            ->get();

        return view('public.menu', compact('productos', 'categorias', 'promociones'));
    }
}