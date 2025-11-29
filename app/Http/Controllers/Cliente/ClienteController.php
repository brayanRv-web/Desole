<?php

namespace App\Http\Controllers\Cliente;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Promocion;
use App\Models\Categoria;
use App\Models\Cliente as ClienteModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;

class ClienteController extends Controller
{   
    protected CartService $cartService;
    protected OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }
    /**
     * Mostrar menú público (accesible sin autenticación)
     */
    public function index()
    {
        $productos = Producto::where('status', 'activo')
            ->where('stock', '>', 0)
            ->with('categoria')
            ->orderBy('nombre')
            ->get();

        $categorias = Categoria::where('status', 'activo')
            ->whereHas('productos', function($query) {
                $query->where('status', 'activo')->where('stock', '>', 0);
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
            ->where('status', 'activo')
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
            
        $promociones = $this->getActivePromociones();

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
        $productos = Producto::where('status', 'activo')
            ->where('stock', '>', 0)
            ->orderBy('nombre')
            ->get();

        $categorias = Categoria::where('status', 'activo')
            ->whereHas('productos', function($query) {
                $query->where('status', 'activo')
                      ->where('stock', '>', 0);
            })
            ->get();

        $promociones = $this->getActivePromociones();

        return view('cliente.menu', compact('productos', 'categorias', 'promociones'));
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
        $promociones = $this->getActivePromociones();
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
        $productoId = (int) $request->producto_id;
        $cantidad = (int) $request->cantidad;

        $result = $this->cartService->add($productoId, $cantidad);

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['message']], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'] ?? 'Producto agregado al carrito',
            'carrito_count' => $this->cartService->count(),
            'carrito_total' => $this->cartService->calculateTotal($result['cart'] ?? null),
            'producto_agregado' => $result['producto_agregado'] ?? null
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
        $productoId = (int) $request->producto_id;
        $cantidad = (int) $request->cantidad;

        $result = $this->cartService->update($productoId, $cantidad);

        if (!$result['success']) {
            return response()->json(['success' => false, 'message' => $result['message']], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'] ?? 'Carrito actualizado',
            'carrito_count' => $this->cartService->count(),
            'carrito_total' => $this->cartService->calculateTotal($result['cart'] ?? null)
        ]);
    }

    /**
     * Confirmar pedido y procesar compra
     */
    public function confirmarPedido(Request $request)
    {
        $cliente = Auth::guard('cliente')->user();
        $carrito = $this->cartService->getCart();

        if (empty($carrito)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío'
            ], 422);
        }

        try {
            $pedido = $this->orderService->create([
                'cliente_id' => $cliente->id,
                'cliente_nombre' => $cliente->nombre ?? ($request->cliente_nombre ?? null),
                'cliente_telefono' => $cliente->telefono ?? ($request->cliente_telefono ?? null),
                'direccion' => $cliente->direccion ?? ($request->direccion ?? null),
            ], $carrito);

            return response()->json([
                'success' => true,
                'message' => 'Pedido creado correctamente. Número de pedido: #' . $pedido->id,
                'pedido_id' => $pedido->id,
                'redirect_url' => route('cliente.pedidos')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ver carrito actual
     */
    public function verCarrito()
    {
        $carrito = $this->cartService->getCart();
        $total = $this->cartService->calculateTotal($carrito);

        return view('cliente.carrito', compact('carrito', 'total'));
    }

    /**
     * Vaciar carrito
     */
    public function vaciarCarrito()
    {
        $this->cartService->clear();

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
        $productoId = (int) $request->producto_id;
        $result = $this->cartService->remove($productoId);

        return response()->json([
            'success' => true,
            'message' => $result['message'] ?? 'Producto eliminado del carrito',
            'carrito_count' => $this->cartService->count(),
            'carrito_total' => $this->cartService->calculateTotal($result['cart'] ?? null)
        ]);
    }

    /**
     * Obtener información del carrito (para AJAX)
     */
    public function obtenerCarrito()
    {
        $carrito = $this->cartService->getCart();

        return response()->json([
            'success' => true,
            'carrito' => $carrito,
            'carrito_count' => $this->cartService->count(),
            'carrito_total' => $this->cartService->calculateTotal($carrito)
        ]);
    }

    /**
     * NOTE: cart total logic moved to App\Services\CartService
     */

    /**
     * Ver detalle de un pedido específico
     */
    public function verPedido(Pedido $pedido)
    {
        // Verificar que el pedido pertenezca al cliente autenticado
        if ($pedido->cliente_id !== Auth::guard('cliente')->user()->id) {
            abort(403, 'No tienes permiso para ver este pedido');
        }

        // Construir mapa de productos a partir de los items del pedido
        $items = $pedido->items ?? [];
        $productoIds = collect($items)->pluck('producto_id')->filter()->values()->all();
        $productos = [];
        if (!empty($productoIds)) {
            $productos = Producto::whereIn('id', $productoIds)->get()->keyBy('id');
        }

        return view('cliente.pedidos.show', compact('pedido', 'productos'));
    }

    /**
     * Obtener promociones activas (reutilizable)
     */
    private function getActivePromociones()
    {
        return Promocion::where('activa', true)
            ->where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->get();
    }

    /**
     * Cancelar pedido (si está pendiente)
     */
    public function cancelarPedido(Request $request, Pedido $pedido)
    {
        // Verificar que el pedido pertenezca al cliente autenticado
        if ($pedido->cliente_id !== Auth::guard('cliente')->user()->id) {
            $request->session()->flash('error', 'No tienes permiso para cancelar este pedido');
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para cancelar este pedido'
            ], 403);
        }

        $result = $this->orderService->cancel($pedido);

        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return response()->json($result);
        } else {
            $request->session()->flash('error', $result['message']);
            return response()->json($result, 422);
        }
    }
}
