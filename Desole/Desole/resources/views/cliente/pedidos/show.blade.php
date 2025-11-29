@extends('cliente.layout.cliente')

@section('title', 'Detalle del Pedido - DÉSOLÉ')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-4xl mx-auto bg-zinc-800 rounded-2xl shadow-xl overflow-hidden border border-zinc-700 p-8">
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-green-400 mb-2">Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-gray-400">Fecha: {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold 
                    {{ $pedido->estado == 'pendiente' ? 'bg-yellow-500/20 text-yellow-500' : '' }}
                    {{ $pedido->estado == 'completado' ? 'bg-green-500/20 text-green-500' : '' }}
                    {{ $pedido->estado == 'cancelado' ? 'bg-red-500/20 text-red-500' : '' }}">
                    <span id="pedido-estado">{{ ucfirst($pedido->estado) }}</span>
                </span>
            </div>
        </div>

        <!-- Detalles del Pago -->
        <div class="bg-zinc-700/30 rounded-xl p-6 mb-8 border border-zinc-600">
            <h3 class="text-xl font-semibold text-white mb-4"><i class="fas fa-wallet mr-2 text-green-400"></i>Información de Pago</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-400 text-sm">Método de Pago</p>
                    <p class="text-white font-medium capitalize">{{ $pedido->metodo_pago ?? 'No especificado' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Total a Pagar</p>
                    <p class="text-green-400 font-bold text-xl">${{ number_format($pedido->total, 2) }}</p>
                </div>
            </div>

            @if($pedido->metodo_pago == 'transferencia')
                <div class="mt-6 pt-6 border-t border-zinc-600">
                    <h4 class="text-yellow-400 font-semibold mb-3"><i class="fas fa-info-circle mr-2"></i>Datos para Transferencia</h4>
                    <div class="bg-zinc-800 p-4 rounded-lg text-sm text-gray-300 space-y-2">
                        <p><span class="text-gray-500">Banco:</span> BBVA Bancomer</p>
                        <p><span class="text-gray-500">Beneficiario:</span> DÉSOLÉ Cafetería</p>
                        <p><span class="text-gray-500">CLABE:</span> 012 345 67890123456 7</p>
                        <p><span class="text-gray-500">Concepto:</span> Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</p>
                        <div class="mt-4 text-xs text-gray-500">
                            * Por favor envía tu comprobante de pago por WhatsApp al {{ config('contacto.whatsapp') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <h3 class="text-xl font-semibold text-white mb-4">Items del Pedido</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 border-b border-zinc-700">
                        <th class="py-3 font-medium">Producto</th>
                        <th class="py-3 font-medium text-center">Cant.</th>
                        <th class="py-3 font-medium text-right">Precio</th>
                        <th class="py-3 font-medium text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    @foreach($pedido->items ?? [] as $item)
                        @php
                            $nombre = $item['nombre'] ?? 'Producto';
                            $precio = (float)($item['precio'] ?? 0);
                            $cantidad = (int)($item['cantidad'] ?? 1);
                        @endphp
                        <tr class="border-b border-zinc-700/50">
                            <td class="py-4">{{ $nombre }}</td>
                            <td class="py-4 text-center">{{ $cantidad }}</td>
                            <td class="py-4 text-right">${{ number_format($precio, 2) }}</td>
                            <td class="py-4 text-right font-medium text-white">${{ number_format(($precio * $cantidad), 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="py-4 text-right text-gray-400">Total:</td>
                        <td class="py-4 text-right text-2xl font-bold text-green-400">${{ number_format($pedido->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('cliente.pedidos.index') }}" class="inline-flex items-center px-6 py-3 bg-zinc-700 hover:bg-zinc-600 text-white rounded-lg transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i> Volver a mis pedidos
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function(){
        const estadoEl = document.getElementById('pedido-estado');
        if (!estadoEl) return;

        let lastEstado = estadoEl.textContent.trim().toLowerCase();
        const pedidoUrl = "{{ route('cliente.pedidos.show', $pedido->id) }}";

        async function checkEstado(){
            try {
                const res = await fetch(pedidoUrl, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const json = await res.json();
                if (!json || !json.success) return;
                const newEstado = (json.data && json.data.pedido && json.data.pedido.estado) ? String(json.data.pedido.estado) : '';
                if (!newEstado) return;
                if (newEstado !== lastEstado) {
                    // update UI
                    estadoEl.textContent = newEstado.charAt(0).toUpperCase() + newEstado.slice(1);
                    lastEstado = newEstado;

                    // small visual toast
                    const t = document.createElement('div');
                    t.textContent = 'Estado actualizado: ' + estadoEl.textContent;
                    t.style.position = 'fixed'; t.style.right = '1rem'; t.style.top = '6rem'; t.style.background = '#059669'; t.style.color = '#fff'; t.style.padding = '0.5rem 1rem'; t.style.borderRadius = '0.5rem'; t.style.boxShadow = '0 6px 18px rgba(0,0,0,0.35)';
                    document.body.appendChild(t);
                    setTimeout(()=> t.remove(), 3500);
                }
            } catch (e) {
                // silently ignore network errors
                console.debug('checkEstado error', e);
            }
        }

        // start polling every 6 seconds
        setInterval(checkEstado, 6000);
        // also run once shortly after load
        setTimeout(checkEstado, 1500);
    })();
</script>
@endpush
