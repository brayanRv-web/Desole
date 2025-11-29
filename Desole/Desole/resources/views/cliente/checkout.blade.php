@extends('cliente.layout.cliente')

@section('title', 'Finalizar Compra - DÉSOLÉ')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-zinc-950 via-black to-zinc-900 py-16 px-4 sm:px-6 lg:px-8">

    <div class="max-w-7xl mx-auto">

        <!-- HEADER -->
        <div class="text-center mb-20">
            <div class="inline-flex items-center justify-center mb-4 animate-pulse">
                <div class="w-3 h-3 rounded-full bg-green-400 shadow-green-500/60 shadow-glow"></div>
                <span class="ml-3 text-green-400 text-sm tracking-widest font-semibold">
                    CHECKOUT SEGURO
                </span>
            </div>

            <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-white mb-4">
                Finalizar <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-300">Compra</span>
            </h1>

            <p class="text-zinc-400 text-lg max-w-2xl mx-auto">
                Revisa tu pedido y selecciona tu método de pago de manera segura.
            </p>
        </div>

        <form action="{{ route('cliente.carrito.finalizar') }}" method="POST" id="checkoutForm"
              class="lg:grid lg:grid-cols-12 lg:gap-x-12 animate-fade-in">
            @csrf


            <!-- ==========================================
                    MÉTODO DE PAGO — DISEÑO PREMIUM
            ============================================ -->
            <section class="lg:col-span-7 space-y-8">

                <div class="bg-gradient-to-b from-zinc-900/80 to-black/60 backdrop-blur-xl border border-zinc-800/70 
                            rounded-3xl shadow-2xl shadow-black/40 overflow-hidden p-10 relative">

                    <!-- Shine -->
                    <div class="absolute inset-0 bg-gradient-to-br from-transparent via-white/5 to-transparent opacity-5 pointer-events-none"></div>

                    <div class="flex items-center gap-4 mb-8">
                        <div class="p-3 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-400 shadow-lg shadow-emerald-600/30">
                            <i class="fas fa-wallet text-white text-xl"></i>
                        </div>

                        <div>
                            <h2 class="text-2xl font-bold text-white">Método de Pago</h2>
                            <p class="text-zinc-500 text-sm">Elige cómo deseas completar tu compra</p>
                        </div>
                    </div>


                    <!-- TARJETAS MEJORADAS -->
                    <div class="space-y-5">

                        <!-- EFECTIVO -->
                        @include('cliente.components.payoption', [
                            'icon' => 'fa-money-bill-wave',
                            'title' => 'Efectivo (Contra entrega)',
                            'desc' => 'Pagas al momento de recibir tu pedido.',
                            'colorFrom' => 'amber-500',
                            'colorTo' => 'amber-600',
                            'value' => 'efectivo',
                            'checked' => true
                        ])

                        <!-- TRANSFERENCIA -->
                        @include('cliente.components.payoption', [
                            'icon' => 'fa-university',
                            'title' => 'Transferencia Bancaria',
                            'desc' => 'Los datos aparecerán al confirmar.',
                            'colorFrom' => 'blue-500',
                            'colorTo' => 'blue-600',
                            'value' => 'transferencia'
                        ])

                        <!-- TARJETA -->
                        @include('cliente.components.payoption', [
                            'icon' => 'fa-credit-card',
                            'title' => 'Tarjeta (Contra entrega)',
                            'desc' => 'Se llevará una terminal hasta tu domicilio.',
                            'colorFrom' => 'purple-500',
                            'colorTo' => 'purple-600',
                            'value' => 'tarjeta'
                        ])

                    </div>


                    <div class="mt-10 pt-6 border-t border-zinc-700/40">
                        <a href="{{ route('cliente.carrito') }}"
                           class="text-zinc-400 hover:text-white transition flex items-center text-sm">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Seguir viendo mi carrito
                        </a>
                    </div>

                </div>

            </section>

            <!-- ==========================================
                    RESUMEN — ULTRA PREMIUM
            ============================================ -->
            <section class="lg:col-span-5">
                <div class="sticky top-6 bg-gradient-to-b from-zinc-900/80 to-black/60 backdrop-blur-xl 
                            border border-zinc-800/70 rounded-3xl shadow-2xl shadow-black/40 p-10 relative overflow-hidden">

                    <div class="absolute -left-20 -bottom-20 w-56 h-56 bg-green-600/10 blur-3xl rounded-full"></div>

                    <div class="flex items-center gap-3 mb-8 relative z-10">
                        <div class="p-2.5 rounded-xl bg-gradient-to-br from-emerald-500 to-green-400 shadow-md shadow-emerald-700/40">
                            <i class="fas fa-receipt text-white"></i>
                        </div>
                        <h2 class="text-xl font-semibold text-white">Resumen del Pedido</h2>
                    </div>

                    <!-- LISTADO DE PRODUCTOS -->
                    <ul class="max-h-80 overflow-y-auto custom-scrollbar divide-y divide-zinc-700/30 pr-2">
                        @foreach($cart as $item)
                        <li class="py-4 flex gap-4 hover:bg-zinc-800/40 px-3 rounded-xl transition">
                            <div class="h-16 w-16 rounded-xl overflow-hidden border border-zinc-700/50">
                                <img src="{{ isset($item['imagen']) ? asset('storage/'.$item['imagen']) : asset('assets/placeholder.png') }}"
                                     class="w-full h-full object-cover">
                            </div>

                            <div class="flex-1">
                                <div class="flex justify-between">
                                    <h3 class="text-white font-medium">{{ $item['nombre'] }}</h3>
                                    <p class="text-green-400 font-semibold">
                                        ${{ number_format($item['precio'] * $item['cantidad'], 2) }}
                                    </p>
                                </div>
                                <p class="text-sm text-zinc-500">
                                    {{ $item['cantidad'] }} × ${{ number_format($item['precio'], 2) }}
                                </p>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <!-- TOTALES -->
                    <div class="mt-8 pt-6 space-y-4 border-t border-zinc-700/40">

                        <div class="flex justify-between text-zinc-400 text-sm">
                            <span>Subtotal</span>
                            <span class="text-white font-medium">${{ number_format($total, 2) }}</span>
                        </div>

                        <div class="flex justify-between items-end mt-4">
                            <div>
                                <p class="text-white font-semibold text-lg">Total a pagar</p>
                                <p class="text-xs text-zinc-500">IVA incluido</p>
                            </div>

                            <p class="text-4xl font-extrabold text-green-400">
                                ${{ number_format($total, 2) }}
                            </p>
                        </div>

                        <!-- BOTÓN -->
                        <button type="submit"
                                class="w-full mt-10 py-4 rounded-2xl bg-gradient-to-r from-green-600 to-emerald-500 
                                       text-white font-bold text-lg shadow-lg shadow-emerald-700/40 
                                       hover:scale-[1.03] active:scale-[0.97] transition flex items-center justify-center gap-2 relative overflow-hidden group">

                            <span class="relative z-10">Confirmar Pedido</span>
                            <i class="fas fa-check-circle relative z-10"></i>

                            <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-20 transition"></div>
                        </button>

                        <p class="mt-4 text-center text-xs text-zinc-500">
                            <i class="fas fa-lock mr-1"></i> Tu transacción es segura
                        </p>

                    </div>
                </div>
            </section>

        </form>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #22c55e, #10b981);
    border-radius: 10px;
}
</style>

<script>
document.getElementById('checkoutForm').addEventListener('submit', () => {
    const btn = document.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Procesando...';
});
</script>

@endsection
