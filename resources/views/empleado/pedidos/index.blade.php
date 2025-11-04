@extends('empleado.layout')

@section('content')
    <div class="flex flex-col space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-green-400">Pedidos</h2>
                <p class="text-gray-400">Listado de pedidos (solo lectura para empleados).</p>
            </div>
        </div>

        <div class="bg-gray-800/50 rounded-2xl border border-green-700/30 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-green-700/40 to-green-800/20 border-b border-green-600/30">
                        <tr>
                            <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">#</th>
                            <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">Teléfono</th>
                            <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">Items</th>
                            <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse($pedidos as $pedido)
                        <tr class="hover:bg-green-900/20 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $pedido->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $pedido->cliente_nombre ?? 'Cliente' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $pedido->cliente_telefono ?? '-' }}</td>
                            <td class="px-6 py-4 max-w-xs text-sm text-gray-300">
                                @if($pedido->items && is_array($pedido->items))
                                    @foreach(array_slice($pedido->items, 0, 3) as $item)
                                        <div class="truncate">{{ $item['nombre'] ?? ($item['producto_nombre'] ?? 'Ítem') }} x{{ $item['cantidad'] ?? 1 }}</div>
                                    @endforeach
                                    @if(count($pedido->items) > 3)
                                        <div class="text-xs text-gray-500">(+{{ count($pedido->items) - 3 }} más)</div>
                                    @endif
                                @else
                                    <span class="text-gray-500">Sin items</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">${{ number_format($pedido->total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ ucfirst($pedido->status) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $pedido->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
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

            <div class="p-4 border-t border-green-700/20 bg-gray-900/30">
                {{ $pedidos->links() }}
            </div>
        </div>
    </div>
@endsection

