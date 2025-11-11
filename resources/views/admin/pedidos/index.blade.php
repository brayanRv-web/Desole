@extends('admin.layout')

@section('content')
<div class="container px-6 mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-semibold text-gray-200">Gestión de Pedidos</h1>
        <p class="mt-2 text-gray-400">Administra y controla todos los pedidos de la plataforma</p>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="mb-6 p-4 bg-zinc-800 rounded-lg">
        <form id="filtros-form" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Estado</label>
                <select name="estado" class="w-full bg-zinc-700 text-gray-200 rounded-md border-gray-600">
                    <option value="">Todos</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="en_preparacion">En Preparación</option>
                    <option value="listo">Listo</option>
                    <option value="entregado">Entregado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Fecha Desde</label>
                <input type="date" name="fecha_desde" class="w-full bg-zinc-700 text-gray-200 rounded-md border-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" class="w-full bg-zinc-700 text-gray-200 rounded-md border-gray-600">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Pedidos Activos -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-200 mb-4">Pedidos Activos</h2>
        <div class="bg-zinc-800 overflow-hidden rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-zinc-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Pedido #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-zinc-800 divide-y divide-gray-700">
                        @forelse($pedidosActivos as $pedido)
                        <tr class="hover:bg-zinc-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-200">
                                #{{ $pedido['id'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-200">
                                            {{ $pedido['cliente_nombre'] }}
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            {{ $pedido['cliente_telefono'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($pedido['estado'] === 'pendiente') bg-yellow-200 text-yellow-800
                                    @elseif($pedido['estado'] === 'en_preparacion') bg-blue-200 text-blue-800
                                    @elseif($pedido['estado'] === 'listo') bg-green-200 text-green-800
                                    @else bg-gray-200 text-gray-800 @endif">
                                    {{ ucfirst($pedido['estado']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-200">
                                ${{ number_format($pedido['total'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ \Carbon\Carbon::parse($pedido['created_at'])->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button 
                                    onclick="verDetalles({{ $pedido['id'] }})"
                                    class="text-green-400 hover:text-green-300 mx-2">
                                    Ver
                                </button>
                                @if($pedido['estado'] === 'pendiente')
                                <button 
                                    onclick="cambiarEstado({{ $pedido['id'] }}, 'en_preparacion')"
                                    class="text-blue-400 hover:text-blue-300 mx-2">
                                    Preparar
                                </button>
                                @elseif($pedido['estado'] === 'en_preparacion')
                                <button 
                                    onclick="cambiarEstado({{ $pedido['id'] }}, 'listo')"
                                    class="text-green-400 hover:text-green-300 mx-2">
                                    Listo
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-400">
                                No hay pedidos activos en este momento
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pedidos Listos -->
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-200 mb-4">Pedidos Listos para Entrega</h2>
        <div class="bg-zinc-800 overflow-hidden rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-zinc-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Pedido #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Tiempo de Espera
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-zinc-800 divide-y divide-gray-700">
                        @forelse($pedidosListos as $pedido)
                        <tr class="hover:bg-zinc-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-200">
                                #{{ $pedido['id'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-200">
                                            {{ $pedido['cliente_nombre'] }}
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            {{ $pedido['cliente_telefono'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-200">
                                ${{ number_format($pedido['total'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ \Carbon\Carbon::parse($pedido['created_at'])->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button 
                                    onclick="verDetalles({{ $pedido['id'] }})"
                                    class="text-green-400 hover:text-green-300 mx-2">
                                    Ver
                                </button>
                                <button 
                                    onclick="cambiarEstado({{ $pedido['id'] }}, 'entregado')"
                                    class="text-blue-400 hover:text-blue-300 mx-2">
                                    Entregado
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                                No hay pedidos listos para entrega
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalles del Pedido -->
<div id="modal-detalles" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-zinc-800 rounded-lg w-full max-w-2xl mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-200">Detalles del Pedido #<span id="pedido-id"></span></h3>
                <button onclick="cerrarModal()" class="text-gray-400 hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="pedido-detalles" class="space-y-4">
                <!-- Los detalles se cargarán dinámicamente -->
            </div>
        </div>
    </div>
</div>

    fetch(`/admin/pedidos/${pedidoId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('pedido-id').textContent = data.id;
            
            const detalles = document.getElementById('pedido-detalles');
            detalles.innerHTML = `
                <div class="grid grid-cols-2 gap-4 text-gray-300">
                    <div>
                        <p class="text-sm text-gray-400">Cliente</p>
                        <p class="font-medium">${data.cliente_nombre}</p>
                        <p class="text-sm">${data.cliente_telefono}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Fecha del Pedido</p>
                        <p class="font-medium">${new Date(data.created_at).toLocaleString()}</p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <p class="text-sm text-gray-400 mb-2">Productos</p>
                    <div class="space-y-2">
                        ${data.items.map(item => `
                            <div class="flex justify-between items-center py-2 border-b border-gray-700">
                                <div>
                                    <p class="font-medium text-gray-200">${item.nombre}</p>
                                    <p class="text-sm text-gray-400">Cantidad: ${item.cantidad}</p>
                                </div>
                                <p class="text-gray-200">$${(item.precio * item.cantidad).toFixed(2)}</p>
                            </div>
                        `).join('')}
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <div class="flex justify-between items-center">
                        <p class="text-lg font-semibold text-gray-200">Total</p>
                        <p class="text-lg font-semibold text-green-400">$${data.total.toFixed(2)}</p>
                    </div>
                </div>
                
                ${data.notas ? `
                    <div class="mt-4 pt-4 border-t border-gray-700">
                        <p class="text-sm text-gray-400 mb-2">Notas</p>
                        <p class="text-gray-300">${data.notas}</p>
                    </div>
                ` : ''}
            `;
            
            document.getElementById('modal-detalles').classList.remove('hidden');
            document.getElementById('modal-detalles').classList.add('flex');
        });
}

function cerrarModal() {
    document.getElementById('modal-detalles').classList.add('hidden');
    document.getElementById('modal-detalles').classList.remove('flex');
}

function cambiarEstado(pedidoId, nuevoEstado) {
    fetch(`/admin/pedidos/${pedidoId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: nuevoEstado })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recargar la página para mostrar los cambios
            window.location.reload();
        } else {
            alert('Error al actualizar el estado: ' + data.message);
        }
    });
}

// Cerrar modal al hacer clic fuera de él
document.getElementById('modal-detalles').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});

// Manejar filtros
document.getElementById('filtros-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const params = new URLSearchParams(formData);
    window.location.href = `${window.location.pathname}?${params.toString()}`;
});
</script>
@endpush
@endsection

@push('scripts')
<script>
function verDetalles(pedidoId) {
