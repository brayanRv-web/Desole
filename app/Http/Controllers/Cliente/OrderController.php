<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Contracts\OrderServiceInterface;
use App\Contracts\CartServiceInterface;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderServiceInterface $orderService;
    protected CartServiceInterface $cartService;

    public function __construct(
        OrderServiceInterface $orderService,
        CartServiceInterface $cartService
    ) {
        $this->orderService = $orderService;
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cliente = Auth::guard('cliente')->user();
        $pedidos = Pedido::where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cliente.pedidos', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        if ($pedido->cliente_id !== Auth::guard('cliente')->user()->id) {
            abort(403, 'No tienes permiso para ver este pedido');
        }

        $detalles = $this->orderService->getOrderDetails($pedido);

        return view('cliente.pedidos.show', $detalles);
    }

    public function store(Request $request): JsonResponse
    {
        $cliente = Auth::guard('cliente')->user();
        $carrito = $this->cartService->getCart();

        if (empty($carrito)) {
            return response()->json([
                'success' => false,
                'message' => 'El carrito está vacío'
            ], 422);
        }

        try {
            $pedido = $this->orderService->create([
                'cliente_id' => $cliente->id,
                'cliente_nombre' => $cliente->nombre ?? ($request->cliente_nombre ?? null),
                'cliente_telefono' => $cliente->telefono ?? ($request->cliente_telefono ?? null),
                'direccion' => $cliente->direccion ?? ($request->direccion ?? null),
            ], $carrito);

            return response()->json([
                'success' => true,
                'message' => 'Pedido creado correctamente. Número de pedido: #' . $pedido->id,
                'pedido_id' => $pedido->id,
                'redirect_url' => route('cliente.pedidos')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancel(Request $request, Pedido $pedido): JsonResponse
    {
        if ($pedido->cliente_id !== Auth::guard('cliente')->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para cancelar este pedido'
            ], 403);
        }

        $result = $this->orderService->cancel($pedido);

        if ($result['success']) {
            $request->session()->flash('success', $result['message']);
            return response()->json($result);
        }

        $request->session()->flash('error', $result['message']);
        return response()->json($result, 422);
    }
}