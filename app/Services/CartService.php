<?php

namespace App\Services;

use App\Models\Producto;
use App\Contracts\CartServiceInterface;
use Illuminate\Support\Facades\Session;

class CartService implements CartServiceInterface
{
    protected string $sessionKey = 'carrito';

    public function getCart(): array
    {
        return Session::get($this->sessionKey, []);
    }

    public function saveCart(array $cart): void
    {
        Session::put($this->sessionKey, $cart);
    }

    public function clear(): void
    {
        Session::forget($this->sessionKey);
    }

    public function add(int $productoId, int $cantidad): array
    {
        $producto = Producto::findOrFail($productoId);

        $cart = $this->getCart();

        if (isset($cart[$productoId])) {
            $nuevaCantidad = $cart[$productoId]['cantidad'] + $cantidad;
            if ($producto->stock < $nuevaCantidad) {
                return ['success' => false, 'message' => 'Stock insuficiente. No puedes agregar más de ' . $producto->stock . ' unidades.'];
            }
            $cart[$productoId]['cantidad'] = $nuevaCantidad;
        } else {
            if ($producto->stock < $cantidad) {
                return ['success' => false, 'message' => 'Stock insuficiente. Solo quedan ' . $producto->stock . ' unidades.'];
            }
            $cart[$productoId] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $cantidad,
                'imagen' => $producto->imagen,
                'stock_disponible' => $producto->stock
            ];
        }

        $this->saveCart($cart);

        return ['success' => true, 'cart' => $cart, 'message' => 'Producto agregado al carrito', 'producto_agregado' => $producto->nombre];
    }

    public function update(int $productoId, int $cantidad): array
    {
        $cart = $this->getCart();

        if ($cantidad <= 0) {
            if (isset($cart[$productoId])) {
                unset($cart[$productoId]);
                $this->saveCart($cart);
            }
            return ['success' => true, 'cart' => $cart, 'message' => 'Producto eliminado del carrito'];
        }

        $producto = Producto::findOrFail($productoId);
        if ($producto->stock < $cantidad) {
            return ['success' => false, 'message' => 'Stock insuficiente. Solo quedan ' . $producto->stock . ' unidades.'];
        }

        if (isset($cart[$productoId])) {
            $cart[$productoId]['cantidad'] = $cantidad;
            $this->saveCart($cart);
            return ['success' => true, 'cart' => $cart, 'message' => 'Carrito actualizado'];
        }

        return ['success' => false, 'message' => 'Producto no encontrado en el carrito'];
    }

    public function remove(int $productoId): array
    {
        $cart = $this->getCart();
        if (isset($cart[$productoId])) {
            unset($cart[$productoId]);
            $this->saveCart($cart);
        }
        return ['success' => true, 'cart' => $cart, 'message' => 'Producto eliminado del carrito'];
    }

    public function count(): int
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'cantidad')) ?: 0;
    }

    public function calculateTotal(array $cart = null): float
    {
        $cart = $cart ?? $this->getCart();
        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['precio'] * $item['cantidad']);
        }
        return (float) $total;
    }

    public function itemsForPedido(array $cart = null): array
    {
        $cart = $cart ?? $this->getCart();
        $items = [];
        foreach ($cart as $item) {
            $items[] = [
                'producto_id' => $item['id'],
                'nombre' => $item['nombre'],
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio']
            ];
        }
        return $items;
    }

    public function validateStock(array $items): bool
    {
        foreach ($items as $item) {
            if (!isset($item['producto_id']) || !isset($item['cantidad'])) {
                return false;
            }

            $producto = Producto::find($item['producto_id']);
            if (!$producto || $producto->stock < $item['cantidad']) {
                return false;
            }
        }
        return true;
    }
}
