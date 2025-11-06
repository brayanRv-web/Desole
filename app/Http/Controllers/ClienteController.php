<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Promocion;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function dashboard()
    {
        $cliente = Auth::guard('cliente')->user();
        $pedidosRecientes = Pedido::where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        $promociones = Promocion::where('activa', 1)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->get();

            // Agregar la variable faltante
        $whatsapp_number = "529614564697"; // Reemplaza con tu número real
        $telefono = "529614564697"; 
         $email = "info@desole.com";
        return view('cliente.dashboard', compact('cliente', 'pedidosRecientes', 'promociones', 'whatsapp_number'));
    }

    public function menu()
    {
        $categorias = Categoria::with(['productos' => function($query) {
            $query->where('disponible', 1);
        }])->get();

        $promociones = Promocion::where('activa', 1)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->get();

        return view('cliente.menu', compact('categorias', 'promociones'));
    }

    public function pedidos()
    {
        $user = Auth::user();
        $pedidos = Pedido::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.pedidos', compact('pedidos'));
    }

    public function perfil()
    {
        $cliente = Auth::guard('cliente')->user();
        // Agregar las variables que necesita welcome.blade.php
        $promociones = Promocion::where('activa', 1)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->get();
        
        // Si welcome.blade.php necesita más variables, agrégalas aquí
        $pedidosRecientes = Pedido::where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('cliente.perfil', compact('cliente', 'promociones', 'pedidosRecientes'));
    }

    public function actualizarPerfil(Request $request)
    {
        $cliente = Auth::guard('cliente')->user();
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20|unique:clientes,telefono,' . $cliente->id,
            'direccion' => 'required|string|max:500',
            'colonia' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'alergias' => 'nullable|string|max:500',
            'preferencias' => 'nullable|string|max:500',
            'referencias' => 'nullable|string|max:500',
        ]);

        $cliente->update($validated);

        return redirect()->route('cliente.perfil')
            ->with('success', 'Perfil actualizado correctamente');
    }

    public function agregarAlCarrito(Request $request)
    {
        // Lógica del carrito (usando session o base de datos)
        $carrito = session()->get('carrito', []);
        
        $productoId = $request->producto_id;
        $cantidad = $request->cantidad ?? 1;

        if(isset($carrito[$productoId])) {
            $carrito[$productoId]['cantidad'] += $cantidad;
        } else {
            $producto = Producto::find($productoId);
            $carrito[$productoId] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $cantidad,
                'imagen' => $producto->imagen
            ];
        }

        session()->put('carrito', $carrito);

        return response()->json([
            'success' => true,
            'carrito_count' => array_sum(array_column($carrito, 'cantidad')),
            'carrito_total' => $this->calcularTotalCarrito($carrito)
        ]);
    }

    private function calcularTotalCarrito($carrito)
    {
        $total = 0;
        foreach($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }
}