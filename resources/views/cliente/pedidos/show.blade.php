@extends('cliente.layout.cliente')

@section('title', 'Detalle del Pedido - DÉSOLÉ')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header / Breadcrumb-ish -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <a href="{{ route('cliente.pedidos.index') }}" class="text-gray-400 hover:text-green-400 transition-colors text-sm mb-2 inline-block">
                    <i class="fas fa-arrow-left mr-1"></i> Volver a Mis Pedidos
                </a>
                <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-600">
                    Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}
                </h1>
                <p class="text-gray-400 text-sm mt-1">
                    Realizado el {{ $pedido->created_at->format('d/m/Y') }} a las {{ $pedido->created_at->format('H:i') }}
                </p>
            </div>
            
            <!-- Status Badge Grande -->
            <div class="flex items-center">
                <div class="px-4 py-2 rounded-full border border-zinc-700 bg-zinc-800/50 backdrop-blur shadow-lg">
                    <span class="text-gray-400 text-sm mr-2">Estado:</span>
                    <span id="pedido-estado-badge" class="font-bold uppercase tracking-wide
                        @if($pedido->estado == 'pendiente') text-yellow-400
                        @elseif($pedido->estado == 'preparacion') text-blue-400
                        @elseif($pedido->estado == 'en_camino') text-purple-400
                        @elseif($pedido->estado == 'entregado') text-green-400
                        @elseif($pedido->estado == 'cancelado') text-red-400
                        @else text-gray-200 @endif">
                        {{ ucfirst($pedido->estado) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Columna Principal: Items -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-zinc-800/80 backdrop-blur-md rounded-2xl border border-zinc-700/50 overflow-hidden shadow-xl">
                    <div class="p-6 border-b border-zinc-700/50">
                        <h2 class="text-xl font-semibold text-white"><i class="fas fa-shopping-bag mr-2 text-green-500"></i> Productos</h2>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-zinc-900/50 text-gray-400 text-xs uppercase tracking-wider">
                                <tr>
                                    <th class="p-4 font-medium">Producto</th>
                                    <th class="p-4 font-medium text-center">Cant.</th>
                                    <th class="p-4 font-medium text-right">Precio</th>
                                    <th class="p-4 font-medium text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-700/50 text-gray-300 text-sm">
                                @foreach($pedido->detalles as $detalle)
                                    @php
                                        $nombre = $detalle->producto->nombre ?? 'Producto no disponible';
                                        $precio = (float)$detalle->precio;
                                        $cantidad = (int)$detalle->cantidad;
                                        $subtotal = $precio * $cantidad;
                                    @endphp
                                    <tr class="hover:bg-zinc-700/30 transition-colors">
                                        <td class="p-4 font-medium text-white">{{ $nombre }}</td>
                                        <td class="p-4 text-center">{{ $cantidad }}</td>
                                        <td class="p-4 text-right">${{ number_format($precio, 2) }}</td>
                                        <td class="p-4 text-right font-semibold text-green-400">${{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-zinc-900/30">
                                <tr>
                                    <td colspan="3" class="p-4 text-right text-gray-400">Subtotal</td>
                                    <td class="p-4 text-right text-white font-medium">${{ number_format($pedido->total, 2) }}</td>
                                </tr>
                                <!-- Aquí podrías agregar envío si lo tuvieras desglosado -->
                                <tr class="border-t border-zinc-700/50">
                                    <td colspan="3" class="p-4 text-right text-lg font-bold text-white">Total</td>
                                    <td class="p-4 text-right text-xl font-bold text-green-400">${{ number_format($pedido->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna Lateral: Info -->
            <div class="space-y-6">
                
                <!-- Info de Entrega / Cliente -->
                <div class="bg-zinc-800/80 backdrop-blur-md rounded-2xl border border-zinc-700/50 overflow-hidden shadow-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-zinc-700/50 pb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-green-500"></i> Detalles de Entrega
                    </h3>
                    
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs uppercase mb-1">Tipo de Entrega</p>
                            <p class="text-white font-medium">
                                @if($pedido->tipo_entrega == 'domicilio')
                                    <i class="fas fa-motorcycle text-green-400 mr-1"></i> A Domicilio
                                @else
                                    <i class="fas fa-store text-blue-400 mr-1"></i> Recoger en Tienda
                                @endif
                            </p>
                        </div>

                        @if($pedido->tipo_entrega == 'domicilio' && $pedido->direccion_entrega)
                            @php
                                $direccion = is_string($pedido->direccion_entrega) ? json_decode($pedido->direccion_entrega, true) : $pedido->direccion_entrega;
                            @endphp
                            <div>
                                <p class="text-gray-500 text-xs uppercase mb-1">Dirección</p>
                                <p class="text-gray-300">{{ $direccion['direccion'] ?? 'No especificada' }}</p>
                            </div>
                            @if(!empty($direccion['telefono']))
                            <div>
                                <p class="text-gray-500 text-xs uppercase mb-1">Teléfono de Contacto</p>
                                <p class="text-gray-300">{{ $direccion['telefono'] }}</p>
                            </div>
                            @endif
                            @if(!empty($direccion['instrucciones']))
                            <div>
                                <p class="text-gray-500 text-xs uppercase mb-1">Instrucciones</p>
                                <p class="text-gray-400 italic">"{{ $direccion['instrucciones'] }}"</p>
                            </div>
                            @endif
                        @else
                            <div>
                                <p class="text-gray-500 text-xs uppercase mb-1">Ubicación de la Tienda</p>
                                <p class="text-gray-300">San Fernando (Sucursal Principal)</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info de Pago -->
                <div class="bg-zinc-800/80 backdrop-blur-md rounded-2xl border border-zinc-700/50 overflow-hidden shadow-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4 border-b border-zinc-700/50 pb-2">
                        <i class="fas fa-credit-card mr-2 text-green-500"></i> Pago
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs uppercase mb-1">Método</p>
                            <p class="text-white font-medium capitalize">
                                @if($pedido->metodo_pago == 'efectivo')
                                    <i class="fas fa-money-bill-wave text-green-400 mr-1"></i> Efectivo
                                @elseif($pedido->metodo_pago == 'tarjeta')
                                    <i class="fas fa-credit-card text-blue-400 mr-1"></i> Tarjeta
                                @else
                                    {{ ucfirst($pedido->metodo_pago) }}
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase mb-1">Estado del Pago</p>
                            <span class="inline-block px-2 py-1 rounded text-xs font-bold
                                @if($pedido->pagado) bg-green-500/20 text-green-400 @else bg-yellow-500/20 text-yellow-400 @endif">
                                @if($pedido->pagado) PAGADO @else PENDIENTE @endif
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    (function(){
        const badgeEl = document.getElementById('pedido-estado-badge');
        if (!badgeEl) return;

        let lastEstado = badgeEl.textContent.trim().toLowerCase();
        const pedidoUrl = "{{ route('cliente.pedidos.show', $pedido->id) }}";

        // Configuración de colores para los estados (Tailwind classes)
        const statusColors = {
            'pendiente': 'text-yellow-400',
            'preparacion': 'text-blue-400',
            'en_camino': 'text-purple-400',
            'entregado': 'text-green-400',
            'cancelado': 'text-red-400',
            'default': 'text-gray-200'
        };

        async function checkEstado(){
            try {
                const res = await fetch(pedidoUrl, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) return;
                const json = await res.json();
                if (!json || !json.success) return;
                
                const newEstado = (json.data && json.data.pedido && json.data.pedido.estado) ? String(json.data.pedido.estado) : '';
                if (!newEstado) return;

                if (newEstado !== lastEstado) {
                    // Actualizar texto
                    badgeEl.textContent = newEstado.charAt(0).toUpperCase() + newEstado.slice(1);
                    
                    // Actualizar color
                    // Primero removemos todas las clases de color posibles
                    Object.values(statusColors).forEach(cls => badgeEl.classList.remove(cls));
                    // Agregamos la nueva clase
                    const colorClass = statusColors[newEstado] || statusColors['default'];
                    badgeEl.classList.add(colorClass);

                    lastEstado = newEstado;

                    // Notificación SweetAlert2 Toast
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        background: '#18181b', // zinc-900
                        color: '#fff',
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: 'info',
                        title: 'Actualización de Estado',
                        text: 'Tu pedido ahora está: ' + badgeEl.textContent
                    });
                }
            } catch (e) {
                console.debug('checkEstado error', e);
            }
        }

        // Polling cada 6 segundos
        setInterval(checkEstado, 6000);
    })();
</script>
@endpush
