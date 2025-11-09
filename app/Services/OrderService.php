<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class OrderService
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Validar disponibilidad de productos para un pedido
     */
    public function validateStock(array $items): array
    {
        foreach ($items as $item) {
            $producto = Producto::find($item['id']);
            if (!$producto || $producto->stock < $item['cantidad']) {
                return [
                    'success' => false,
                    'message' => 'El producto "' . ($producto->nombre ?? $item['nombre']) . '" ya no está disponible en la cantidad solicitada.'
                ];
            }
        }

        return ['success' => true];
    }

    /**
     * Crear un nuevo pedido
     */
    public function create(array $data, array $cartItems): ?Pedido
    {
        // Validar stock antes de procesar
        $stockValidation = $this->validateStock($cartItems);
        if (!$stockValidation['success']) {
            throw new \Exception($stockValidation['message']);
        }

        return DB::transaction(function () use ($data, $cartItems) {
            $pedido = Pedido::create([
                'cliente_id' => $data['cliente_id'],
                'cliente_nombre' => $data['cliente_nombre'],
                'cliente_telefono' => $data['cliente_telefono'],
                'direccion' => $data['direccion'],
                'total' => $this->cartService->calculateTotal($cartItems),
                'status' => 'pendiente',
                'items' => $this->cartService->itemsForPedido($cartItems),
            ]);

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
        if ($pedido->status !== 'pendiente') {
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

                $pedido->update(['status' => 'cancelado']);
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
     * Marcar pedido como en preparación
     */
    public function startPreparation(Pedido $pedido): array
    {
        if ($pedido->status !== 'pendiente') {
            return [
                'success' => false,
                'message' => 'Solo se pueden preparar pedidos pendientes'
            ];
        }

        try {
            DB::transaction(function () use ($pedido) {
                $pedido->decrementarStock();
                $pedido->update([
                    'status' => 'en_preparacion',
                    'tiempo_estimado' => now()->addMinutes(30)
                ]);
            });

            return [
                'success' => true,
                'message' => 'Pedido en preparación'
            ];

        } catch (\Exception $e) {
            Log::error('Error al iniciar preparación: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al iniciar preparación: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Marcar pedido como listo
     */
    public function complete(Pedido $pedido): array
    {
        if ($pedido->status !== 'en_preparacion') {
            return [
                'success' => false,
                'message' => 'Solo se pueden completar pedidos en preparación'
            ];
        }

        try {
            $pedido->update(['status' => 'listo']);

            return [
                'success' => true,
                'message' => 'Pedido marcado como listo'
            ];

        } catch (\Exception $e) {
            Log::error('Error al completar pedido: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al completar pedido: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Marcar pedido como entregado
     */
    public function deliver(Pedido $pedido): array
    {
        if ($pedido->status !== 'listo') {
            return [
                'success' => false,
                'message' => 'Solo se pueden entregar pedidos listos'
            ];
        }

        try {
            $pedido->update(['status' => 'entregado']);

            return [
                'success' => true,
                'message' => 'Pedido marcado como entregado'
            ];

        } catch (\Exception $e) {
            Log::error('Error al marcar pedido como entregado: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al marcar pedido como entregado: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtener pedidos pendientes y en preparación ordenados por fecha
     */
    public function getActivePedidos(): Collection
    {
        return Pedido::whereIn('status', ['pendiente', 'en_preparacion'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Obtener pedidos listos para entregar
     */
    public function getReadyPedidos(): Collection
    {
        return Pedido::where('status', 'listo')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}