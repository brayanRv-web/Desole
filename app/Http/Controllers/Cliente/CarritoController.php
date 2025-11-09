<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Contracts\CartServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Http\Requests\Cliente\CartRequest;
use App\Models\Pedido;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarritoController extends Controller
{
    use JsonResponseTrait;

    protected CartServiceInterface $cartService;
    protected OrderServiceInterface $orderService;

    public function __construct(
        CartServiceInterface $cartService,
        OrderServiceInterface $orderService
    ) {
        $this->middleware('auth:cliente');
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Mostrar el carrito
     */
    public function index()
    {
        $carrito = $this->cartService->getCart();
        $total = $this->cartService->calculateTotal($carrito);
        return view('cliente.carrito', compact('carrito', 'total'));
    }

    /**
     * Agregar producto al carrito
     */
    public function add(CartRequest $request): JsonResponse
    {
        $result = $this->cartService->add(
            (int) $request->producto_id,
            (int) $request->cantidad
        );

        if (!$result['success']) {
            return $this->errorResponse($result['message']);
        }

        return $this->cartResponse(
            $result['message'] ?? 'Producto agregado al carrito',
            $result['cart'] ?? null
        );
    }

    /**
     * Actualizar cantidad de un producto en el carrito
     */
    public function update(CartRequest $request): JsonResponse
    {
        $result = $this->cartService->update(
            (int) $request->producto_id,
            (int) $request->cantidad
        );

        if (!$result['success']) {
            return $this->errorResponse($result['message']);
        }

        return $this->cartResponse(
            $result['message'] ?? 'Carrito actualizado',
            $result['cart'] ?? null
        );
    }

    /**
     * Eliminar un producto del carrito
     */
    public function remove(CartRequest $request): JsonResponse
    {
        $result = $this->cartService->remove((int) $request->producto_id);
        
        return $this->cartResponse(
            $result['message'] ?? 'Producto eliminado del carrito',
            $result['cart'] ?? null
        );
    }

    /**
     * Vaciar el carrito
     */
    public function clear(): JsonResponse
    {
        $this->cartService->clear();
        
        return $this->cartResponse('Carrito vaciado', []);
    }

    /**
     * Obtener información del carrito
     */
    public function getInfo(): JsonResponse
    {
        $carrito = $this->cartService->getCart();
        return $this->cartResponse('', $carrito);
    }

    /**
     * Mostrar vista de confirmación del pedido
     */
    public function confirmar()
    {
        $cliente = Auth::guard('cliente')->user();
        $carrito = $this->cartService->getCart();
        $total = $this->cartService->calculateTotal($carrito);
        
        return view('cliente.carrito.confirmar', compact('cliente', 'carrito', 'total'));
    }

    /**
     * Procesar el pedido
     */
    public function procesar(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'notas' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            $cliente = Auth::guard('cliente')->user();
            $carrito = $this->cartService->getCart();

            if (empty($carrito)) {
                throw new \Exception('El carrito está vacío');
            }

            $pedido = $this->orderService->create([
                'cliente_id' => $cliente->id,
                'cliente_nombre' => $cliente->nombre,
                'cliente_telefono' => $cliente->telefono,
                'direccion' => $cliente->direccion,
                'notas' => $request->notas
            ], $carrito);

            DB::commit();

            return $this->successResponse(
                'Pedido creado correctamente. Número de pedido: #' . $pedido->id,
                [
                    'pedido_id' => $pedido->id,
                    'redirect_url' => route('cliente.pedidos.show', $pedido)
                ]
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al crear el pedido: ' . $e->getMessage());
        }
    }
}