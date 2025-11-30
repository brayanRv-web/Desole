<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:cliente');
    }

    /**
     * Mostrar listado de pedidos del cliente autenticado
     */
    public function index()
    {
        $pedidos = Pedido::where('cliente_id', Auth::guard('cliente')->id())
            ->where('oculto_cliente', false) // Filter out hidden orders
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Se usa la vista cliente/pedidos.blade.php existente
        return view('cliente.pedidos', compact('pedidos'));
    }

    /**
     * Mostrar el detalle de un pedido específico
     */
    public function show($id)
    {
        $pedido = Pedido::with('detalles.producto')
            ->where('id', $id)
            ->where('cliente_id', Auth::guard('cliente')->id())
            ->firstOrFail();

        // If request expects JSON (AJAX polling), return the pedido as JSON so client can poll for estado
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [ 'pedido' => $pedido ]
            ]);
        }

        // Render the dedicated show view for normal browser requests
        return view('cliente.pedidos.show', compact('pedido'));
    }

    /**
     * Cancelar un pedido del cliente (si está pendiente)
     */
    public function cancelar($id)
    {
        $pedido = Pedido::where('id', $id)
            ->where('cliente_id', Auth::guard('cliente')->id())
            ->firstOrFail();

        if ($pedido->estado !== 'pendiente') {
            return back()->with('error', 'Solo se pueden cancelar pedidos pendientes.');
        }

        DB::beginTransaction();

        try {
            // Restaurar stock si ya fue descontado
            if ($pedido->stock_descontado) {
                $pedido->incrementarStock();
            }

            $pedido->estado = 'cancelado';
            $pedido->save();

            DB::commit();

            return back()->with('success', 'Pedido cancelado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cancelar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * Ocultar un pedido del historial del cliente (Soft Delete visual)
     */
    public function ocultar($id)
    {
        $pedido = Pedido::where('id', $id)
            ->where('cliente_id', Auth::guard('cliente')->id())
            ->firstOrFail();

        // Solo permitir ocultar si está entregado o completado
        if (!in_array($pedido->estado, ['entregado', 'completado', 'cancelado'])) {
            return back()->with('error', 'Solo se pueden eliminar del historial pedidos finalizados.');
        }

        $pedido->oculto_cliente = true;
        $pedido->save();

        return back()->with('success', 'Pedido eliminado del historial.');
    }
}
