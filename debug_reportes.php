<?php

use App\Models\Pedido;
use Carbon\Carbon;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate Controller Logic
$startDate = Carbon::now()->startOfMonth();
$endDate = Carbon::now()->endOfDay();

echo "Rango: " . $startDate->toDateTimeString() . " - " . $endDate->toDateTimeString() . "\n";

$query = Pedido::whereBetween('created_at', [$startDate, $endDate])
    ->where(function($q) {
        $q->whereIn('estado', ['entregado', 'completado']);
    });

$pedidos = $query->get();

echo "Pedidos encontrados: " . $pedidos->count() . "\n";

if ($pedidos->count() > 0) {
    foreach ($pedidos as $p) {
        echo "ID: {$p->id}, Estado: {$p->estado}, Total: {$p->total}, Fecha: {$p->created_at}\n";
    }
} else {
    echo "No hay pedidos entregados/completados en este rango.\n";
    
    // Check if there are ANY orders at all
    $allPedidos = Pedido::whereBetween('created_at', [$startDate, $endDate])->get();
    echo "Total pedidos (cualquier estado) en rango: " . $allPedidos->count() . "\n";
    foreach ($allPedidos as $p) {
         echo "ID: {$p->id}, Estado: {$p->estado}\n";
    }
}

// Chart Data Logic
$ventasPorDia = $pedidos->groupBy(function($date) {
    return Carbon::parse($date->created_at)->format('Y-m-d');
})->map(function ($row) {
    return $row->sum('total');
});

echo "\nVentas por dia (Raw):\n";
print_r($ventasPorDia->toArray());
