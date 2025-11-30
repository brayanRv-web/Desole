<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
            'status' => 'required|in:activo,inactivo'
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

        // Lógica de Estado vs Stock
        if ($request->stock == 0) {
            $data['estado_stock'] = 'agotado';
            // Si es inactivo, se queda inactivo. Si es activo, pasa a agotado.
            if ($request->status == 'activo') {
                $data['status'] = 'agotado';
            }
        } else {
            $data['estado_stock'] = 'disponible';
            // Si estaba agotado y ahora hay stock, pasa a activo.
            // Si el usuario lo marcó como inactivo, se respeta.
            if ($producto->status == 'agotado' && $request->status != 'inactivo') {
                $data['status'] = 'activo';
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
        Log::info('Datos recibidos:', $request->all());
        
        $request->validate([
            'status' => 'required|in:activo,inactivo'
        ]);

        $nuevoEstado = $request->status;
        $nuevoEstadoStock = 'disponible';

        // Si intenta activar pero no hay stock, se pone como agotado
        if ($nuevoEstado == 'activo' && $producto->stock <= 0) {
            $nuevoEstado = 'agotado';
            $nuevoEstadoStock = 'agotado';
        }

        $producto->update([
            'status' => $nuevoEstado,
            'estado_stock' => $nuevoEstadoStock
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