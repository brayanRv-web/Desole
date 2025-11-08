@extends('empleado.layout')

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-green-400">Pedidos</h2>
            <p class="text-gray-400">Administración de pedidos</p>
        </div>
        
        <!-- Filtros -->
        <div class="flex space-x-4">
            <select id="status-filter" 
                    class="bg-gray-800/30 border border-green-700/10 rounded-lg text-white px-3 py-2 focus:outline-none focus:border-green-500/50">
                <option value="todos">Todos los estados</option>
                <option value="pendiente">Pendientes</option>
                <option value="preparando">En preparación</option>
                <option value="listo">Listos</option>
                <option value="entregado">Entregados</option>
                <option value="cancelado">Cancelados</option>
            </select>

            <input type="date" 
                   id="date-filter"
                   class="bg-gray-800/30 border border-green-700/10 rounded-lg text-white px-3 py-2 focus:outline-none focus:border-green-500/50">
        </div>
    </div>

    <!-- Contadores -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-gray-800/50 rounded-2xl p-6 border border-yellow-600/20">
            <p class="text-sm text-gray-400">Pendientes</p>
            <p class="text-2xl font-bold text-yellow-400">{{ $counts['pendiente'] ?? 0 }}</p>
        </div>
        <div class="bg-gray-800/50 rounded-2xl p-6 border border-blue-600/20">
            <p class="text-sm text-gray-400">En Preparación</p>
            <p class="text-2xl font-bold text-blue-400">{{ $counts['preparando'] ?? 0 }}</p>
        </div>
        <div class="bg-gray-800/50 rounded-2xl p-6 border border-green-600/20">
            <p class="text-sm text-gray-400">Listos</p>
            <p class="text-2xl font-bold text-green-400">{{ $counts['listo'] ?? 0 }}</p>
        </div>
        <div class="bg-gray-800/50 rounded-2xl p-6 border border-purple-600/20">
            <p class="text-sm text-gray-400">Entregados</p>
            <p class="text-2xl font-bold text-purple-400">{{ $counts['entregado'] ?? 0 }}</p>
        </div>
        <div class="bg-gray-800/50 rounded-2xl p-6 border border-red-600/20">
            <p class="text-sm text-gray-400">Cancelados</p>
            <p class="text-2xl font-bold text-red-400">{{ $counts['cancelado'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Lista de Pedidos -->
    <div class="bg-gray-800/50 rounded-2xl border border-green-700/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-green-700/40 to-green-800/20 border-b border-green-600/30">
                    <tr>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">Items</th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">T. Est.</th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">Fecha</th>
                        <th class="relative px-6 py-4">
                            <span class="sr-only">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($pedidos as $pedido)
                    <tr class="hover:bg-green-900/20 transition-all duration-200">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-green-400">#{{ $pedido->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-white">{{ $pedido->cliente_nombre ?? 'Cliente' }}</span>
                                @if($pedido->cliente_telefono)
                                    <span class="text-sm text-gray-400">{{ $pedido->cliente_telefono }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-lg text-xs font-semibold
                                {{ $pedido->status === 'pendiente' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                {{ $pedido->status === 'preparando' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                {{ $pedido->status === 'listo' ? 'bg-green-500/20 text-green-400' : '' }}
                                {{ $pedido->status === 'entregado' ? 'bg-purple-500/20 text-purple-400' : '' }}
                                {{ $pedido->status === 'cancelado' ? 'bg-red-500/20 text-red-400' : '' }}">
                                {{ ucfirst($pedido->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            @if($pedido->items && is_array($pedido->items))
                                <div class="space-y-1">
                                    @foreach(array_slice($pedido->items, 0, 2) as $item)
                                        <div class="text-sm text-gray-300 truncate">
                                            {{ $item['nombre'] ?? ($item['producto_nombre'] ?? 'Ítem') }} x{{ $item['cantidad'] ?? 1 }}
                                        </div>
                                    @endforeach
                                    @if(count($pedido->items) > 2)
                                        <div class="text-xs text-gray-500">(+{{ count($pedido->items) - 2 }} más)</div>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500">Sin items</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-white">${{ number_format($pedido->total, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ $pedido->tiempo_estimado ? $pedido->tiempo_estimado . ' min' : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-300">{{ $pedido->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $pedido->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('empleado.pedidos.show', $pedido) }}" 
                               class="text-green-400 hover:text-green-300">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fas fa-receipt text-4xl mb-4 text-gray-600"></i>
                                <h3 class="text-lg font-semibold text-gray-400 mb-2">No hay pedidos registrados</h3>
                                <p class="text-sm text-gray-500 mb-4">Aún no hay pedidos. Se crearán cuando los clientes realicen una compra.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="p-4 border-t border-green-700/20 bg-gray-900/30">
            {{ $pedidos->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
// Filtrado de pedidos
document.getElementById('status-filter').addEventListener('change', function() {
    updateFilters();
});

document.getElementById('date-filter').addEventListener('change', function() {
    updateFilters();
});

function updateFilters() {
    const status = document.getElementById('status-filter').value;
    const date = document.getElementById('date-filter').value;
    
    let url = new URL(window.location.href);
    
    if (status && status !== 'todos') {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    
    if (date) {
        url.searchParams.set('fecha', date);
    } else {
        url.searchParams.delete('fecha');
    }
    
    window.location.href = url.toString();
}

// Establecer valores iniciales de los filtros
window.addEventListener('load', function() {
    const url = new URL(window.location.href);
    const status = url.searchParams.get('status');
    const date = url.searchParams.get('fecha');
    
    if (status) {
        document.getElementById('status-filter').value = status;
    }
    
    if (date) {
        document.getElementById('date-filter').value = date;
    }
});
</script>
@endpush

