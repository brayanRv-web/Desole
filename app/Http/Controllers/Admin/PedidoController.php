<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    use JsonResponseTrait;

    public function index()
    {
        $pedidos = Pedido::orderBy('created_at', 'desc')->get();
        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        $pedido->load(['cliente']);
        return $this->successResponse('', ['pedido' => $pedido]);
    }

    public function updateEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_proceso,completado,cancelado'
        ]);

        try {
            DB::transaction(function () use ($pedido, $request) {
                // Si el pedido se cancela, devolver el stock
                if ($request->estado === 'cancelado' && $pedido->estado !== 'cancelado') {
                    $pedido->incrementarStock();
                }

                // Si el pedido estaba cancelado y se reactiva, descontar stock
                if ($pedido->estado === 'cancelado' && $request->estado !== 'cancelado') {
                    $pedido->decrementarStock();
                }

                $pedido->update([
                    'estado' => $request->estado
                ]);
            });

            return $this->successResponse('Estado actualizado correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al actualizar el estado: ' . $e->getMessage());
        }
    }
}