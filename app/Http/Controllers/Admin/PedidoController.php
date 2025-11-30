<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    use JsonResponseTrait;

    public function index()
    {
        // Detectar si la columna en la base de datos es `status` o `estado`
        $col = Schema::hasColumn('pedidos', 'status') ? 'status' : (Schema::hasColumn('pedidos', 'estado') ? 'estado' : 'status');

        // Pedidos activos (pendientes o en preparación) - eager load cliente
        $pedidosActivos = Pedido::with('cliente')
            ->whereIn($col, ['pendiente', 'preparando'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($pedido) {
                // Ensure cliente_nombre is available for the view
                $pedido->cliente_nombre = $pedido->cliente->nombre ?? $pedido->cliente_nombre ?? 'Cliente';
                return $pedido;
            });

        // Pedidos listos para entrega
        $pedidosListos = Pedido::with('cliente')
            ->where($col, 'listo')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($pedido) {
                $pedido->cliente_nombre = $pedido->cliente->nombre ?? $pedido->cliente_nombre ?? 'Cliente';
                return $pedido;
            });

        // Todos los pedidos para otra sección opcional (eager load cliente)
        $pedidos = Pedido::with('cliente')->orderBy('created_at', 'desc')->get()->map(function ($pedido) {
            $pedido->cliente_nombre = $pedido->cliente->nombre ?? $pedido->cliente_nombre ?? 'Cliente';
            return $pedido;
        });

        return view('admin.pedidos.index', compact('pedidosActivos', 'pedidosListos', 'pedidos'));
    }

    public function show(Pedido $pedido)
    {
        $pedido->load(['cliente', 'detalles.producto']);

        // Asegurar que los datos del cliente estén disponibles
        if (!$pedido->cliente_nombre && $pedido->cliente) {
            $pedido->cliente_nombre = $pedido->cliente->nombre;
        }
        if (!$pedido->cliente_telefono && $pedido->cliente) {
            $pedido->cliente_telefono = $pedido->cliente->telefono;
        }

        // If the request expects JSON (AJAX/API), return JSON. Otherwise render the admin blade view.
        if (request()->wantsJson() || request()->ajax()) {
            return $this->successResponse('', ['pedido' => $pedido]);
        }

        return view('admin.pedidos.show', compact('pedido'));
    }

    public function updateEstado(Request $request, Pedido $pedido)
    {
        try {
            // Accept either 'status' or 'estado' from different views
            $newEstado = $request->input('status') ?? $request->input('estado');

            // Validate the status value
            $allowed = ['pendiente', 'preparando', 'listo', 'entregado', 'cancelado'];
            if (!$newEstado || !in_array($newEstado, $allowed, true)) {
                return redirect()->back()->with('error', 'Estado inválido o no proporcionado');
            }

            Log::info("Actualizando pedido {$pedido->id} a estado {$newEstado}");

            // Update using DB query with 'estado' column (the only one that exists)
            DB::table('pedidos')
                ->where('id', $pedido->id)
                ->update(['estado' => $newEstado]);

            // Refresh the model to get the latest data
            $pedido->refresh();

            Log::info("Pedido {$pedido->id} actualizado a {$newEstado}. Estado actual en DB: {$pedido->estado}");

            return redirect()->back()->with('success', 'Estado actualizado correctamente');
        } catch (\Exception $e) {
            Log::error("Error al actualizar pedido {$pedido->id}: {$e->getMessage()}");
            return redirect()->back()->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }
    public function checkNewOrders(Request $request)
    {
        $lastId = $request->input('last_id', 0);
        
        // Buscar pedidos nuevos con ID mayor al último conocido
        $newOrders = Pedido::where('id', '>', $lastId)
            ->where('estado', 'pendiente') // Solo notificar pendientes
            ->count();
            
        $latestOrder = Pedido::latest('id')->first();
        $currentMaxId = $latestOrder ? $latestOrder->id : 0;

        return response()->json([
            'new_orders_count' => $newOrders,
            'latest_id' => $currentMaxId,
            'has_new' => $newOrders > 0
        ]);
    }
}