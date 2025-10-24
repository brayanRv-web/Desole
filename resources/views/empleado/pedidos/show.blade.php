@extends('empleado.layout')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-green-400">Detalle del Pedido #{{ $pedido->id }}</h2>
                <p class="text-gray-400">Información del pedido</p>
            </div>
            <a href="{{ route('empleado.pedidos.index') }}" class="text-sm text-gray-300">Volver a pedidos</a>
        </div>

        <div class="bg-gray-800/50 rounded-2xl p-6 border border-green-700/20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-gray-400">Cliente</div>
                    <div class="font-medium text-white">{{ $pedido->cliente_nombre ?? 'Cliente' }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-400">Teléfono</div>
                    <div class="font-medium text-white">{{ $pedido->cliente_telefono ?? '-' }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-sm text-gray-400">Dirección</div>
                    <div class="font-medium text-white">{{ $pedido->direccion ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-400">Total</div>
                    <div class="font-medium text-white">${{ number_format($pedido->total,2) }}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-400">Estado</div>
                    <div class="font-medium text-white">{{ ucfirst($pedido->status) }}</div>
                </div>
            </div>

            <hr class="my-4 border-gray-700/40">

            <div>
                <h4 class="text-sm text-gray-400 mb-2">Items</h4>
                @if($pedido->items && is_array($pedido->items))
                    <ul class="space-y-2">
                        @foreach($pedido->items as $item)
                            <li class="flex justify-between">
                                <div class="text-gray-200">{{ $item['nombre'] ?? ($item['producto_nombre'] ?? 'Ítem') }} x{{ $item['cantidad'] ?? 1 }}</div>
                                <div class="text-gray-300">${{ number_format(($item['precio'] ?? 0) * ($item['cantidad'] ?? 1),2) }}</div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-gray-500">No hay items registrados.</div>
                @endif
            </div>
        </div>
    </div>
@endsection
