<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('categoria')
            ->where('status', 'activo')
            ->where('stock', '>', 0)
            ->orderBy('nombre');

        // Filtrar por categoría si se especifica
        if ($request->has('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        $productos = $query->paginate(12);
        
        // Obtener categorías que tengan productos disponibles
        $categorias = Categoria::where('status', 'activo')
            ->whereHas('productos', function($q) {
                $q->where('status', 'activo')
                  ->where('stock', '>', 0);
            })
            ->orderBy('orden')
            ->get();

        return view('public.menu', compact('productos', 'categorias'));
    }
}