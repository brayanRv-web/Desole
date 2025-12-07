@extends('admin.layout')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header con número de pedido y estado -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2 flex items-center gap-2">
                <i class="fas fa-shopping-bag text-green-500"></i> Pedido #{{ $pedido->id }}
            </h1>
            <p class="text-gray-400">Detalles completos del pedido y seguimiento de estado</p>
        </div>
        <div class="px-6 py-3 rounded-lg border 
                    @if($pedido->status === 'pendiente') border-yellow-500/40 bg-yellow-900/20 text-yellow-400
                    @elseif($pedido->status === 'preparando') border-blue-500/40 bg-blue-900/20 text-blue-400
                    @elseif($pedido->status === 'listo') border-green-500/40 bg-green-900/20 text-green-400
                    @else border-gray-500/40 bg-gray-900/20 text-gray-400 @endif">
            <p class="text-gray-300 text-sm">Estado actual:</p>
            <p class="text-xl font-bold capitalize">
                {{ str_replace('_', ' ', $pedido->status) }}
            </p>
        </div>
    </div>

    <!-- Grid de información del cliente -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Información del Cliente -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg p-6 shadow-lg hover:border-green-600 transition">
            <h2 class="text-lg font-bold text-green-400 mb-6 flex items-center gap-2">
                <i class="fas fa-user-circle text-2xl"></i> Información del Cliente
            </h2>
            <div class="space-y-5">
                <div class="bg-gray-800/50 rounded-lg p-4 border-l-4 border-green-500">
                    <p class="text-gray-400 text-xs uppercase tracking-wide">Nombre del Cliente</p>
                    <p class="text-white font-bold text-xl mt-2">
                        <i class="fas fa-id-card text-green-400 mr-2"></i>
                        {{ $pedido->cliente_nombre ?? 'No disponible' }}
                    </p>
                </div>
                <div class="bg-gray-800/50 rounded-lg p-4 border-l-4 border-blue-500">
                    <p class="text-gray-400 text-xs uppercase tracking-wide">Teléfono</p>
                    <p class="text-white font-bold text-xl mt-2">
                        <i class="fas fa-phone text-blue-400 mr-2"></i>
                        {{ $pedido->cliente_telefono ?? 'No disponible' }}
                    </p>
                    @if($pedido->cliente_telefono)
                        <a href="tel:{{ $pedido->cliente_telefono }}" class="text-blue-400 text-sm hover:text-blue-300 mt-2 inline-block">
                            <i class="fas fa-external-link-alt mr-1"></i> Llamar
                        </a>
                    @endif
                </div>
                <div class="bg-gray-800/50 rounded-lg p-4 border-l-4 border-amber-500">
                    <p class="text-gray-400 text-xs uppercase tracking-wide">Dirección de Entrega</p>
                    <p class="text-white font-bold text-lg mt-2">
                        <i class="fas fa-map-marker-alt text-amber-400 mr-2"></i>
                        {{ $pedido->direccion ?? $pedido->cliente->direccion ?? 'No disponible' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Resumen del Pedido -->
        <div class="bg-gray-900 border border-gray-700 rounded-lg p-6 shadow-lg hover:border-green-600 transition">
            <h2 class="text-lg font-bold text-green-400 mb-4 flex items-center gap-2">
                <i class="fas fa-list-check"></i> Resumen del Pedido
            </h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-gray-700">
                    <p class="text-gray-400">Cantidad de items:</p>
                    <span class="text-white font-bold text-lg">{{ $pedido->detalles->count() }}</span>
                </div>
                <div class="flex justify-between items-center pb-3 border-b border-gray-700">
                    <p class="text-gray-400">Total:</p>
                    <span class="text-green-400 font-bold text-2xl">${{ number_format($pedido->total, 2) }}</span>
                </div>
                @if($pedido->created_at)
                    <div class="flex justify-between items-center">
                        <p class="text-gray-400">Fecha del pedido:</p>
                        <span class="text-white font-semibold">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sección de Productos -->
    <div class="bg-gray-900 border border-gray-700 rounded-lg p-6 shadow-lg mb-8">
        <h2 class="text-xl font-bold text-green-400 mb-6 flex items-center gap-2">
            <i class="fas fa-package"></i> Productos en el Pedido
        </h2>

        @php $detalles = $pedido->detalles ?? collect([]); @endphp
        @if($detalles->isEmpty())
            <div class="bg-gray-800 border border-dashed border-gray-600 rounded-lg p-8 text-center">
                <i class="fas fa-inbox text-gray-500 text-3xl mb-3"></i>
                <p class="text-gray-400">No hay items registrados en este pedido.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-700 bg-gray-800">
                            <th class="px-4 py-3 text-left text-gray-300 font-semibold">#ID</th>
                            <th class="px-4 py-3 text-left text-gray-300 font-semibold">Producto</th>
                            <th class="px-4 py-3 text-center text-gray-300 font-semibold">Cantidad</th>
                            <th class="px-4 py-3 text-right text-gray-300 font-semibold">Precio unitario</th>
                            <th class="px-4 py-3 text-right text-gray-300 font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalles as $detalle)
                            <tr class="border-b border-gray-700 hover:bg-gray-800 transition">
                                <td class="px-4 py-3 text-gray-400">{{ $detalle->producto_id }}</td>
                                <td class="px-4 py-3 text-white font-semibold">{{ $detalle->producto->nombre ?? 'Producto no disponible' }}</td>
                                <td class="px-4 py-3 text-center text-white">
                                    <span class="bg-green-900/40 px-3 py-1 rounded-full text-green-400 font-bold">
                                        {{ $detalle->cantidad }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-white">${{ number_format($detalle->precio, 2) }}</td>
                                <td class="px-4 py-3 text-right text-green-400 font-bold">
                                    ${{ number_format($detalle->precio * $detalle->cantidad, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Cambiar Estado del Pedido -->
    <div class="bg-gray-900 border border-gray-700 rounded-lg p-6 shadow-lg">
        <h2 class="text-lg font-bold text-green-400 mb-4 flex items-center gap-2">
            <i class="fas fa-tasks"></i> Cambiar Estado del Pedido
        </h2>

        <div class="space-y-3">
            @if($pedido->status === 'pendiente')
                <form method="POST" action="{{ route('admin.pedidos.updateEstado', $pedido) }}">
                    @csrf
                    <input type="hidden" name="status" value="preparando">
                    <button type="submit" class="btn btn-warning w-full md:w-auto flex items-center justify-center gap-2">
                        <i class="fas fa-hourglass-start"></i> Iniciar preparación
                    </button>
                </form>
                <p class="text-gray-400 text-sm mt-2">El pedido está en espera. Presiona el botón para comenzar su preparación.</p>
            @elseif($pedido->status === 'preparando')
                <form method="POST" action="{{ route('admin.pedidos.updateEstado', $pedido) }}">
                    @csrf
                    <input type="hidden" name="status" value="listo">
                    <button type="submit" class="btn btn-info w-full md:w-auto flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> Marcar como listo
                    </button>
                </form>
                <p class="text-gray-400 text-sm mt-2">El pedido está siendo preparado. Presiona el botón cuando esté listo para entrega.</p>
            @elseif($pedido->status === 'listo')
                <form method="POST" action="{{ route('admin.pedidos.updateEstado', $pedido) }}">
                    @csrf
                    <input type="hidden" name="status" value="entregado">
                    <button type="submit" class="btn btn-success w-full md:w-auto flex items-center justify-center gap-2">
                        <i class="fas fa-truck"></i> Marcar como entregado
                    </button>
                </form>
                <p class="text-gray-400 text-sm mt-2">El pedido está listo. Presiona el botón cuando sea entregado al cliente.</p>
            @else
                <div class="bg-gray-800/50 border border-gray-700 rounded-lg p-4">
                    <p class="text-gray-400 font-semibold flex items-center gap-2">
                        <i class="fas fa-check-double"></i> Este pedido ha sido entregado
                    </p>
                    <p class="text-gray-400 text-sm mt-2">No hay acciones pendientes para este pedido.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Botón de regreso -->
    <div class="mt-8 text-center">
        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline-light px-6 py-2 inline-flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Volver a pedidos
        </a>
    </div>
</div>
@endsection
