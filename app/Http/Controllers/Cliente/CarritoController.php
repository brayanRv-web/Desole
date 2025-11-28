<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // Validar stock y estructura mínima de cada ítem
        foreach ($cart as $item) {
            $itemId = $item['id'] ?? null;
            $itemQty = isset($item['cantidad']) ? (int)$item['cantidad'] : 0;
            $itemName = $item['nombre'] ?? ('#' . ($itemId ?? 'desconocido'));

            if (!$itemId || $itemQty <= 0) {
                if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Carrito inválido.'], 400);
                }
                return redirect()->route('cliente.carrito')->with('error', 'Carrito inválido.');
            }

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
        }

        DB::beginTransaction();
        try {
            // Calcular total: si el request trajo precio, mejor recalcular en servidor
            $total = 0;
            foreach ($cart as $item) {
                $precio = isset($item['precio']) ? (float)$item['precio'] : (float)Producto::find($item['id'])->precio ?? 0;
                $cantidad = (int)($item['cantidad'] ?? 0);
                $total += $precio * $cantidad;
            }

            $pedido = Pedido::create([
                'cliente_id' => auth()->id(),
                'total' => $total,
                'estado' => 'pendiente',
            ]);

            foreach ($cart as $item) {
                $itemId = $item['id'];
                $cantidad = (int)($item['cantidad'] ?? 1);
                $precio = isset($item['precio']) ? (float)$item['precio'] : (float)Producto::find($itemId)->precio ?? 0;

                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $itemId,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                ]);

                // Actualizar stock en producto
                $producto = Producto::find($itemId);
                $producto->decrement('stock', $cantidad);
            }

            // Si usamos el carrito del servidor, limpiarlo; si el carrito vino del cliente
            // (localStorage), el frontend ya lo limpia tras recibir success.
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
                    'message' => 'Error al procesar el pedido. Por favor, intenta nuevamente.'
                ], 500);
            }

            return redirect()->route('cliente.carrito')->with('error', 'Error al procesar el pedido. Por favor, intenta nuevamente.');
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

                // Crear el pedido con items en JSON
                $pedido = Pedido::create([
                    'cliente_id' => auth('cliente')->id(),
                    'total' => $total,
                    'items' => $cart,  // Guardar items directamente como JSON
                    'estado' => 'pendiente'
                ]);

                // Decrementar stock de cada producto
                foreach ($cart as $item) {
                    $producto = Producto::find($item['id']);
                    $producto->decrement('stock', (int)($item['cantidad'] ?? 1));
                }

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
}