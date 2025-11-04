@extends('admin.layout')

@section('content')
	<div class="flex flex-col gap-6">
		<div class="flex items-center justify-between">
			<div>
				<h2 class="text-3xl font-bold text-green-400">Pedidos</h2>
				<p class="text-gray-400">Panel de pedidos — aquí el empleado puede ver y gestionar pedidos.</p>
			</div>
		</div>

		<div class="bg-gray-800/40 border border-green-700/30 rounded-xl p-6">
			<p class="text-gray-400">Aún no hay una interfaz completa para pedidos. Este es un placeholder para el panel del empleado.</p>
			<p class="text-sm text-gray-500 mt-3">Puedes implementar aquí la lista de pedidos, búsqueda y acciones (marcar como preparado/entregado).</p>
		</div>
	</div>
@endsection
