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
        $pedido = Pedido::where('id', $id)
            ->where('cliente_id', Auth::guard('cliente')->id())
            ->firstOrFail();

        // Obtener los items del pedido (guardados como JSON)
        $items = $pedido->items ?? [];

        // Obtener productos asociados
        $productoIds = collect($items)->pluck('producto_id')->filter()->toArray();
        $productos = Producto::whereIn('id', $productoIds)->get()->keyBy('id');

        // If request expects JSON (AJAX polling), return the pedido as JSON so client can poll for estado
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => [ 'pedido' => $pedido ]
            ]);
        }

        // Render the dedicated show view for normal browser requests
        return view('cliente.pedidos.show', compact('pedido', 'productos'));
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
            $pedido->estado = 'cancelado';
            $pedido->save();

            // Restaurar stock si ya fue descontado
            if ($pedido->stock_descontado) {
                foreach ($pedido->items ?? [] as $item) {
                    if (!empty($item['producto_id']) && !empty($item['cantidad'])) {
                        $producto = Producto::find($item['producto_id']);
                        if ($producto) {
                            $producto->increment('stock', $item['cantidad']);
                        }
                    }
                }

                $pedido->stock_descontado = false;
                $pedido->save();
            }

            DB::commit();

            return back()->with('success', 'Pedido cancelado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cancelar el pedido: ' . $e->getMessage());
        }
    }
}
