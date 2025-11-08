<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    /**
     * Estado permitidos y sus transiciones válidas
     */
    private const ESTADOS_PERMITIDOS = [
        'pendiente' => ['preparando', 'cancelado'],
        'preparando' => ['listo', 'cancelado'],
        'listo' => ['entregado'],
        'entregado' => [],
        'cancelado' => []
    ];

    /**
     * Mostrar listado de pedidos con filtros y ordenamiento.
     */
    public function index(Request $request)
    {
        $query = Pedido::query();

        // Filtrar por estado
        if ($request->has('status') && $request->status !== 'todos') {
            $query->where('status', $request->status);
        }

        // Filtrar por fecha
        if ($request->has('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }

        // Ordenar
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        $pedidos = $query->paginate(15)->withQueryString();

        // Contadores para el sidebar
        $counts = Pedido::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('empleado.pedidos.index', compact('pedidos', 'counts'));
    }

    /**
     * Dashboard para empleado con métricas y pedidos recientes.
     */
    public function dashboard()
    {
        // Contadores por estado
        $counts = Pedido::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $pendiente = $counts['pendiente'] ?? 0;
        $preparando = $counts['preparando'] ?? 0;
        $listo = $counts['listo'] ?? 0;
        $entregado = $counts['entregado'] ?? 0;
        $cancelado = $counts['cancelado'] ?? 0;

        // Pedidos que requieren atención inmediata
        $pedidosUrgentes = Pedido::where('status', 'pendiente')
            ->orderBy('created_at', 'asc')
            ->limit(3)
            ->get();

        // Últimos pedidos actualizados
        $ultimosActualizados = Pedido::orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Productos con stock bajo
        $productosBajoStock = Producto::where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->where('status', 'activo')
            ->get();

        return view('empleado.dashboard', compact(
            'pendiente', 
            'preparando', 
            'listo',
            'entregado',
            'cancelado',
            'pedidosUrgentes',
            'ultimosActualizados',
            'productosBajoStock'
        ));
    }

    /**
     * Mostrar detalle completo de un pedido.
     */
    public function show(Pedido $pedido)
    {
        // Cargar información adicional necesaria
        $pedido->load(['cliente']);

        // Verificar stock actual de los productos
        $items = collect($pedido->items)->map(function ($item) {
            $producto = Producto::find($item['producto_id']);
            $item['stock_actual'] = $producto ? $producto->stock : 0;
            return $item;
        });

        // Obtener estados permitidos para este pedido
        $estadosPermitidos = $this->getEstadosPermitidos($pedido->status);

        return view('empleado.pedidos.show', compact('pedido', 'items', 'estadosPermitidos'));
    }

    /**
     * Actualizar estado del pedido con validaciones y manejo de stock.
     */
    public function updateStatus(Request $request, Pedido $pedido)
    {
        // Validar datos
        $data = $request->validate([
            'status' => 'required|string|in:pendiente,preparando,listo,entregado,cancelado',
            'tiempo_estimado' => 'nullable|integer|min:1|max:180',
            'notas' => 'nullable|string|max:500'
        ]);

        // Verificar si la transición de estado es válida
        if (!$this->esTransicionValida($pedido->status, $data['status'])) {
            return response()->json([
                'success' => false,
                'message' => "No se puede cambiar el estado de '{$pedido->status}' a '{$data['status']}'"
            ], 422);
        }

        $oldStatus = $pedido->status;

        DB::beginTransaction();
        try {
            // Manejar cambio a estado 'listo'
            if ($data['status'] === 'listo' && $oldStatus !== 'listo' && !$pedido->stock_descontado) {
                try {
                    $pedido->decrementarStock();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Error al decrementar stock: {$e->getMessage()}", [
                        'pedido_id' => $pedido->id,
                        'items' => $pedido->items
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ], 422);
                }
            }

            // Manejar cancelación
            if ($data['status'] === 'cancelado' && $pedido->stock_descontado) {
                try {
                    $pedido->incrementarStock();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Error al restaurar stock: {$e->getMessage()}", [
                        'pedido_id' => $pedido->id,
                        'items' => $pedido->items
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => "Error al restaurar stock: {$e->getMessage()}"
                    ], 422);
                }
            }

            // Actualizar pedido
            $pedido->update([
                'status' => $data['status'],
                'tiempo_estimado' => $data['tiempo_estimado'] ?? $pedido->tiempo_estimado,
                'notas' => $data['notas'] ?? $pedido->notas
            ]);

            DB::commit();

            // Preparar respuesta con información actualizada
            $response = [
                'success' => true,
                'message' => 'Estado del pedido actualizado correctamente',
                'pedido' => [
                    'id' => $pedido->id,
                    'status' => $pedido->status,
                    'tiempo_estimado' => $pedido->tiempo_estimado,
                    'stock_descontado' => $pedido->stock_descontado,
                    'updated_at' => $pedido->updated_at->format('Y-m-d H:i:s')
                ],
                'estados_permitidos' => $this->getEstadosPermitidos($data['status'])
            ];

            $request->session()->flash('success', $response['message']);
            return response()->json($response);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar pedido: {$e->getMessage()}", [
                'pedido_id' => $pedido->id,
                'new_status' => $data['status']
            ]);
            
            return response()->json([
                'success' => false,
                'message' => "Error al actualizar el pedido: {$e->getMessage()}"
            ], 500);
        }
    }

    /**
     * Obtener estados permitidos para un estado actual
     */
    private function getEstadosPermitidos(string $estadoActual): array
    {
        return self::ESTADOS_PERMITIDOS[$estadoActual] ?? [];
    }

    /**
     * Verificar si una transición de estado es válida
     */
    private function esTransicionValida(string $estadoActual, string $nuevoEstado): bool
    {
        if ($estadoActual === $nuevoEstado) {
            return true;
        }

        $estadosPermitidos = $this->getEstadosPermitidos($estadoActual);
        return in_array($nuevoEstado, $estadosPermitidos);
    }
}