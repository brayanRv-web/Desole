@extends('admin.layout')

@section('content')
	<h1 class="text-2xl font-bold mb-4">Pedidos</h1>

	<h2 class="mt-4">Pendientes / En preparación</h2>
	@if($pedidosActivos->isEmpty())
		<p>No hay pedidos activos.</p>
	@else
		<table class="min-w-full bg-white">
			<thead>
				<tr>
					<th class="px-4 py-2">#</th>
					<th class="px-4 py-2">Cliente</th>
					<th class="px-4 py-2">Total</th>
					<th class="px-4 py-2">Estado</th>
					<th class="px-4 py-2">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pedidosActivos as $pedido)
					<tr class="border-t">
						<td class="px-4 py-2">{{ $pedido->id }}</td>
						<td class="px-4 py-2">{{ $pedido->cliente_nombre }}</td>
						<td class="px-4 py-2">{{ number_format($pedido->total, 2) }}</td>
						<td class="px-4 py-2">{{ $pedido->status }}</td>
						<td class="px-4 py-2">
							<a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-sm btn-primary">Ver</a>
							@if($pedido->status === 'pendiente')
								<form method="POST" action="{{ route('admin.pedidos.iniciarPreparacion', $pedido) }}" style="display:inline">
									@csrf
									<button class="btn btn-sm btn-warning">Iniciar preparación</button>
								</form>
							@elseif($pedido->status === 'en_preparacion')
								<form method="POST" action="{{ route('admin.pedidos.marcarListo', $pedido) }}" style="display:inline">
									@csrf
									<button class="btn btn-sm btn-success">Marcar listo</button>
								</form>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif

	<h2 class="mt-6">Listos para entrega</h2>
	@if($pedidosListos->isEmpty())
		<p>No hay pedidos listos.</p>
	@else
		<table class="min-w-full bg-white">
			<thead>
				<tr>
					<th class="px-4 py-2">#</th>
					<th class="px-4 py-2">Cliente</th>
					<th class="px-4 py-2">Total</th>
					<th class="px-4 py-2">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pedidosListos as $pedido)
					<tr class="border-t">
						<td class="px-4 py-2">{{ $pedido->id }}</td>
						<td class="px-4 py-2">{{ $pedido->cliente_nombre }}</td>
						<td class="px-4 py-2">{{ number_format($pedido->total, 2) }}</td>
						<td class="px-4 py-2">
							<a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-sm btn-primary">Ver</a>
							<form method="POST" action="{{ route('admin.pedidos.marcarEntregado', $pedido) }}" style="display:inline">
								@csrf
								<button class="btn btn-sm btn-info">Marcar entregado</button>
							</form>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif

@endsection
