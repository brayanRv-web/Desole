<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Promocion;
use App\Models\Categoria;
use App\Models\Cliente as ClienteModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{   
    /**
     * Mostrar menú público (accesible sin autenticación)
     */
    public function index()
    {
        $productos = Producto::where('estado', 'activo')
            ->where('stock', '>', 0)
            ->with('categoria')
            ->orderBy('nombre')
            ->get();

        $categorias = Categoria::where('estado', 'activo')
            ->whereHas('productos', function($query) {
                $query->where('estado', 'activo')->where('stock', '>', 0);
            })
            ->get();

        return view('cliente.menu', compact('productos', 'categorias'));
    }

    /**
     * Mostrar detalle de producto (accesible sin autenticación)
     */
    public function show(Producto $producto)
    {
        if (!$producto->estaDisponible()) {
            abort(404, 'Producto no disponible');
        }

        $productosRelacionados = Producto::where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->where('estado', 'activo')
            ->where('stock', '>', 0)
            ->take(4)
            ->get();

        return view('cliente.productos.show', compact('producto', 'productosRelacionados'));
    }

    /**
     * Dashboard del cliente autenticado
     */
    public function dashboard()
    {
        $cliente = Auth::guard('cliente')->user();
        
        $pedidosRecientes = Pedido::where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        $promociones = Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->get();

        $whatsapp_number = "529614564697";
        $telefono = "529614564697"; 
        $email = "info@desole.com";

        return view('cliente.dashboard', compact(
            'cliente', 
            'pedidosRecientes', 
            'promociones', 
            'whatsapp_number',
            'telefono',
            'email'
        ));
    }

    /**
     * Menú para clientes autenticados
     */
    public function menu()
    {
        $categorias = Categoria::with(['productos' => function($query) {
            $query->where('estado', 'activo')->where('stock', '>', 0);
        }])->where('estado', 'activo')->get();

        $promociones = Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->get();

        return view('cliente.menu', compact('categorias', 'promociones'));
    }

    /**
     * Historial de pedidos del cliente
     */
    public function pedidos()
    {
        $cliente = Auth::guard('cliente')->user();
        $pedidos = Pedido::where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.pedidos', compact('pedidos'));
    }

    /**
     * Perfil del cliente
     */
    public function perfil()
    {
        $cliente = Auth::guard('cliente')->user();
        
        $promociones = Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->get();
        
        $pedidosRecientes = Pedido::where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('cliente.perfil', compact('cliente', 'promociones', 'pedidosRecientes'));
    }

    /**
     * Actualizar perfil del cliente
     */
    public function actualizarPerfil(Request $request)
    {
        $cliente = Auth::guard('cliente')->user();
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email,' . $cliente->id,
            'telefono' => 'required|string|max:20|unique:clientes,telefono,' . $cliente->id,
            'direccion' => 'required|string|max:500',
            'colonia' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'alergias' => 'nullable|string|max:500',
            'preferencias' => 'nullable|string|max:500',
            'referencias' => 'nullable|string|max:500',
        ]);

        // Actualizar contraseña si se proporciona
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $cliente->update($validated);

        return redirect()->route('cliente.perfil')
            ->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Agregar producto al carrito
     */
    public function agregarAlCarrito(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = Producto::findOrFail($request->producto_id);

        // Verificar stock disponible
        if ($producto->stock < $request->cantidad) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuficiente. Solo quedan ' . $producto->stock . ' unidades.'
            ], 422);
        }

        $carrito = session()->get('carrito', []);
        $productoId = $request->producto_id;
        $cantidad = $request->cantidad;

        if(isset($carrito[$productoId])) {
            $nuevaCantidad = $carrito[$productoId]['cantidad'] + $cantidad;
            
            // Verificar stock nuevamente con la cantidad total
            if ($producto->stock < $nuevaCantidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente. No puedes agregar más de ' . $producto->stock . ' unidades.'
                ], 422);
            }
            
            $carrito[$productoId]['cantidad'] = $nuevaCantidad;
        } else {
            $carrito[$productoId] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $cantidad,
                'imagen' => $producto->imagen,
                'stock_disponible' => $producto->stock
            ];
        }

        session()->put('carrito', $carrito);

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'carrito_count' => array_sum(array_column($carrito, 'cantidad')),
            'carrito_total' => $this->calcularTotalCarrito($carrito),
            'producto_agregado' => $producto->nombre
        ]);
    }

    /**
     * Actualizar cantidad en el carrito
     */
    public function actualizarCarrito(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:0'
        ]);

        $carrito = session()->get('carrito', []);
        $productoId = $request->producto_id;
        $cantidad = $request->cantidad;

        // Verificar stock si la cantidad es mayor a 0
        if ($cantidad > 0) {
            $producto = Producto::findOrFail($productoId);
            if ($producto->stock < $cantidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stock insuficiente. Solo quedan ' . $producto->stock . ' unidades.'
                ], 422);
            }
        }

        if ($cantidad <= 0) {
            unset($carrito[$productoId]);
            $mensaje = 'Producto eliminado del carrito';
        } else {
            $carrito[$productoId]['cantidad'] = $cantidad;
            $mensaje = 'Carrito actualizado';
        }

        session()->put('carrito', $carrito);

        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'carrito_count' => array_sum(array_column($carrito, 'cantidad')),
            'carrito_total' => $this->calcularTotalCarrito($carrito)
        ]);
    }

    /**
     * Confirmar pedido y procesar compra
     */
    public function confirmarPedido(Request $request)
    {
        $cliente = Auth::guard('cliente')->user();
        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío'
            ], 422);
        }

        // Validar stock antes de procesar el pedido
        foreach ($carrito as $item) {
            $producto = Producto::find($item['id']);
            if (!$producto || $producto->stock < $item['cantidad']) {
                return response()->json([
                    'success' => false,
                    'message' => 'El producto "' . $item['nombre'] . '" ya no está disponible en la cantidad solicitada.'
                ], 422);
            }
        }

        // Crear el pedido
        try {
            $pedido = Pedido::create([
                'cliente_id' => $cliente->id,
                'total' => $this->calcularTotalCarrito($carrito),
                'estado' => 'pendiente',
                'metodo_pago' => $request->metodo_pago ?? 'efectivo',
                'direccion_entrega' => $cliente->direccion,
                'notas' => $request->notas
            ]);

            // Agregar productos al pedido y actualizar stock
            foreach ($carrito as $item) {
                $pedido->productos()->attach($item['id'], [
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio']
                ]);

                // Actualizar stock del producto
                $producto = Producto::find($item['id']);
                $producto->stock -= $item['cantidad'];
                
                // Actualizar estado automáticamente si stock llega a 0
                if ($producto->stock <= 0) {
                    $producto->estado = 'agotado';
                    $producto->estado_stock = 'agotado';
                }
                
                $producto->save();
            }

            // Limpiar carrito
            session()->forget('carrito');

            return response()->json([
                'success' => true,
                'message' => 'Pedido confirmado exitosamente. Número de pedido: #' . $pedido->id,
                'pedido_id' => $pedido->id,
                'redirect_url' => route('cliente.pedidos')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ver carrito actual
     */
    public function verCarrito()
    {
        $carrito = session()->get('carrito', []);
        $total = $this->calcularTotalCarrito($carrito);

        return view('cliente.carrito', compact('carrito', 'total'));
    }

    /**
     * Vaciar carrito
     */
    public function vaciarCarrito()
    {
        session()->forget('carrito');

        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado',
            'carrito_count' => 0,
            'carrito_total' => 0
        ]);
    }

    /**
     * Eliminar producto específico del carrito
     */
    public function eliminarDelCarrito(Request $request)
    {
        $carrito = session()->get('carrito', []);
        $productoId = $request->producto_id;

        if (isset($carrito[$productoId])) {
            unset($carrito[$productoId]);
            session()->put('carrito', $carrito);
        }

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado del carrito',
            'carrito_count' => array_sum(array_column($carrito, 'cantidad')),
            'carrito_total' => $this->calcularTotalCarrito($carrito)
        ]);
    }

    /**
     * Obtener información del carrito (para AJAX)
     */
    public function obtenerCarrito()
    {
        $carrito = session()->get('carrito', []);

        return response()->json([
            'success' => true,
            'carrito' => $carrito,
            'carrito_count' => array_sum(array_column($carrito, 'cantidad')),
            'carrito_total' => $this->calcularTotalCarrito($carrito)
        ]);
    }

    /**
     * Calcular total del carrito
     */
    private function calcularTotalCarrito($carrito)
    {
        $total = 0;
        foreach($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

    /**
     * Ver detalle de un pedido específico
     */
    public function verPedido(Pedido $pedido)
    {
        // Verificar que el pedido pertenezca al cliente autenticado
        if ($pedido->cliente_id !== Auth::guard('cliente')->user()->id) {
            abort(403, 'No tienes permiso para ver este pedido');
        }

        $pedido->load('productos');

        return view('cliente.pedidos.show', compact('pedido'));
    }

    /**
     * Cancelar pedido (si está pendiente)
     */
    public function cancelarPedido(Pedido $pedido)
    {
        // Verificar que el pedido pertenezca al cliente autenticado
        if ($pedido->cliente_id !== Auth::guard('cliente')->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para cancelar este pedido'
            ], 403);
        }

        // Solo se pueden cancelar pedidos pendientes
        if ($pedido->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden cancelar pedidos pendientes'
            ], 422);
        }

        try {
            // Devolver stock de los productos
            foreach ($pedido->productos as $producto) {
                $producto->stock += $producto->pivot->cantidad;
                
                // Si el producto estaba agotado, reactivarlo
                if ($producto->estado === 'agotado' && $producto->stock > 0) {
                    $producto->estado = 'activo';
                    $producto->estado_stock = 'disponible';
                }
                
                $producto->save();
            }

            // Actualizar estado del pedido
            $pedido->update(['estado' => 'cancelado']);

            return response()->json([
                'success' => true,
                'message' => 'Pedido cancelado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar el pedido: ' . $e->getMessage()
            ], 500);
        }
    }
}