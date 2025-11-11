@extends('admin.layout')

@section('content')
<div class="max-w-5xl mx-auto">
	<!-- Header con número de pedido y estado -->
	<div class="mb-8 flex items-center justify-between">
		<div>
			<h1 class="text-4xl font-bold text-white mb-2">
				<i class="fas fa-shopping-bag text-green-500 mr-2"></i> Pedido #{{ $pedido->id }}
			</h1>
			<p class="text-gray-400">Detalles completos del pedido y seguimiento de estado</p>
		</div>
		<div class="px-6 py-3 bg-green-900/30 border border-green-600 rounded-lg">
			<p class="text-gray-300 text-sm">Estado actual:</p>
			<p class="text-xl font-bold text-green-400 capitalize">{{ str_replace('_', ' ', $pedido->status) }}</p>
		</div>
	</div>

	<!-- Grid de información del cliente -->
	<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
		<!-- Tarjeta de información del cliente -->
		<div class="bg-gray-900 border border-gray-700 rounded-lg p-6 shadow-lg hover:border-green-600 transition">
			<h2 class="text-lg font-bold text-green-400 mb-6 flex items-center gap-2">
				<i class="fas fa-user-circle text-2xl"></i> Información del Cliente
			</h2>
			<div class="space-y-5">
				<div class="bg-gray-800/50 rounded-lg p-4 border-l-4 border-green-500">
					<p class="text-gray-500 text-xs font-semibold uppercase tracking-wide">Nombre del Cliente</p>
					<p class="text-white font-bold text-xl mt-2">
						<i class="fas fa-id-card text-green-400 mr-2"></i>
						{{ $pedido->cliente_nombre ?? 'No disponible' }}
					</p>
				</div>
				<div class="bg-gray-800/50 rounded-lg p-4 border-l-4 border-blue-500">
					<p class="text-gray-500 text-xs font-semibold uppercase tracking-wide">Teléfono</p>
					<p class="text-white font-bold text-xl mt-2">
						<i class="fas fa-phone text-blue-400 mr-2"></i>
						{{ $pedido->cliente_telefono ?? 'No disponible' }}
					</p>
					@if($pedido->cliente_telefono)
						<a href="tel:{{ $pedido->cliente_telefono }}" class="text-blue-400 text-sm hover:text-blue-300 mt-2 inline-block">
							<i class="fas fa-external-link-alt mr-1"></i> Llamar
						</a>
					@endif
				</div>
				<div class="bg-gray-800/50 rounded-lg p-4 border-l-4 border-amber-500">
					<p class="text-gray-500 text-xs font-semibold uppercase tracking-wide">Dirección de Entrega</p>
					<p class="text-white font-bold text-lg mt-2">
						<i class="fas fa-map-marker-alt text-amber-400 mr-2"></i>
						{{ $pedido->direccion ?? 'No disponible' }}
					</p>
				</div>
			</div>
		</div>

		<!-- Tarjeta de resumen del pedido -->
		<div class="bg-gray-900 border border-gray-700 rounded-lg p-6 shadow-lg hover:border-green-600 transition">
			<h2 class="text-lg font-bold text-green-400 mb-4 flex items-center gap-2">
				<i class="fas fa-list-check"></i> Resumen del Pedido
			</h2>
			<div class="space-y-4">
				<div class="flex justify-between items-center pb-3 border-b border-gray-700">
					<p class="text-gray-400">Cantidad de items:</p>
					<span class="text-white font-bold text-lg">{{ count($pedido->items ?? []) }}</span>
				</div>
				<div class="flex justify-between items-center pb-3 border-b border-gray-700">
					<p class="text-gray-400">Total:</p>
					<span class="text-green-400 font-bold text-2xl">${{ number_format($pedido->total, 2) }}</span>
				</div>
				@if($pedido->created_at)
					<div class="flex justify-between items-center">
						<p class="text-gray-400">Fecha del pedido:</p>
						<span class="text-white font-semibold">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
					</div>
				@endif
			</div>
		</div>
	</div>

	<!-- Sección de items -->
	<div class="bg-gray-900 border border-gray-700 rounded-lg p-6 shadow-lg mb-8">
		<h2 class="text-xl font-bold text-green-400 mb-6 flex items-center gap-2">
			<i class="fas fa-package"></i> Productos en el Pedido
		</h2>

		@php $items = $pedido->items ?? []; @endphp
		@if(empty($items))
			<div class="bg-gray-800 border border-dashed border-gray-600 rounded-lg p-8 text-center">
				<i class="fas fa-inbox text-gray-500 text-3xl mb-3"></i>
				<p class="text-gray-400">No hay items registrados en este pedido.</p>
			</div>
		@else
			<div class="overflow-x-auto">
				<table class="w-full">
					<thead>
						<tr class="border-b border-gray-700 bg-gray-800">
							<th class="px-4 py-3 text-left text-gray-300 font-semibold">#ID</th>
							<th class="px-4 py-3 text-left text-gray-300 font-semibold">Producto</th>
							<th class="px-4 py-3 text-center text-gray-300 font-semibold">Cantidad</th>
							<th class="px-4 py-3 text-right text-gray-300 font-semibold">Precio unitario</th>
							<th class="px-4 py-3 text-right text-gray-300 font-semibold">Subtotal</th>
						</tr>
					</thead>
					<tbody>
						@foreach($items as $item)
							<tr class="border-b border-gray-700 hover:bg-gray-800 transition">
								<td class="px-4 py-3 text-gray-400">{{ $item['producto_id'] ?? '-' }}</td>
								<td class="px-4 py-3 text-white font-semibold">
									{{ $item['nombre'] ?? ($productos[$item['producto_id']]->nombre ?? 'Desconocido') }}
								</td>
								<td class="px-4 py-3 text-center text-white">
									<span class="bg-green-900/40 px-3 py-1 rounded-full text-green-400 font-bold">
										{{ $item['cantidad'] ?? 0 }}
									</span>
								</td>
								<td class="px-4 py-3 text-right text-white">${{ number_format($item['precio'] ?? 0, 2) }}</td>
								<td class="px-4 py-3 text-right text-green-400 font-bold">
									${{ number_format(($item['precio'] ?? 0) * ($item['cantidad'] ?? 0), 2) }}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@endif
	</div>

	<!-- Sección de acciones de estado -->
	<div class="bg-gray-900 border border-gray-700 rounded-lg p-6 shadow-lg">
		<h2 class="text-lg font-bold text-green-400 mb-4 flex items-center gap-2">
			<i class="fas fa-tasks"></i> Cambiar Estado del Pedido
		</h2>

		<div class="space-y-3">
			@if($pedido->status === 'pendiente')
				<form method="POST" action="{{ route('admin.pedidos.updateEstado', $pedido) }}" class="inline">
					@csrf
					<input type="hidden" name="status" value="preparando">
					<button type="submit" class="btn btn-warning w-full md:w-auto flex items-center justify-center gap-2">
						<i class="fas fa-hourglass-start"></i> Iniciar preparación
					</button>
				</form>
				<p class="text-gray-400 text-sm mt-2">El pedido está en espera. Presiona el botón para comenzar su preparación.</p>

			@elseif($pedido->status === 'preparando')
				<form method="POST" action="{{ route('admin.pedidos.updateEstado', $pedido) }}" class="inline">
					@csrf
					<input type="hidden" name="status" value="listo">
					<button type="submit" class="btn btn-success w-full md:w-auto flex items-center justify-center gap-2">
						<i class="fas fa-check-circle"></i> Marcar como listo
					</button>
				</form>
				<p class="text-gray-400 text-sm mt-2">El pedido está siendo preparado. Presiona el botón cuando esté listo para entrega.</p>

			@elseif($pedido->status === 'listo')
				<form method="POST" action="{{ route('admin.pedidos.updateEstado', $pedido) }}" class="inline">
					@csrf
					<input type="hidden" name="status" value="entregado">
					<button type="submit" class="btn btn-info w-full md:w-auto flex items-center justify-center gap-2">
						<i class="fas fa-truck"></i> Marcar como entregado
					</button>
				</form>
				<p class="text-gray-400 text-sm mt-2">El pedido está listo. Presiona el botón cuando sea entregado al cliente.</p>

			@else
				<div class="bg-green-900/30 border border-green-600 rounded-lg p-4">
					<p class="text-green-400 font-semibold flex items-center gap-2">
						<i class="fas fa-check-double"></i> Este pedido ha sido entregado
					</p>
					<p class="text-gray-400 text-sm mt-2">No hay acciones pendientes para este pedido.</p>
				</div>
			@endif
		</div>
	</div>

	<!-- Botón de regreso -->
	<div class="mt-8 text-center">
		<a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline-light px-6 py-2 inline-flex items-center gap-2">
			<i class="fas fa-arrow-left"></i> Volver a pedidos
		</a>
	</div>
</div>

@endsection
