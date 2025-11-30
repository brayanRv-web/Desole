<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\Producto;
use App\Contracts\OrderServiceInterface;
use App\Contracts\CartServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class OrderService implements OrderServiceInterface
{
    protected CartServiceInterface $cartService;

    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * ✅ Crear un nuevo pedido
     */
    public function create(array $data, array $cart): Pedido
    {
        // Validar orden y stock
        $this->validateOrder($data, $cart);

        return DB::transaction(function () use ($data, $cart) {
            $col = Schema::hasColumn('pedidos', 'status') ? 'status' : (Schema::hasColumn('pedidos', 'estado') ? 'estado' : 'status');

            $payload = [
                'cliente_id' => $data['cliente_id'] ?? null,
                'cliente_nombre' => $data['cliente_nombre'] ?? 'Cliente',
                'cliente_telefono' => $data['cliente_telefono'] ?? null,
                'direccion' => $data['direccion'] ?? null,
                'total' => $this->cartService->calculateTotal($cart),
                'notas' => $data['notas'] ?? null,
            ];

            $payload[$col] = 'pendiente';

            $pedido = Pedido::create($payload);

            // Guardar detalles en tabla relacional
            foreach ($cart as $item) {
                \App\Models\PedidoDetalle::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio']
                ]);
            }

            // Descontar stock si corresponde
            if (method_exists($pedido, 'decrementarStock')) {
                $pedido->decrementarStock();
            }

            // Vaciar carrito
            $this->cartService->clear();

            return $pedido;
        });
    }

    /**
     * ✅ Validar que el pedido sea válido antes de crearlo
     */
    public function validateOrder(array $data, array $cart): void
    {
        // Validar cliente
        if (empty($data['cliente_id']) && empty($data['cliente_nombre'])) {
            throw new \Exception('Faltan datos del cliente.');
        }

        // Validar carrito
        $this->validateCartNotEmpty($cart);

        // Validar stock
        $stockValidation = $this->validateStock($cart);
        if (!$stockValidation['success']) {
            throw new \Exception($stockValidation['message']);
        }
    }

    /**
     * ✅ Cancelar un pedido
     */
    public function cancel(Pedido $pedido): array
    {
        $col = Schema::hasColumn('pedidos', 'status') ? 'status' : (Schema::hasColumn('pedidos', 'estado') ? 'estado' : 'status');

        if (($pedido->{$col} ?? null) !== 'pendiente') {
            return [
                'success' => false,
                'message' => 'Solo se pueden cancelar pedidos pendientes.',
            ];
        }

        try {
            DB::transaction(function () use ($pedido, $col) {
                if (property_exists($pedido, 'stock_descontado') && $pedido->stock_descontado) {
                    if (method_exists($pedido, 'incrementarStock')) {
                        $pedido->incrementarStock();
                    }
                }

                $pedido->update([$col => 'cancelado']);
            });

            return [
                'success' => true,
                'message' => 'Pedido cancelado correctamente.',
            ];
        } catch (\Exception $e) {
            Log::error('Error cancelando pedido: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al cancelar el pedido: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * ✅ Obtener detalles de un pedido
     */
    public function getOrderDetails(Pedido $pedido): array
    {
        // Cargar relación si no existe
        if (!$pedido->relationLoaded('detalles')) {
            $pedido->load('detalles.producto');
        }

        $items = $pedido->detalles->map(function ($detalle) {
            return [
                'producto_id' => $detalle->producto_id,
                'nombre' => $detalle->producto->nombre ?? 'Producto eliminado',
                'cantidad' => $detalle->cantidad,
                'precio' => $detalle->precio,
                'imagen' => $detalle->producto->imagen ?? null
            ];
        })->toArray();

        return [
            'id' => $pedido->id,
            'cliente' => $pedido->cliente_nombre,
            'total' => $pedido->total,
            'status' => $pedido->status,
            'items' => $items,
            'fecha' => $pedido->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * ✅ Validar stock de los productos
     */
    public function validateStock(array $items): array
    {
        foreach ($items as $item) {
            $producto = Producto::find($item['id']);
            if (!$producto) {
                return [
                    'success' => false,
                    'message' => "El producto con ID {$item['id']} no existe.",
                ];
            }

            if ($producto->stock < $item['cantidad']) {
                return [
                    'success' => false,
                    'message' => "No hay suficiente stock de {$producto->nombre}.",
                ];
            }
        }

        return ['success' => true, 'message' => 'Stock validado correctamente.'];
    }

    /**
     * ✅ Obtener pedidos activos (pendientes o preparando)
     */
    public function getActivePedidos(): Collection
    {
        $col = Schema::hasColumn('pedidos', 'status') ? 'status' : (Schema::hasColumn('pedidos', 'estado') ? 'estado' : 'status');

        return Pedido::whereIn($col, ['pendiente', 'preparando'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * ✅ Obtener pedidos listos o entregados
     */
    public function getReadyPedidos(): Collection
    {
        $col = Schema::hasColumn('pedidos', 'status') ? 'status' : (Schema::hasColumn('pedidos', 'estado') ? 'estado' : 'status');

        return Pedido::whereIn($col, ['listo', 'entregado'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * ✅ Validar que el carrito no esté vacío
     */
    public function validateCartNotEmpty(array $cart): void
    {
        if (empty($cart)) {
            throw new \Exception('El carrito no puede estar vacío.');
        }
    }
}
