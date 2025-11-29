@extends('cliente.layout.cliente')

@section('title', 'Carrito de Compras - DÉSOLÉ')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-5xl mx-auto bg-zinc-800 rounded-2xl shadow-xl overflow-hidden border border-zinc-700">
        <div class="p-8">
            <h1 class="text-4xl font-bold text-green-400 mb-8 text-center">🛒 Mi Carrito</h1>

            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500 text-green-500 px-4 py-3 rounded mb-6" role="alert">
                    <strong class="font-bold">¡Éxito!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500 text-red-500 px-4 py-3 rounded mb-6" role="alert">
                    <strong class="font-bold">¡Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(empty($cart) || count($cart) === 0)
                <div class="text-center text-gray-400 p-8">
                    <i class="fas fa-shopping-cart text-5xl mb-4"></i>
                    <p class="text-xl mb-4">Tu carrito está vacío</p>
                    <a href="{{ route('cliente.menu') }}" class="btn-seguir-comprando mt-4 inline-block bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Seguir Comprando
                    </a>
                </div>
            @else
                @foreach($cart as $id => $item)
                    <div class="cart-item flex items-center gap-4 p-4 border border-zinc-600 rounded-lg bg-zinc-700/50 mb-4 hover:bg-zinc-700 transition duration-300">
                        <img src="{{ $item['imagen'] ? asset('storage/'.$item['imagen']) : asset('assets/placeholder.png') }}" 
                             class="w-20 h-20 object-cover rounded-lg" alt="{{ $item['nombre'] }}">
                        <div class="flex-1">
                            <h3 class="text-white font-semibold text-lg">{{ $item['nombre'] }}</h3>
                            <p class="text-green-400 font-medium">${{ number_format($item['precio'], 2) }} c/u</p>
                            <p class="text-gray-400">Cantidad: {{ $item['cantidad'] }}</p>
                            <p class="text-white font-semibold mt-1">Subtotal: ${{ number_format($item['precio'] * $item['cantidad'], 2) }}</p>
                        </div>
                    </div>
                @endforeach

                <div class="cart-summary border-t border-zinc-600 pt-6 mt-6 flex justify-between items-center">
                    <div>
                        <span class="text-white font-bold text-xl">Total: ${{ number_format($total, 2) }}</span>
                        <p class="text-gray-400 text-sm mt-1">{{ count($cart) }} producto(s) en el carrito</p>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('cliente.menu') }}" class="bg-zinc-600 hover:bg-zinc-500 text-white px-6 py-3 rounded-lg transition duration-300">
                            <i class="fas fa-plus mr-2"></i>Seguir Comprando
                        </a>
                        
                        <button onclick="window.carrito.irAlCheckout()" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-300 transform hover:scale-105 inline-flex items-center">
                            <i class="fas fa-credit-card mr-2"></i>Finalizar Compra
                        </button>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection