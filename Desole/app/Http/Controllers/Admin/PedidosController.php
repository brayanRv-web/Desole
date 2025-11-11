<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Services\OrderService;
use Illuminate\Http\Request;

class PedidosController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Mostrar lista de pedidos activos (pendientes y en preparación)
     */
    public function index()
    {
        $pedidosActivos = $this->orderService->getActivePedidos();
        $pedidosListos = $this->orderService->getReadyPedidos();

        return view('admin.pedidos.index', compact('pedidosActivos', 'pedidosListos'));
    }

    /**
     * Ver detalle de un pedido
     */
    public function show(Pedido $pedido)
    {
        return view('admin.pedidos.show', compact('pedido'));
    }

    /**
     * Iniciar preparación de un pedido
     */
    public function iniciarPreparacion(Request $request, Pedido $pedido)
    {
        $result = $this->orderService->startPreparation($pedido);

        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return response()->json($result);
        } else {
            $request->session()->flash('error', $result['message']);
            return response()->json($result, 422);
        }
    }

    /**
     * Marcar pedido como listo
     */
    public function marcarListo(Request $request, Pedido $pedido)
    {
        $result = $this->orderService->complete($pedido);

        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return response()->json($result);
        } else {
            $request->session()->flash('error', $result['message']);
            return response()->json($result, 422);
        }
    }

    /**
     * Marcar pedido como entregado
     */
    public function marcarEntregado(Request $request, Pedido $pedido)
    {
        $result = $this->orderService->deliver($pedido);

        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return response()->json($result);
        } else {
            $request->session()->flash('error', $result['message']);
            return response()->json($result, 422);
        }
    }

    /**
     * Marcar pedido como cancelado (solo administradores)
     */
    public function cancelar(Request $request, Pedido $pedido)
    {
        if (!session('admin_role') || session('admin_role') !== 'Administrador') {
            $request->session()->flash('error', 'No tienes permiso para cancelar pedidos');
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para cancelar pedidos'
            ], 403);
        }

        $result = $this->orderService->cancel($pedido);

        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return response()->json($result);
        } else {
            $request->session()->flash('error', $result['message']);
            return response()->json($result, 422);
        }
    }
}