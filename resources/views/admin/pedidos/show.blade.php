@extends('admin.layout')

@section('content')
	<h1 class="text-2xl font-bold mb-4">Pedido #{{ $pedido->id }}</h1>

	<div class="mb-4">
		<strong>Cliente:</strong> {{ $pedido->cliente_nombre }}<br>
		<strong>Teléfono:</strong> {{ $pedido->cliente_telefono }}<br>
		<strong>Dirección:</strong> {{ $pedido->direccion }}<br>
		<strong>Estado:</strong> {{ $pedido->status }}<br>
		<strong>Total:</strong> {{ number_format($pedido->total, 2) }}
	</div>

	<h2 class="text-lg font-semibold">Items</h2>
	@php $items = $pedido->items ?? []; @endphp
	@if(empty($items))
		<p>No hay items registrados en este pedido.</p>
	@else
		<table class="min-w-full bg-white">
			<thead>
				<tr>
					<th class="px-4 py-2">Producto ID</th>
					<th class="px-4 py-2">Nombre</th>
					<th class="px-4 py-2">Cantidad</th>
					<th class="px-4 py-2">Precio</th>
				</tr>
			</thead>
			<tbody>
				@foreach($items as $item)
					<tr class="border-t">
						<td class="px-4 py-2">{{ $item['producto_id'] ?? '-' }}</td>
						<td class="px-4 py-2">{{ $item['nombre'] ?? ($productos[$item['producto_id']]->nombre ?? 'Desconocido') }}</td>
						<td class="px-4 py-2">{{ $item['cantidad'] ?? 0 }}</td>
						<td class="px-4 py-2">{{ number_format($item['precio'] ?? 0, 2) }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif

	<div class="mt-4">
		@if($pedido->status === 'pendiente')
			<form method="POST" action="{{ route('admin.pedidos.iniciarPreparacion', $pedido) }}">
				@csrf
				<button class="btn btn-warning">Iniciar preparación</button>
			</form>
		@elseif($pedido->status === 'en_preparacion')
			<form method="POST" action="{{ route('admin.pedidos.marcarListo', $pedido) }}">
				@csrf
				<button class="btn btn-success">Marcar listo</button>
			</form>
		@elseif($pedido->status === 'listo')
			<form method="POST" action="{{ route('admin.pedidos.marcarEntregado', $pedido) }}">
				@csrf
				<button class="btn btn-info">Marcar entregado</button>
			</form>
		@endif
	</div>

@endsection
