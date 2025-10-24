<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    // 🟢 ELIMINAR COMPLETAMENTE EL CONSTRUCTOR
    // NO USAR CONSTRUCTOR CON MIDDLEWARE

    // 🟢 Listado general
    public function index()
    {
        $productos = Producto::with('categoria')->paginate(10);
        return view('admin.productos.index', compact('productos'));
    }

    // 🟢 Formulario de creación
    public function create()
    {
        $categorias = Categoria::all();
        return view('admin.productos.create', compact('categorias'));
    }

    // 🟢 Guardar producto nuevo
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string|max:1000',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Manejo de imagen
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('productos', 'public');
            $validated['imagen'] = $imagenPath;
        }

        Producto::create($validated);

        return redirect()->route('admin.productos.index')->with('success', '✅ Producto agregado correctamente.');
    }

    // 🟢 Formulario de edición
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    // 🟢 Actualizar producto
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

        return redirect()->route('admin.productos.index')
                         ->with('success', 'Producto actualizado correctamente.');
    }

    // 🟢 Eliminar producto
    public function destroy(Producto $producto)
    {
        $user = request()->user('admin');
        if (!$user || !$user->hasRole('Administrador')) {
            return redirect()->route('admin.productos.index')->with('error', 'No tienes permiso para eliminar productos.');
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
                         ->with('success', '🗑️ Producto eliminado correctamente.');
    }

    // 🟢 Actualizar estado del producto
    public function updateEstado(Request $request, Producto $producto)
    {
        $request->validate([
            'estado' => 'required|string|in:activo,inactivo,agotado',
        ]);

        $producto->update(['estado' => $request->estado]);

        return redirect()->back()->with('success', 'Estado del producto actualizado correctamente.');
    }
}