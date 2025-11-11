<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Contracts\Services\OrderServiceInterface;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PedidoController extends Controller
{
    use JsonResponseTrait;

    public function __construct(
        protected OrderServiceInterface $orderService
    ) {}

    public function index(Request $request)
    {
        $filters = [];

        // Filtro por estado
        if ($request->filled('estado')) {
            $filters['estado'] = $request->estado;
        }

        // Filtros de fecha
        if ($request->filled('fecha_desde')) {
            $filters['fecha_desde'] = Carbon::parse($request->fecha_desde)->startOfDay();
        }
        if ($request->filled('fecha_hasta')) {
            $filters['fecha_hasta'] = Carbon::parse($request->fecha_hasta)->endOfDay();
        }

        // Obtener pedidos activos (pendientes y en preparación)
        $pedidosActivos = $this->orderService->getAll(array_merge(
            $filters,
            ['estado' => ['pendiente', 'en_preparacion']]
        ));

        // Obtener pedidos listos para entrega
        $pedidosListos = $this->orderService->getAll(array_merge(
            $filters,
            ['estado' => 'listo']
        ));

        return view('admin.pedidos.index', compact('pedidosActivos', 'pedidosListos'));
    }

    public function show(int $id)
    {
        try {
            $pedido = $this->orderService->get($id);
            
            if ($this->isAjaxRequest()) {
                return $this->successResponse('', $pedido);
            }

            return view('admin.pedidos.show', compact('pedido'));
        } catch (\Exception $e) {
            if ($this->isAjaxRequest()) {
                return $this->errorResponse($e->getMessage());
            }
            return redirect()->route('admin.pedidos.index')
                           ->with('error', 'Pedido no encontrado');
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pendiente,en_preparacion,listo,entregado,cancelado'
        ]);

        try {
            $pedido = $this->orderService->updateStatus($id, $request->status);
            return $this->successResponse('Estado actualizado correctamente', $pedido);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al actualizar el estado: ' . $e->getMessage());
        }
    }

    protected function isAjaxRequest(): bool
    {
        return request()->ajax() || request()->wantsJson();
    }
}