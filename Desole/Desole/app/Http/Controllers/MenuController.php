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
        $promociones = Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->get();

        return view('public.menu', compact('productos', 'categorias', 'promociones'));
    }

    /**
     * Mostrar la página de preguntas frecuentes
     */
    public function faq()
    {
        return view('public.faq');
    }
}