<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    // Listado (panel empleado)
    public function index()
    {
        $productos = Producto::with('categoria')->paginate(10);
        // Usamos una vista específica para empleado (sin botón eliminar)
        return view('empleado.productos.index', compact('productos'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('empleado.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['nombre', 'precio', 'categoria_id', 'descripcion']);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('empleado.productos.index')
                         ->with('success', 'Producto actualizado correctamente.');
    }

    // Cambiar estado (empleado puede actualizar menú)
    public function updateEstado(Request $request, Producto $producto)
    {
        $request->validate([
            'estado' => 'required|string|in:activo,inactivo,agotado',
        ]);

        $producto->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', 'Estado del producto actualizado correctamente.');
    }
}
