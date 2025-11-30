<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->getReportData($request);
        return view('admin.reportes.index', $data);
    }

    public function downloadPdf(Request $request)
    {
        $data = $this->getReportData($request);
        $pdf = Pdf::loadView('admin.reportes.pdf', $data);
        return $pdf->download('reporte_ventas_' . $data['startDate']->format('Ymd') . '_' . $data['endDate']->format('Ymd') . '.pdf');
    }

    private function getReportData(Request $request)
    {
        // 1. Filtros de Fecha (Default: Mes actual)
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date'))->startOfDay() 
            : Carbon::now()->startOfMonth();
            
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date'))->endOfDay() 
            : Carbon::now()->endOfDay();

        // 2. Query Base: Solo pedidos finalizados (entregado o completado)
        $query = Pedido::whereBetween('created_at', [$startDate, $endDate])
            ->where(function($q) {
                $q->whereIn('estado', ['entregado', 'completado']);
            });

        $pedidos = $query->get();

        // 3. Métricas Generales
        $totalVentas = $pedidos->sum('total');
        $totalPedidos = $pedidos->count();
        $ticketPromedio = $totalPedidos > 0 ? $totalVentas / $totalPedidos : 0;

        // 4. Datos para Gráfica (Ventas por día)
        $ventasPorDia = $pedidos->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        })->map(function ($row) {
            return $row->sum('total');
        });

        // Rellenar días vacíos en el rango para la gráfica
        $chartLabels = [];
        $chartData = [];
        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');
            $chartData[] = $ventasPorDia->has($dateString) ? $ventasPorDia[$dateString] : 0;
        }

        // 5. Productos Más Vendidos (Query directo a pedido_detalles)
        $topProductos = \App\Models\PedidoDetalle::select(
                'productos.nombre',
                DB::raw('SUM(pedido_detalles.cantidad) as cantidad'),
                DB::raw('SUM(pedido_detalles.cantidad * pedido_detalles.precio) as total')
            )
            ->join('pedidos', 'pedidos.id', '=', 'pedido_detalles.pedido_id')
            ->join('productos', 'productos.id', '=', 'pedido_detalles.producto_id')
            ->whereBetween('pedidos.created_at', [$startDate, $endDate])
            ->whereIn('pedidos.estado', ['entregado', 'completado'])
            ->groupBy('productos.nombre')
            ->orderByDesc('cantidad')
            ->limit(5)
            ->get();

        return compact(
            'totalVentas', 
            'totalPedidos', 
            'ticketPromedio', 
            'chartLabels', 
            'chartData', 
            'topProductos',
            'startDate',
            'endDate'
        );
    }
}
