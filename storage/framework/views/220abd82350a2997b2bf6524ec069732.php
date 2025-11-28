<?php $__env->startSection('content'); ?>
	<h1 class="text-2xl font-bold mb-4 text-gray-100">Pedidos</h1>

	<h2 class="mt-4 text-lg font-semibold text-gray-200">Pendientes / En preparación</h2>
	<?php if($pedidosActivos->isEmpty()): ?>
		<p class="text-gray-400">No hay pedidos activos.</p>
	<?php else: ?>
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
				<?php $__currentLoopData = $pedidosActivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr class="border-t border-gray-200 hover:bg-gray-50">
						<td class="px-4 py-2 text-gray-900"><?php echo e($pedido->id); ?></td>
						<td class="px-4 py-2 text-gray-900"><?php echo e($pedido->cliente_nombre ?? 'Sin especificar'); ?></td>
						<td class="px-4 py-2 text-gray-900">$<?php echo e(number_format($pedido->total, 2)); ?></td>
						<td class="px-4 py-2 text-gray-900">
							<span class="px-2 py-1 rounded text-sm font-medium
								<?php if($pedido->estado === 'pendiente'): ?> bg-yellow-200 text-yellow-800
								<?php elseif($pedido->estado === 'preparando'): ?> bg-blue-200 text-blue-800
								<?php else: ?> bg-gray-200 text-gray-800
								<?php endif; ?>">
								<?php echo e(ucfirst($pedido->estado)); ?>

							</span>
						</td>
						<td class="px-4 py-2">
							<a href="<?php echo e(route('admin.pedidos.show', $pedido)); ?>" class="btn btn-sm btn-primary text-sm">Ver</a>
							<?php if($pedido->estado === 'pendiente'): ?>
								<form method="POST" action="<?php echo e(route('admin.pedidos.updateEstado', $pedido)); ?>" style="display:inline">
									<?php echo csrf_field(); ?>
									<input type="hidden" name="estado" value="preparando">
									<button class="btn btn-sm btn-warning text-sm">Preparar</button>
								</form>
							<?php elseif($pedido->estado === 'preparando'): ?>
								<form method="POST" action="<?php echo e(route('admin.pedidos.updateEstado', $pedido)); ?>" style="display:inline">
									<?php echo csrf_field(); ?>
									<input type="hidden" name="estado" value="listo">
									<button class="btn btn-sm btn-success text-sm">Listo</button>
								</form>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	<?php endif; ?>

	<h2 class="mt-6 text-lg font-semibold text-gray-200">Listos para entrega</h2>
	<?php if($pedidosListos->isEmpty()): ?>
		<p class="text-gray-400">No hay pedidos listos.</p>
	<?php else: ?>
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
				<?php $__currentLoopData = $pedidosListos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr class="border-t border-gray-200 hover:bg-gray-50">
						<td class="px-4 py-2 text-gray-900"><?php echo e($pedido->id); ?></td>
						<td class="px-4 py-2 text-gray-900"><?php echo e($pedido->cliente_nombre ?? 'Sin especificar'); ?></td>
						<td class="px-4 py-2 text-gray-900">$<?php echo e(number_format($pedido->total, 2)); ?></td>
						<td class="px-4 py-2">
							<a href="<?php echo e(route('admin.pedidos.show', $pedido)); ?>" class="btn btn-sm btn-primary text-sm">Ver</a>
							<form method="POST" action="<?php echo e(route('admin.pedidos.updateEstado', $pedido)); ?>" style="display:inline">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="estado" value="entregado">
								<button class="btn btn-sm btn-info text-sm">Entregar</button>
							</form>
						</td>
					</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>
	<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Desole\resources\views/admin/pedidos/index.blade.php ENDPATH**/ ?>