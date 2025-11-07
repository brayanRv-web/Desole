<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
         $productos = Producto::with('categoria')->get();
    
    //  AGREGAR ESTADÍSTICAS DE STOCK
    $stockBajo = Producto::where('stock', '<=', 5)->where('stock', '>', 0)->count();
    $agotados = Producto::where('stock', 0)->count();
    $totalProductos = $productos->count();
    $productosStockBajo = Producto::with('categoria')
        ->where('stock', '<=', 5)
        ->where('stock', '>', 0)
        ->orderBy('stock', 'asc')
        ->get();

    return view('admin.productos.index', compact(
        'productos', 
        'stockBajo', 
        'agotados', 
        'totalProductos',
        'productosStockBajo'
        ));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'estado' => 'required|in:activo,inactivo,agotado'
        ]);

        $data = $request->all();

        // Manejar la imagen
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        // Ajustar estado_stock basado en el stock
        if ($request->stock == 0) {
            $data['estado_stock'] = 'agotado';
            $data['estado'] = 'agotado';
        } else {
            $data['estado_stock'] = 'disponible';
            
            //  ENVIAR ALERTA SI EL STOCK ES BAJO AL CREAR
            if ($request->stock <= 5) {
                // Se enviará después de crear el producto
            }
        }

        $producto = Producto::create($data);

        //  ENVIAR ALERTA SI ES NECESARIO
        if ($producto->stock <= 5 && $producto->stock > 0) {
            $producto->enviarAlertaStock('bajo');
        }

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'estado' => 'required|in:activo,inactivo,agotado'
        ]);

        $stockAnterior = $producto->stock;
        $data = $request->all();

        // Manejar la imagen
        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        // Ajustar estado_stock basado en el stock
        if ($request->stock == 0) {
            $data['estado_stock'] = 'agotado';
            $data['estado'] = 'agotado';
        } else {
            $data['estado_stock'] = 'disponible';
            if ($producto->estado == 'agotado' && $request->stock > 0) {
                $data['estado'] = 'activo';
            }
        }

        $producto->update($data);

        // ENVIAR ALERTAS SI ES NECESARIO
        if ($request->stock == 0 && $stockAnterior > 0) {
            $producto->enviarAlertaStock('agotado');
        } elseif ($request->stock <= 5 && $stockAnterior > 5) {
            $producto->enviarAlertaStock('bajo');
        }

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function updateEstado(Request $request, Producto $producto)
    {
        $request->validate([
            'estado' => 'required|in:activo,inactivo,agotado'
        ]);

        $producto->update([
            'estado' => $request->estado,
            'estado_stock' => $request->estado == 'agotado' ? 'agotado' : 'disponible'
        ]);

        return back()->with('success', 'Estado del producto actualizado.');
    }

    public function destroy(Producto $producto)
    {
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }

    // Método para actualizar solo el stock
    public function updateStock(Request $request, Producto $producto)
    {
        $request->validate([
            'stock' => 'required|integer|min:0'
        ]);

        $stockAnterior = $producto->stock;
        $nuevoStock = $request->stock;

        $producto->reponerStock($nuevoStock - $producto->stock);

        //  ENVIAR ALERTAS SI ES NECESARIO
        if ($nuevoStock == 0 && $stockAnterior > 0) {
            $producto->enviarAlertaStock('agotado');
        } elseif ($nuevoStock <= 5 && $stockAnterior > 5) {
            $producto->enviarAlertaStock('bajo');
        }

        return back()->with('success', 'Stock actualizado exitosamente.');
    }

    // MÉTODO PARA VER STOCK BAJO
    public function stockBajo()
    {
        $productos = Producto::with('categoria')
            ->where('estado', 'activo')
            ->where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->get();

        return view('admin.productos.stock-bajo', compact('productos'));
    }

    //  MÉTODO PARA VER PRODUCTOS AGOTADOS
    public function agotados()
    {
        $productos = Producto::with('categoria')
            ->where('estado', 'agotado')
            ->orderBy('nombre')
            ->get();

        return view('admin.productos.agotados', compact('productos'));
    }

    /*public function dashboardStock()
    {
        $stockBajo = Producto::where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $agotados = Producto::where('stock', 0)->count();
        $totalProductos = Producto::count();
        $productosStockBajo = Producto::with('categoria')
            ->where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->get();
        
        return view('admin.stock.dashboard', compact(
            'stockBajo', 'agotados', 'totalProductos', 'productosStockBajo'
        ));
    }*/

}