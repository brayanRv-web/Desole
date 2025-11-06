{{-- resources/views/cliente/productos/index.blade.php --}}
@extends('cliente.layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-green-400 mb-8">Nuestro Menú</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($productos as $producto)
        <div class="bg-gray-800 rounded-2xl border border-green-700/30 overflow-hidden hover:border-green-500/50 transition-all duration-300 hover:transform hover:scale-105">
            @if($producto->imagen)
            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                 alt="{{ $producto->nombre }}"
                 class="w-full h-48 object-cover">
            @else
            <div class="w-full h-48 bg-gray-700 flex items-center justify-center">
                <i class="fas fa-coffee text-4xl text-gray-500"></i>
            </div>
            @endif

            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold text-white">{{ $producto->nombre }}</h3>
                    <span class="text-2xl font-bold text-green-400">${{ number_format($producto->precio, 2) }}</span>
                </div>

                <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                    {{ $producto->descripcion ?: 'Sin descripción' }}
                </p>

                <div class="flex justify-between items-center">
                    <span class="text-sm {{ $producto->stock > 0 ? 'text-green-400' : 'text-red-400' }}">
                        @if($producto->stock > 0)
                            {{ $producto->stock }} disponibles
                        @else
                            Agotado
                        @endif
                    </span>

                    <button 
                        @if($producto->stock <= 0) disabled @endif
                        class="bg-green-600 hover:bg-green-700 disabled:bg-gray-600 disabled:cursor-not-allowed text-white px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                        <i class="fas fa-shopping-cart"></i>
                        Agregar
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($productos->isEmpty())
    <div class="text-center py-12">
        <i class="fas fa-coffee text-6xl text-gray-600 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-400 mb-2">No hay productos disponibles</h3>
        <p class="text-gray-500">Vuelve pronto para ver nuestro menú</p>
    </div>
    @endif
</div>
@endsection