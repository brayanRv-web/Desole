<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Contracts\CartServiceInterface;
use App\Http\Requests\Cliente\CartRequest;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    protected CartServiceInterface $cartService;

    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    public function add(CartRequest $request): JsonResponse
    {
        $result = $this->cartService->add(
            (int) $request->producto_id,
            (int) $request->cantidad
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'] ?? 'Producto agregado al carrito',
            'carrito_count' => $this->cartService->count(),
            'carrito_total' => $this->cartService->calculateTotal($result['cart'] ?? null),
            'producto_agregado' => $result['producto_agregado'] ?? null
        ]);
    }

    public function update(CartRequest $request): JsonResponse
    {
        $result = $this->cartService->update(
            (int) $request->producto_id,
            (int) $request->cantidad
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => $result['message'] ?? 'Carrito actualizado',
            'carrito_count' => $this->cartService->count(),
            'carrito_total' => $this->cartService->calculateTotal($result['cart'] ?? null)
        ]);
    }

    public function remove(CartRequest $request): JsonResponse
    {
        $result = $this->cartService->remove((int) $request->producto_id);

        return response()->json([
            'success' => true,
            'message' => $result['message'] ?? 'Producto eliminado del carrito',
            'carrito_count' => $this->cartService->count(),
            'carrito_total' => $this->cartService->calculateTotal($result['cart'] ?? null)
        ]);
    }

    public function clear(): JsonResponse
    {
        $this->cartService->clear();

        return response()->json([
            'success' => true,
            'message' => 'Carrito vaciado',
            'carrito_count' => 0,
            'carrito_total' => 0
        ]);
    }

    public function show()
    {
        $carrito = $this->cartService->getCart();
        $total = $this->cartService->calculateTotal($carrito);

        return view('cliente.carrito', compact('carrito', 'total'));
    }

    public function getInfo(): JsonResponse
    {
        $carrito = $this->cartService->getCart();

        return response()->json([
            'success' => true,
            'carrito' => $carrito,
            'carrito_count' => $this->cartService->count(),
            'carrito_total' => $this->cartService->calculateTotal($carrito)
        ]);
    }
}