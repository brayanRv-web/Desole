@extends('empleado.layout')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gray-800/50 rounded-2xl p-6 border border-green-700/20">
                <div class="text-sm text-gray-400">Pedidos pendientes</div>
                <div class="text-3xl font-bold text-white mt-2">{{ $pendiente }}</div>
            </div>

            <div class="bg-gray-800/50 rounded-2xl p-6 border border-yellow-600/20">
                <div class="text-sm text-gray-400">En preparación</div>
                <div class="text-3xl font-bold text-white mt-2">{{ $preparando }}</div>
            </div>

            <div class="bg-gray-800/50 rounded-2xl p-6 border border-blue-600/20">
                <div class="text-sm text-gray-400">Listos</div>
                <div class="text-3xl font-bold text-white mt-2">{{ $listo }}</div>
            </div>
        </div>

        <div class="bg-gray-800/50 rounded-2xl p-6 border border-green-700/20">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-semibold text-white">Últimos pedidos</h3>
                    <p class="text-sm text-gray-400">Los 5 pedidos más recientes</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('empleado.productos.index') }}" class="px-3 py-2 bg-green-600 text-white rounded">Actualizar menú</a>
                    <a href="{{ route('empleado.pedidos.index') }}" class="px-3 py-2 bg-gray-700 text-gray-200 rounded">Ver todos</a>
                </div>
            </div>

            @if($ultimos->isEmpty())
                <div class="text-gray-500">No hay pedidos recientes.</div>
            @else
                <div class="divide-y divide-gray-700/40">
                    @foreach($ultimos as $p)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-white">Pedido #{{ $p->id }} — {{ $p->cliente_nombre ?? 'Cliente' }}</div>
                                <div class="text-sm text-gray-400">{{ $p->created_at->format('Y-m-d H:i') }} • {{ ucfirst($p->status) }}</div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-sm text-gray-300">${{ number_format($p->total,2) }}</div>
                                <a href="{{ route('empleado.pedidos.show', $p) }}" class="text-sm text-blue-400 hover:underline">Ver detalles</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
