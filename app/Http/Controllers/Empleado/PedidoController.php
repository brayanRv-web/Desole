<?php

namespace App\Http\Controllers\Empleado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoController extends Controller
{
    /**
     * Mostrar listado de pedidos (solo lectura para empleados).
     */
    public function index(Request $request)
    {
        $pedidos = Pedido::orderBy('created_at', 'desc')->paginate(15);

        return view('empleado.pedidos.index', compact('pedidos'));
    }

    /**
     * Dashboard para empleado: contadores y últimos 5 pedidos.
     */
    public function dashboard()
    {
        $counts = Pedido::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $pendiente = $counts['pendiente'] ?? 0;
        $preparando = $counts['preparando'] ?? 0;
        $listo = $counts['listo'] ?? 0;

        $ultimos = Pedido::orderBy('created_at', 'desc')->limit(5)->get();

        return view('empleado.dashboard', compact('pendiente', 'preparando', 'listo', 'ultimos'));
    }

    /**
     * Mostrar detalle de un pedido (solo lectura).
     */
    public function show(Pedido $pedido)
    {
        return view('empleado.pedidos.show', compact('pedido'));
    }

    // (Opcional) show() se puede agregar más adelante si se solicita.
}
