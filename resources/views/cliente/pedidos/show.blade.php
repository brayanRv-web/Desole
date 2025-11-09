@extends('cliente.layout.cliente')

@section('title', 'Detalle del Pedido - DÉSOLÉ')

@section('content')
<div class="pedido-detail-container">
    <h1>Pedido #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</h1>
    <p>Estado: <strong>{{ ucfirst($pedido->status) }}</strong></p>
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
                    $producto = isset($productos[$item['producto_id']]) ? $productos[$item['producto_id']] : null;
                    $nombre = $producto?->nombre ?? ($item['nombre'] ?? 'Producto');
                    $precio = $item['precio'] ?? ($producto?->precio ?? 0);
                @endphp
                <tr>
                    <td>{{ $nombre }}</td>
                    <td>{{ $item['cantidad'] }}</td>
                    <td>${{ number_format($precio, 2) }}</td>
                    <td>${{ number_format(($precio * $item['cantidad']), 2) }}</td>
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

    <a href="{{ route('cliente.pedidos') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection
