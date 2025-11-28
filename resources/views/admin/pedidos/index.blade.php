@extends('admin.layout')

@section('content')
	<h1 class="text-2xl font-bold mb-4 text-gray-100">Pedidos</h1>

	<h2 class="mt-4 text-lg font-semibold text-gray-200">Pendientes / En preparación</h2>
	@if($pedidosActivos->isEmpty())
		<p class="text-gray-400">No hay pedidos activos.</p>
	@else
		<table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden">
			<thead class="bg-gray-200">
				<tr>
					<th class="px-4 py-2 text-left text-gray-900 font-semibold">#</th>
					<th class="px-4 py-2 text-left text-gray-900 font-semibold">Cliente</th>
					<th class="px-4 py-2 text-left text-gray-900 font-semibold">Total</th>
					<th class="px-4 py-2 text-left text-gray-900 font-semibold">Estado</th>
					<th class="px-4 py-2 text-left text-gray-900 font-semibold">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pedidosActivos as $pedido)
					<tr class="border-t border-gray-200 hover:bg-gray-50">
						<td class="px-4 py-2 text-gray-900">{{ $pedido->id }}</td>
						<td class="px-4 py-2 text-gray-900">{{ $pedido->cliente_nombre ?? 'Sin especificar' }}</td>
						<td class="px-4 py-2 text-gray-900">${{ number_format($pedido->total, 2) }}</td>
						<td class="px-4 py-2 text-gray-900">
							<span class="px-2 py-1 rounded text-sm font-medium
								@if($pedido->estado === 'pendiente') bg-yellow-200 text-yellow-800
								@elseif($pedido->estado === 'preparando') bg-blue-200 text-blue-800
								@else bg-gray-200 text-gray-800
								@endif">
								{{ ucfirst($pedido->estado) }}
							</span>
						</td>
						<td class="px-4 py-2">
							<a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-sm btn-primary text-sm">Ver</a>
							@if($pedido->estado === 'pendiente')
								<form method="POST" action="{{ route('admin.pedidos.updateEstado', $pedido) }}" style="display:inline">
									@csrf
									<input type="hidden" name="estado" value="preparando">
									<button class="btn btn-sm btn-warning text-sm">Preparar</button>
								</form>
							@elseif($pedido->estado === 'preparando')
								<form method="POST" action="{{ route('admin.pedidos.updateEstado', $pedido) }}" style="display:inline">
									@csrf
									<input type="hidden" name="estado" value="listo">
									<button class="btn btn-sm btn-success text-sm">Listo</button>
								</form>
							@endif
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif

	<h2 class="mt-6 text-lg font-semibold text-gray-200">Listos para entrega</h2>
	@if($pedidosListos->isEmpty())
		<p class="text-gray-400">No hay pedidos listos.</p>
	@else
		<table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden">
			<thead class="bg-gray-200">
				<tr>
					<th class="px-4 py-2 text-left text-gray-900 font-semibold">#</th>
					<th class="px-4 py-2 text-left text-gray-900 font-semibold">Cliente</th>
					<th class="px-4 py-2 text-left text-gray-900 font-semibold">Total</th>
					<th class="px-4 py-2 text-left text-gray-900 font-semibold">Acciones</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pedidosListos as $pedido)
					<tr class="border-t border-gray-200 hover:bg-gray-50">
						<td class="px-4 py-2 text-gray-900">{{ $pedido->id }}</td>
						<td class="px-4 py-2 text-gray-900">{{ $pedido->cliente_nombre ?? 'Sin especificar' }}</td>
						<td class="px-4 py-2 text-gray-900">${{ number_format($pedido->total, 2) }}</td>
						<td class="px-4 py-2">
							<a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-sm btn-primary text-sm">Ver</a>
							<form method="POST" action="{{ route('admin.pedidos.updateEstado', $pedido) }}" style="display:inline">
								@csrf
								<input type="hidden" name="estado" value="entregado">
								<button class="btn btn-sm btn-info text-sm">Entregar</button>
							</form>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif

@endsection
