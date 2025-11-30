@extends('cliente.layout.cliente')

@section('title', 'Método de Pago - DÉSOLÉ')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-3xl mx-auto bg-zinc-800 rounded-2xl shadow-xl overflow-hidden border border-zinc-700">
        <div class="p-8">
            <h1 class="text-3xl font-bold text-green-400 mb-8 text-center">💳 Método de Pago</h1>

            <div class="mb-8 p-6 bg-zinc-700/50 rounded-lg border border-zinc-600">
                <h2 class="text-xl text-white font-semibold mb-4">Resumen del Pedido</h2>
                <div class="flex justify-between items-center text-gray-300 mb-2">
                    <span>Total a Pagar:</span>
                    <span class="text-2xl text-green-400 font-bold">${{ number_format($total, 2) }}</span>
                </div>
                <p class="text-sm text-gray-400">{{ count($cart) }} producto(s)</p>
            </div>

            <form action="{{ route('cliente.carrito.finalizar') }}" method="POST" id="pagoForm">
                @csrf
                
                <div class="space-y-4 mb-8">
                    <label class="block relative p-4 border border-zinc-600 rounded-lg cursor-pointer hover:bg-zinc-700 transition duration-300 group">
                        <input type="radio" name="metodo_pago" value="efectivo" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-green-500 focus:ring-green-500" checked>
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-zinc-700 flex items-center justify-center mr-4 group-hover:bg-zinc-600 transition">
                                <i class="fas fa-money-bill-wave text-xl text-green-400"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg">Efectivo</h3>
                                <p class="text-gray-400 text-sm">Pago en efectivo al recibir el pedido</p>
                            </div>
                        </div>
                    </label>

                    <label class="block relative p-4 border border-zinc-600 rounded-lg cursor-pointer hover:bg-zinc-700 transition duration-300 group">
                        <input type="radio" name="metodo_pago" value="tarjeta" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-green-500 focus:ring-green-500">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-zinc-700 flex items-center justify-center mr-4 group-hover:bg-zinc-600 transition">
                                <i class="fas fa-credit-card text-xl text-blue-400"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg">Tarjeta de Crédito / Débito</h3>
                                <p class="text-gray-400 text-sm">Pago con tarjeta al repartidor (POS)</p>
                            </div>
                        </div>
                    </label>

                    <label class="block relative p-4 border border-zinc-600 rounded-lg cursor-pointer hover:bg-zinc-700 transition duration-300 group">
                        <input type="radio" name="metodo_pago" value="transferencia" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-green-500 focus:ring-green-500">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-zinc-700 flex items-center justify-center mr-4 group-hover:bg-zinc-600 transition">
                                <i class="fas fa-university text-xl text-purple-400"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-semibold text-lg">Transferencia Bancaria</h3>
                                <p class="text-gray-400 text-sm">Realiza una transferencia a nuestra cuenta</p>
                            </div>
                        </div>
                    </label>
                </div>

                <div class="flex gap-4 justify-between pt-4 border-t border-zinc-600">
                    <a href="{{ route('cliente.carrito') }}" class="bg-zinc-600 hover:bg-zinc-500 text-white px-6 py-3 rounded-lg transition duration-300 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Volver
                    </a>
                    
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-lg font-semibold transition duration-300 transform hover:scale-105 shadow-lg shadow-green-500/20 flex items-center">
                        Confirmar Pedido <i class="fas fa-check ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('pagoForm')?.addEventListener('submit', function(e) {
    const button = this.querySelector('button[type="submit"]');
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';
});
</script>
@endsection
