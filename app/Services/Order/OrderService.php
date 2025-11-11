<?php

namespace App\Services\Order;

use App\Models\Pedido;
use App\Models\Producto;
use App\Contracts\Services\OrderServiceInterface;
use App\Contracts\Services\CartServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        protected CartServiceInterface $cartService,
        protected Pedido $pedido
    ) {}

    public function create(array $data): array
    {
        try {
            $pedido = DB::transaction(function () use ($data) {
                $pedido = $this->pedido->create([
                    'cliente_id' => $data['cliente_id'],
                    'cliente_nombre' => $data['cliente_nombre'],
                    'cliente_telefono' => $data['cliente_telefono'],
                    'direccion' => $data['direccion'],
                    'total' => $data['total'],
                    'estado' => 'pendiente',
                    'items' => $data['items'],
                    'notas' => $data['notas'] ?? null
                ]);

                // Descontar stock
                $this->decrementarStock($pedido);

                return $pedido;
            });

            return $pedido->toArray();
        } catch (\Exception $e) {
            Log::error('Error creando pedido: ' . $e->getMessage());
            throw $e;
        }
    }

    public function get(int $id): array
    {
        return $this->pedido->findOrFail($id)->toArray();
    }

    public function getAll(array $filters = []): array
    {
        $query = $this->pedido->query();

        if (isset($filters['estado'])) {
            if (is_array($filters['estado'])) {
                $query->whereIn('estado', $filters['estado']);
            } else {
                $query->where('estado', $filters['estado']);
            }
        }

        if (isset($filters['cliente_id'])) {
            $query->where('cliente_id', $filters['cliente_id']);
        }

        if (isset($filters['fecha_desde'])) {
            $query->whereDate('created_at', '>=', $filters['fecha_desde']);
        }

        if (isset($filters['fecha_hasta'])) {
            $query->whereDate('created_at', '<=', $filters['fecha_hasta']);
        }

        return $query->orderBy('created_at', 'desc')->get()->toArray();
    }

    public function updateStatus(int $id, string $status): array
    {
        $pedido = $this->pedido->findOrFail($id);
        $oldStatus = $pedido->estado;

        DB::transaction(function () use ($pedido, $status, $oldStatus) {
            // Si el pedido se cancela, devolver el stock
            if ($status === 'cancelado' && $oldStatus !== 'cancelado') {
                $this->incrementarStock($pedido);
            }
            // Si el pedido estaba cancelado y se reactiva, descontar stock
            elseif ($oldStatus === 'cancelado' && $status !== 'cancelado') {
                $this->decrementarStock($pedido);
            }

            $pedido->update(['estado' => $status]);

            // Notificar al cliente si existe
            if ($pedido->cliente) {
                $pedido->cliente->notify(new \App\Notifications\PedidoStatusNotification(
                    $pedido->toArray(),
                    $oldStatus,
                    $status
                ));
            }
        });

        return $pedido->fresh()->toArray();
    }

    public function cancel(int $id): array
    {
        $pedido = $this->pedido->findOrFail($id);

        if ($pedido->estado !== 'pendiente') {
            return [
                'success' => false,
                'message' => 'Solo se pueden cancelar pedidos pendientes',
                'data' => $pedido->toArray()
            ];
        }

        try {
            DB::transaction(function () use ($pedido) {
                if ($pedido->stock_descontado) {
                    $this->incrementarStock($pedido);
                }
                $pedido->update(['estado' => 'cancelado']);
            });
            
            return [
                'success' => true,
                'message' => 'Pedido cancelado exitosamente',
                'data' => $pedido->fresh()->toArray()
            ];
        } catch (\Exception $e) {
            Log::error('Error cancelando pedido: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function decrementarStock(Pedido $pedido): void
    {
        foreach ($pedido->items as $item) {
            $producto = Producto::findOrFail($item['product_id']);
            $producto->decrement('stock', $item['quantity']);
        }
        $pedido->update(['stock_descontado' => true]);
    }

    protected function incrementarStock(Pedido $pedido): void
    {
        foreach ($pedido->items as $item) {
            $producto = Producto::findOrFail($item['product_id']);
            $producto->increment('stock', $item['quantity']);
        }
        $pedido->update(['stock_descontado' => false]);
    }
}