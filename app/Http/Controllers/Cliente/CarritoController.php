<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Promocion;

class CarritoController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $total = $this->cartService->calculateTotal($cart);
        return view('cliente.carrito', compact('cart', 'total'));
    }

    public function checkout()
    {
        // Retornar la vista de checkout. La validación del carrito se hará en el cliente (JS)
        // ya que el carrito principal vive en localStorage.
        return view('cliente.carrito.checkout');
    }

    // Cambiar el nombre del método para que coincida con la ruta
    public function finalizar(\Illuminate\Http\Request $request)
    {
        // Log para debug
        \Log::info('🔵 CarritoController::finalizar() llamado');
        \Log::info('Request headers:', $request->headers->all());
        \Log::info('Request input:', $request->all());

        // Soportar tanto peticiones AJAX (frontend envia carrito en el body)
        // como el flujo tradicional donde el carrito está en el servidor (CartService).

        $cartFromRequest = $request->input('carrito');

        // Si viene carrito en el request, úsalo; si no, toma el del servicio
        $cart = is_array($cartFromRequest) ? $cartFromRequest : $this->cartService->getCart();

        // Si no hay items, devolver según el tipo de petición
        if (empty($cart) || !is_array($cart)) {
            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tu carrito está vacío.'
                ], 400);
            }

            return redirect()->route('cliente.carrito')->with('error', 'Tu carrito está vacío.');
        }

        // Validar stock y preparar items para el pedido (JSON)
    $itemsToStore = []; 

    foreach ($cart as $item) {
        $itemId = (int)($item['id'] ?? 0);
        $itemQty = isset($item['cantidad']) ? (int)$item['cantidad'] : 0;
        $itemName = $item['nombre'] ?? ('#' . $itemId);

        if ($itemId == 0 || $itemQty <= 0) {
             if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Ítem inválido en el carrito.'], 400);
            }
            return redirect()->route('cliente.carrito')->with('error', 'Ítem inválido en el carrito.');
        }

        // --- LÓGICA PARA PROMOCIONES (ID NEGATIVO) ---
        if ($itemId < 0) {
            $promocionId = abs($itemId);
            $promocion = \App\Models\Promocion::with('productosActivos')->find($promocionId);

            if (!$promocion || !$promocion->activa) {
                $msg = "La promoción '{$itemName}' ya no está disponible.";
                if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $msg], 400);
                }
                return redirect()->route('cliente.carrito')->with('error', $msg);
            }

            // Validar stock y agregar productos individuales
            foreach ($promocion->productosActivos as $prod) {
                $qtyRequired = $itemQty; 
                
                if ($prod->stock < $qtyRequired) {
                    $msg = "Stock insuficiente para el producto '{$prod->nombre}' incluido en el pack '{$itemName}'. Disponible: {$prod->stock}";
                    if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => $msg], 400);
                    }
                    return redirect()->route('cliente.carrito')->with('error', $msg);
                }

                $itemsToStore[] = [
                    'producto_id' => $prod->id,
                    'cantidad' => $qtyRequired,
                    'precio' => $prod->precio_descuento,
                    'nombre' => $prod->nombre . " (Pack: {$promocion->nombre})"
                ];
            }
            continue; 
        }

        // --- LÓGICA PARA PRODUCTOS NORMALES ---
        $producto = Producto::find($itemId);
        if (!$producto) {
            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Producto no encontrado: ' . $itemName], 404);
            }
            return redirect()->route('cliente.carrito')->with('error', 'Producto no encontrado: ' . $itemName);
        }

        if ($producto->stock < $itemQty) {
            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Stock insuficiente para: ' . $itemName . '. Stock disponible: ' . $producto->stock], 400);
            }
            return redirect()->route('cliente.carrito')->with('error', 'Stock insuficiente para: ' . $itemName . '. Stock disponible: ' . $producto->stock);
        }

        $itemsToStore[] = [
            'producto_id' => $producto->id,
            'cantidad' => $itemQty,
            'precio' => $producto->precio, // O usar $item['precio'] si se confía
            'nombre' => $producto->nombre
        ];
    }

    DB::beginTransaction();
    try {
        // Calcular total real
        $total = 0;
        foreach ($itemsToStore as $procItem) {
            $total += $procItem['precio'] * $procItem['cantidad'];
        }

        $pedido = Pedido::create([
            'cliente_id' => auth('cliente')->id(),
            'total' => $total,
            'estado' => 'pendiente',
            'metodo_pago' => $request->input('metodo_pago', 'efectivo')
        ]);

        // Guardar detalles
        foreach ($itemsToStore as $item) {
            \App\Models\PedidoDetalle::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item['producto_id'],
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio']
            ]);
        }

        // Descontar stock usando el método del modelo (que maneja transacciones y validación extra)
        try {
            $pedido->decrementarStock();
        } catch (\Exception $e) {
            // Si falla el descuento de stock (por concurrencia), rollback
            throw $e;
        }

        // Limpiar carrito
        if (!is_array($cartFromRequest)) {
            $this->cartService->clear();
        }

        DB::commit();

        if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'pedido_id' => $pedido->id,
                'message' => '¡Pedido realizado correctamente!'
            ]);
        }

        return redirect()->route('cliente.pedidos.show', $pedido->id)
                         ->with('success', '¡Pedido realizado correctamente!');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error al finalizar compra: ' . $e->getMessage());

        if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pedido: ' . $e->getMessage()
            ], 500);
        }
        return redirect()->route('cliente.carrito')->with('error', 'Error al procesar el pedido.');
    }
}

        /**
     * ✅ Finalizar compra - Endpoint API (AJAX)
     * Simple and functional - stores items directly in JSON
     */
    public function finalizarApi(\Illuminate\Http\Request $request)
    {
        try {
            \Log::info('🔵 CarritoController::finalizarApi() llamado');

            $cart = $request->input('carrito', []);

            // Validar carrito no vacío
            if (empty($cart) || !is_array($cart)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tu carrito está vacío.'
                ], 400);
            }

            // Validar stock para cada producto
            foreach ($cart as $item) {
                $itemId = $item['id'] ?? null;
                $itemQty = (int)($item['cantidad'] ?? 0);
                $itemName = $item['nombre'] ?? "Producto #$itemId";

                if (!$itemId || $itemQty <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Carrito inválido.'
                    ], 400);
                }

                $producto = Producto::find($itemId);
                if (!$producto) {
                    return response()->json([
                        'success' => false,
                        'message' => "Producto no encontrado: $itemName"
                    ], 404);
                }

                if ($producto->stock < $itemQty) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente para $itemName. Disponible: {$producto->stock}"
                    ], 400);
                }
            }

            // Crear pedido
            DB::beginTransaction();
            try {
                // Calcular total
                $total = 0;
                foreach ($cart as $item) {
                    $total += (float)($item['precio'] ?? 0) * (int)($item['cantidad'] ?? 1);
                }

                // Crear el pedido
                $pedido = Pedido::create([
                    'cliente_id' => auth('cliente')->id(),
                    'total' => $total,
                    'estado' => 'pendiente',
                    'metodo_pago' => $request->input('metodo_pago', 'efectivo')
                ]);

                // Guardar detalles
                foreach ($cart as $item) {
                    \App\Models\PedidoDetalle::create([
                        'pedido_id' => $pedido->id,
                        'producto_id' => $item['id'],
                        'cantidad' => $item['cantidad'],
                        'precio' => $item['precio']
                    ]);
                }

                // Decrementar stock
                $pedido->decrementarStock();

                DB::commit();

                \Log::info('✅ Pedido creado:', ['pedido_id' => $pedido->id, 'total' => $total]);

                return response()->json([
                    'success' => true,
                    'pedido_id' => $pedido->id,
                    'message' => '¡Pedido realizado correctamente!'
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('❌ Error en finalizarApi: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pedido. Por favor, intenta nuevamente.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Obtener productos de una promoción para agregar al carrito (AJAX)
     */
    public function agregarPromocion(Request $request)
    {
        try {
            $promocionId = $request->input('promocion_id');
            $promocion = \App\Models\Promocion::with('productosActivos')->find($promocionId);

            if (!$promocion || !$promocion->activa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Promoción no válida o expirada.'
                ], 404);
            }

            $productos = [];
        $totalPrecio = 0;
        
        foreach ($promocion->productosActivos as $prod) {
            $totalPrecio += $prod->precio_descuento;
            // No necesitamos devolver la lista detallada al frontend si vamos a agruparlo
            // pero podríamos devolverla si quisiéramos mostrar detalles en el modal del carrito
        }

        if ($promocion->productosActivos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Esta promoción no tiene productos disponibles actualmente.'
            ], 400);
        }

        // Devolver un ÚNICO objeto que representa el pack completo
        // Usamos ID negativo para diferenciarlo de productos normales
        $promoItem = [
            'id' => -1 * $promocion->id, 
            'nombre' => 'Pack: ' . $promocion->nombre,
            'precio' => $totalPrecio,
            'imagen' => null, // O una imagen genérica de promo
            'cantidad' => 1,
            'es_promo' => true
        ];

        return response()->json([
            'success' => true,
            'producto' => $promoItem, // Cambiado de 'productos' (array) a 'producto' (single)
            'message' => 'Pack agregado al carrito.'
        ]);

    } catch (\Exception $e) {
        \Log::error('Error en agregarPromocion: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al procesar la promoción.'
        ], 500);
    }
}
}