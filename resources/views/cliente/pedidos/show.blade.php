@extends('cliente.layout.cliente')

@section('title', 'Detalle del Pedido - DÉSOLÉ')

@section('content')
<div class="pedido-detail-container">
    <h1>Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</h1>
    <p>Estado: <strong><span id="pedido-estado">{{ ucfirst($pedido->estado) }}</span></strong></p>
    <p>Fecha: {{ $pedido->created_at->format('d/m/Y H:i') }}</p>

    <h3>Items</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->items ?? [] as $item)
                @php
                    $nombre = $item['nombre'] ?? 'Producto';
                    $precio = (float)($item['precio'] ?? 0);
                    $cantidad = (int)($item['cantidad'] ?? 1);
                @endphp
                <tr>
                    <td>{{ $nombre }}</td>
                    <td>{{ $cantidad }}</td>
                    <td>${{ number_format($precio, 2) }}</td>
                    <td>${{ number_format(($precio * $cantidad), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                <td><strong>${{ number_format($pedido->total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <a href="{{ route('cliente.pedidos.index') }}" class="btn btn-secondary">Volver</a>
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
