<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promocion;
use App\Models\Producto;
use Illuminate\Http\Request;

class PromocionController extends Controller
{
public function index()
{
    // ✅ CARGAR LAS RELACIONES CORRECTAS
    $promociones = Promocion::with(['productos', 'productosActivos'])->latest()->get();
    return view('admin.promociones.index', compact('promociones'));
}

    public function create()
    {
        $productos = Producto::where('estado', 'activo')->get();
        return view('admin.promociones.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_descuento' => 'required|in:porcentaje,monto_fijo',
            'valor_descuento' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'productos' => 'required|array|min:1',
            'productos.*' => 'exists:productos,id'
        ]);

        $productosInactivos = Producto::whereIn('id', $request->productos)
                                    ->where('estado', '!=', 'activo')
                                    ->exists();

        if ($productosInactivos) {
            return back()->withErrors([
                'productos' => 'No se pueden agregar productos inactivos o agotados a la promoción.'
            ])->withInput();
        }

        $promocion = Promocion::create($request->only([
            'nombre', 'descripcion', 'tipo_descuento', 
            'valor_descuento', 'fecha_inicio', 'fecha_fin'
        ]));

        $promocion->productos()->sync($request->productos);

        return redirect()->route('admin.promociones.index')
                        ->with('success', 'Promoción creada exitosamente.');
    }

    public function edit(Promocion $promocione)
    {
        $promocion = $promocione;
        $productos = Producto::where('estado', 'activo')->get();
        $productosSeleccionados = $promocion->productos->pluck('id')->toArray();

        return view('admin.promociones.edit', compact('promocion', 'productos', 'productosSeleccionados'));
    }

    public function update(Request $request, Promocion $promocione)
    {
        $promocion = $promocione;

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_descuento' => 'required|in:porcentaje,monto_fijo',
            'valor_descuento' => 'required|numeric|min:0',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'productos' => 'required|array|min:1',
            'productos.*' => 'exists:productos,id'
        ]);

        $productosInactivos = Producto::whereIn('id', $request->productos)
                                    ->where('estado', '!=', 'activo')
                                    ->exists();

        if ($productosInactivos) {
            return back()->withErrors([
                'productos' => 'No se pueden agregar productos inactivos o agotados a la promoción.'
            ])->withInput();
        }

        $promocion->update($request->only([
            'nombre', 'descripcion', 'tipo_descuento', 
            'valor_descuento', 'fecha_inicio', 'fecha_fin'
        ]));

        $promocion->productos()->sync($request->productos);

        return redirect()->route('admin.promociones.index')
                        ->with('success', 'Promoción actualizada exitosamente.');
    }

    public function destroy(Promocion $promocione)
    {
        $user = request()->user('admin');
        if (!$user || !$user->hasRole('Administrador')) {
            return redirect()->route('admin.promociones.index')->with('error', 'No tienes permiso para eliminar promociones.');
        }

        $promocione->delete();
        return redirect()->route('admin.promociones.index')
                        ->with('success', 'Promoción eliminada exitosamente.');
    }

    public function toggleStatus(Promocion $promocione)
    {
        $user = request()->user('admin');
        if (!$user || !$user->hasRole('Administrador')) {
            return redirect()->back()->with('error', 'No tienes permiso para cambiar el estado de la promoción.');
        }

        $promocione->update(['activa' => !$promocione->activa]);
        $status = $promocione->activa ? 'activada' : 'desactivada';
        return back()->with('success', "Promoción {$status} exitosamente.");
    }
}