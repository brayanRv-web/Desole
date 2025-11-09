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
     * Mostrar listado de pedidos del cliente
     */
    public function index()
    {
        $pedidos = Pedido::where('cliente_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cliente.pedidos.index', compact('pedidos'));
    }

    /**
     * Ver detalle de un pedido
     */
    public function show(Pedido $pedido)
    {
        if ($pedido->cliente_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este pedido');
        }

        // Obtener productos relacionados con el pedido
        $items = $pedido->items ?? [];
        $productoIds = collect($items)->pluck('producto_id')->filter()->toArray();
        $productos = Producto::whereIn('id', $productoIds)->get()->keyBy('id');

        return view('cliente.pedidos.show', compact('pedido', 'productos'));
    }

    /**
     * Cancelar un pedido
     */
    public function cancelar(Pedido $pedido)
    {
        if ($pedido->cliente_id !== Auth::id()) {
            abort(403, 'No tienes permiso para cancelar este pedido');
        }

        // Solo se pueden cancelar pedidos pendientes
        if ($pedido->status !== 'pendiente') {
            return back()->with('error', 'Solo se pueden cancelar pedidos pendientes');
        }

        DB::beginTransaction();
        try {
            // Actualizar estado y devolver stock
            $pedido->status = 'cancelado';
            $pedido->save();

            // Incrementar stock de los productos
            if ($pedido->stock_descontado) {
                foreach ($pedido->items ?? [] as $item) {
                    if (isset($item['producto_id'], $item['cantidad'])) {
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
            return back()->with('success', 'Pedido cancelado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cancelar el pedido: ' . $e->getMessage());
        }
    }
}