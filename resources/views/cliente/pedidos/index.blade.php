@extends('cliente.layout.cliente')

@section('title', 'Mis Pedidos - DÉSOLÉ')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold text-green-400 mb-8">Mis Pedidos</h1>

        @if($pedidos->isEmpty())
            <div class="bg-zinc-800 rounded-xl p-8 text-center">
                <i class="fas fa-receipt text-6xl text-gray-600 mb-4"></i>
                <p class="text-gray-400 text-lg mb-4">Aún no tienes pedidos</p>
                <a href="{{ route('cliente.menu') }}" class="inline-block bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-utensils mr-2"></i>Ver Menú
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($pedidos as $pedido)
                    <div class="bg-zinc-800 rounded-xl overflow-hidden shadow-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-white mb-1">
                                        Pedido #{{ $pedido->id }}
                                    </h3>
                                    <p class="text-gray-400">
                                        {{ $pedido->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 rounded-full text-sm 
                                        @if($pedido->status == 'pendiente') bg-yellow-500/20 text-yellow-400
                                        @elseif($pedido->status == 'preparando') bg-blue-500/20 text-blue-400
                                        @elseif($pedido->status == 'listo') bg-green-500/20 text-green-400
                                        @elseif($pedido->status == 'entregado') bg-purple-500/20 text-purple-400
                                        @else bg-red-500/20 text-red-400
                                        @endif">
                                        {{ ucfirst($pedido->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="border-t border-zinc-700 pt-4">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div>
                                        <p class="text-gray-400 text-sm">Total</p>
                                        <p class="text-green-400 font-semibold">${{ number_format($pedido->total, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-400 text-sm">Productos</p>
                                        <p class="text-white">{{ count($pedido->items) }}</p>
                                    </div>
                                    @if($pedido->tiempo_estimado)
                                        <div>
                                            <p class="text-gray-400 text-sm">Tiempo Estimado</p>
                                            <p class="text-white">{{ $pedido->tiempo_estimado }} min</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex justify-between items-center">
                                    <a href="{{ route('cliente.pedidos.show', $pedido) }}" 
                                       class="text-blue-400 hover:text-blue-300 transition-colors">
                                        Ver Detalles <i class="fas fa-chevron-right ml-1"></i>
                                    </a>

                                    @if($pedido->status === 'pendiente')
                                        <form action="{{ route('cliente.pedidos.cancelar', $pedido) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('¿Estás seguro de que deseas cancelar este pedido?')">
                                            @csrf
                                            <button type="submit" 
                                                    class="text-red-400 hover:text-red-300 transition-colors">
                                                <i class="fas fa-times mr-1"></i>Cancelar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $pedidos->links() }}
            </div>
        @endif
    </div>
</div>
@endsection