<?php

namespace App\Traits;

trait JsonResponseTrait
{
    protected function successResponse($data = [], $message = '', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message, $code = 422)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    protected function cartResponse($cart = null, $message = '')
    {
        return $this->successResponse([
            'carrito' => $cart,
            'carrito_count' => $this->cartService->count(),
            'carrito_total' => $this->cartService->calculateTotal($cart)
        ], $message);
    }
}