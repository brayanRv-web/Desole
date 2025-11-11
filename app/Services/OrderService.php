<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\Producto;
use App\Contracts\OrderServiceInterface;
use App\Contracts\CartServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService implements OrderServiceInterface
{
    protected CartServiceInterface $cartService;

    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Validar disponibilidad de productos para un pedido
     */
    public function validateStock(array $items): bool
    {
        foreach ($items as $item) {
            $producto = Producto::find($item['id']);
            if (!$producto || $producto->stock < $item['cantidad']) {
                return false;
            }
        }
        return true;
    }

    /**
     * Crear un nuevo pedido
     */
    public function create(array $data, array $cartItems): Pedido
    {
        if (!$this->validateStock($cartItems)) {
            throw new \Exception('Algunos productos no están disponibles en la cantidad solicitada.');
        }

        return DB::transaction(function () use ($data, $cartItems) {
            $pedido = Pedido::create([
                'cliente_id' => $data['cliente_id'],
                'cliente_nombre' => $data['cliente_nombre'],
                'cliente_telefono' => $data['cliente_telefono'],
                'direccion' => $data['direccion'],
                'total' => $this->cartService->calculateTotal($cartItems),
                'estado' => 'pendiente',
                'items' => $this->cartService->itemsForPedido($cartItems),
                'notas' => $data['notas'] ?? null
            ]);

            // Descontar stock
            $pedido->decrementarStock();

            // Limpiar carrito después de crear el pedido
            $this->cartService->clear();

            return $pedido;
        });
    }

    /**
     * Cancelar un pedido
     */
    public function cancel(Pedido $pedido): array
    {
        if ($pedido->estado !== 'pendiente') {
            return [
                'success' => false,
                'message' => 'Solo se pueden cancelar pedidos pendientes'
            ];
        }

        try {
            DB::transaction(function () use ($pedido) {
                if ($pedido->stock_descontado) {
                    $pedido->incrementarStock();
                }
                $pedido->update(['estado' => 'cancelado']);
            });
            
            return [
                'success' => true,
                'message' => 'Pedido cancelado exitosamente'
            ];
        } catch (\Exception $e) {
            Log::error('Error cancelando pedido: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al cancelar el pedido: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtener pedidos activos (pendientes y en proceso)
     */
    public function getActivePedidos(): array
    {
        return Pedido::whereIn('estado', ['pendiente', 'en_proceso'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * Obtener pedidos listos para entregar
     */
    public function getReadyPedidos(): array
    {
        return Pedido::where('estado', 'completado')
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }

    /**
     * Validar que el carrito no esté vacío
     */
    public function validateCartNotEmpty(array $cart): bool
    {
        return !empty($cart);
    }
}