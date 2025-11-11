<?php

namespace App\Services\Cart;

use App\Models\Producto;
use App\Contracts\Services\CartServiceInterface;
use App\DTOs\Cart\CartItemDTO;
use Illuminate\Support\Facades\Session;

class CartService implements CartServiceInterface
{
    protected string $sessionKey = 'carrito';

    public function __construct(protected Producto $producto)
    {}

    public function add(array $data): array
    {
        $producto = $this->producto->findOrFail($data['product_id']);
        $cantidad = $data['quantity'];

        $cart = $this->getContent();

        if (isset($cart[$producto->id])) {
            $nuevaCantidad = $cart[$producto->id]['quantity'] + $cantidad;
            if ($producto->stock < $nuevaCantidad) {
                return [
                    'success' => false,
                    'message' => 'Stock insuficiente. No puedes agregar más de ' . $producto->stock . ' unidades.'
                ];
            }
            $cart[$producto->id]['quantity'] = $nuevaCantidad;
        } else {
            if ($producto->stock < $cantidad) {
                return [
                    'success' => false,
                    'message' => 'Stock insuficiente. Solo quedan ' . $producto->stock . ' unidades.'
                ];
            }
            
            $cartItem = new CartItemDTO(
                productId: $producto->id,
                quantity: $cantidad,
                price: $producto->precio,
                name: $producto->nombre,
                notes: $data['notes'] ?? null
            );
            
            $cart[$producto->id] = $cartItem->toArray();
        }

        Session::put($this->sessionKey, $cart);

        return [
            'success' => true,
            'data' => $cart,
            'message' => 'Producto agregado al carrito'
        ];
    }

    public function update(array $data): array
    {
        $productoId = $data['product_id'];
        $cantidad = $data['quantity'];

        if ($cantidad <= 0) {
            return $this->remove($productoId);
        }

        $producto = $this->producto->findOrFail($productoId);
        if ($producto->stock < $cantidad) {
            return [
                'success' => false,
                'message' => 'Stock insuficiente. Solo quedan ' . $producto->stock . ' unidades.'
            ];
        }

        $cart = $this->getContent();
        if (isset($cart[$productoId])) {
            $cart[$productoId]['quantity'] = $cantidad;
            $cart[$productoId]['notes'] = $data['notes'] ?? $cart[$productoId]['notes'] ?? null;
            
            Session::put($this->sessionKey, $cart);
            
            return [
                'success' => true,
                'data' => $cart,
                'message' => 'Carrito actualizado'
            ];
        }

        return [
            'success' => false,
            'message' => 'Producto no encontrado en el carrito'
        ];
    }

    public function remove(string $productId): bool
    {
        $cart = $this->getContent();
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put($this->sessionKey, $cart);
            return true;
        }
        return false;
    }

    public function clear(): bool
    {
        Session::forget($this->sessionKey);
        return true;
    }

    public function getContent(): array
    {
        return Session::get($this->sessionKey, []);
    }

    public function getInfo(): array
    {
        $cart = $this->getContent();
        
        $total = 0;
        $itemCount = 0;
        
        foreach ($cart as $item) {
            $total += ($item['price'] * $item['quantity']);
            $itemCount += $item['quantity'];
        }

        return [
            'total' => $total,
            'item_count' => $itemCount,
            'items' => $cart
        ];
    }

    public function checkout(array $data): array
    {
        $cart = $this->getContent();
        
        if (empty($cart)) {
            return [
                'success' => false,
                'message' => 'El carrito está vacío'
            ];
        }

        // Validar stock antes de procesar
        foreach ($cart as $item) {
            $producto = $this->producto->find($item['product_id']);
            if (!$producto || $producto->stock < $item['quantity']) {
                return [
                    'success' => false,
                    'message' => 'Stock insuficiente para algunos productos'
                ];
            }
        }

        return [
            'success' => true,
            'data' => [
                'items' => $cart,
                'total' => $this->calculateTotal($cart),
                'checkout_data' => $data
            ]
        ];
    }

    protected function calculateTotal(array $cart): float
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['price'] * $item['quantity']);
        }
        return (float) $total;
    }
}