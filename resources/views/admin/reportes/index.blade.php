@extends('admin.layout')

@section('title', 'Reporte de Ventas')

@section('content')
<div class="space-y-6">
    <!-- Header & Filtros -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Reporte de Ventas</h1>
            <p class="text-gray-400 text-sm">Resumen de ingresos y rendimiento</p>
        </div>
        
        <form action="{{ route('admin.reportes.index') }}" method="GET" class="flex flex-wrap items-end gap-3 bg-gray-800 p-3 rounded-lg border border-gray-700">
            <div>
                <label for="start_date" class="block text-xs text-gray-400 mb-1">Desde</label>
                <input type="date" id="start_date" name="start_date" 
                       class="bg-gray-700 border border-gray-600 text-white text-sm rounded px-3 py-1.5 focus:outline-none focus:border-green-500 transition"
                       value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div>
                <label for="end_date" class="block text-xs text-gray-400 mb-1">Hasta</label>
                <input type="date" id="end_date" name="end_date" 
                       class="bg-gray-700 border border-gray-600 text-white text-sm rounded px-3 py-1.5 focus:outline-none focus:border-green-500 transition"
                       value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded text-sm font-medium transition shadow-lg shadow-green-900/20 h-[34px]">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            <button type="submit" formaction="{{ route('admin.reportes.pdf') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded text-sm font-medium transition shadow-lg shadow-red-900/20 h-[34px]">
                <i class="fas fa-file-pdf mr-1"></i> PDF
            </button>
        </form>
    </div>

    <!-- Tarjetas de Resumen -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Ingresos -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                <i class="fas fa-dollar-sign text-6xl text-green-500"></i>
            </div>
            <div class="relative z-10">
                <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Ingresos Totales</p>
                <h3 class="text-3xl font-bold text-white mt-1">${{ number_format($totalVentas, 2) }}</h3>
                <div class="mt-2 text-xs text-green-400 flex items-center gap-1">
                    <i class="fas fa-arrow-up"></i>
                    <span>En el periodo seleccionado</span>
                </div>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                <i class="fas fa-shopping-bag text-6xl text-blue-500"></i>
            </div>
            <div class="relative z-10">
                <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Pedidos Completados</p>
                <h3 class="text-3xl font-bold text-white mt-1">{{ $totalPedidos }}</h3>
                <div class="mt-2 text-xs text-blue-400 flex items-center gap-1">
                    <i class="fas fa-check-circle"></i>
                    <span>Órdenes finalizadas</span>
                </div>
            </div>
        </div>

        <!-- Ticket Promedio -->
        <div class="bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-lg relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                <i class="fas fa-receipt text-6xl text-purple-500"></i>
            </div>
            <div class="relative z-10">
                <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Ticket Promedio</p>
                <h3 class="text-3xl font-bold text-white mt-1">${{ number_format($ticketPromedio, 2) }}</h3>
                <div class="mt-2 text-xs text-purple-400 flex items-center gap-1">
                    <i class="fas fa-calculator"></i>
                    <span>Promedio por venta</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Gráfico de Ventas -->
        <div class="lg:col-span-2 bg-gray-800 rounded-xl border border-gray-700 shadow-lg p-6">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <i class="fas fa-chart-area text-green-500"></i> Tendencia de Ventas
            </h3>
            <div class="relative h-80 w-full">
                <canvas id="ventasChart"></canvas>
            </div>
        </div>

        <!-- Top Productos -->
        <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-lg flex flex-col">
            <div class="p-6 border-b border-gray-700">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-trophy text-yellow-500"></i> Top 5 Productos
                </h3>
            </div>
            <div class="flex-1 overflow-auto p-0">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-900/50 text-gray-400 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3 font-medium">Producto</th>
                            <th class="px-6 py-3 font-medium text-center">Cant.</th>
                            <th class="px-6 py-3 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($topProductos as $prod)
                            <tr class="hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 text-sm text-gray-200">
                                    {{ Str::limit($prod['nombre'], 20) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-center font-bold text-white">
                                    {{ $prod['cantidad'] }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right text-green-400 font-medium">
                                    ${{ number_format($prod['total'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">
                                    <i class="fas fa-inbox text-2xl mb-2 block opacity-50"></i>
                                    Sin datos en este periodo
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script>
    // Configuración global de Chart.js para tema oscuro
    Chart.defaults.global.defaultFontFamily = 'Inter, system-ui, sans-serif';
    Chart.defaults.global.defaultFontColor = '#9ca3af'; // gray-400

    var ctx = document.getElementById("ventasChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: "Ventas",
                lineTension: 0.4, // Curva más suave
                backgroundColor: "rgba(22, 163, 74, 0.1)", // green-600 con opacidad
                borderColor: "#16a34a", // green-600
                pointRadius: 4,
                pointBackgroundColor: "#16a34a",
                pointBorderColor: "#1f2937", // gray-800
                pointHoverRadius: 6,
                pointHoverBackgroundColor: "#22c55e", // green-500
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: {!! json_encode($chartData) !!},
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: { left: 10, right: 25, top: 25, bottom: 0 }
            },
            scales: {
                xAxes: [{
                    time: { unit: 'date' },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: { maxTicksLimit: 7 }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        callback: function(value) { return '$' + number_format(value); }
                    },
                    gridLines: {
                        color: "rgba(75, 85, 99, 0.2)", // gray-600 con opacidad
                        zeroLineColor: "rgba(75, 85, 99, 0.2)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: { display: false },
            tooltips: {
                backgroundColor: "#1f2937", // gray-800
                bodyFontColor: "#f3f4f6", // gray-100
                titleFontColor: '#9ca3af', // gray-400
                titleFontSize: 14,
                borderColor: '#374151', // gray-700
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });

    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
@endpush
