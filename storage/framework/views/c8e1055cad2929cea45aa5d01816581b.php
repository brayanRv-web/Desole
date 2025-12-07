<?php $__env->startSection('content'); ?>

    <div class="container mx-auto px-4 py-8">
        <!-- Header & Stats -->
        <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-600 mb-2">
                    Gestión de Pedidos
                </h1>
                <p class="text-gray-400">Administra y actualiza el estado de los pedidos en tiempo real.</p>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-zinc-800/80 p-4 rounded-xl border border-zinc-700/50 shadow-lg flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-yellow-500/20 flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Pendientes</p>
                        <p class="text-2xl font-bold text-white"><?php echo e($pedidosActivos->count()); ?></p>
                    </div>
                </div>
                <div class="bg-zinc-800/80 p-4 rounded-xl border border-zinc-700/50 shadow-lg flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-400 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Listos</p>
                        <p class="text-2xl font-bold text-white"><?php echo e($pedidosListos->count()); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor dinámico para recarga AJAX -->
        <div id="orders-container">
            <!-- Sección: Pendientes / En Preparación -->
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-fire text-blue-400"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white">En Cocina / Activos</h2>
                </div>

                <?php if($pedidosActivos->isEmpty()): ?>
                    <div class="bg-zinc-800/50 rounded-2xl p-12 text-center border border-zinc-700/50 border-dashed">
                        <i class="fas fa-utensils text-4xl text-gray-600 mb-4"></i>
                        <p class="text-gray-400 text-lg">No hay pedidos activos en este momento.</p>
                    </div>
                <?php else: ?>
                    <div class="bg-zinc-800/80 backdrop-blur-md rounded-2xl border border-zinc-700/50 overflow-hidden shadow-xl">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-zinc-900/50 text-gray-400 text-sm uppercase tracking-wider border-b border-zinc-700/50">
                                        <th class="p-6 font-medium"># Pedido</th>
                                        <th class="p-6 font-medium">Cliente</th>
                                        <th class="p-6 font-medium">Detalles</th>
                                        <th class="p-6 font-medium">Total</th>
                                        <th class="p-6 font-medium">Estado</th>
                                        <th class="p-6 font-medium text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-700/50">
                                    <?php $__currentLoopData = $pedidosActivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-zinc-700/30 transition-colors group">
                                            <td class="p-6">
                                                <span class="font-mono text-green-400 font-bold">#<?php echo e($pedido->id); ?></span>
                                                <div class="text-xs text-gray-500 mt-1"><?php echo e($pedido->created_at->diffForHumans()); ?></div>
                                            </td>
                                            <td class="p-6">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-zinc-700 flex items-center justify-center text-gray-300 font-bold text-xs">
                                                        <?php echo e(substr($pedido->cliente_nombre ?? 'C', 0, 1)); ?>

                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-white"><?php echo e($pedido->cliente_nombre ?? 'Cliente Invitado'); ?></p>
                                                        <?php if($pedido->cliente_telefono): ?>
                                                            <p class="text-xs text-gray-500"><i class="fas fa-phone mr-1"></i><?php echo e($pedido->cliente_telefono); ?></p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-6">
                                                <div class="text-sm text-gray-300">
                                                    <?php
                                                        $items = is_string($pedido->items) ? json_decode($pedido->items, true) : $pedido->items;
                                                        $itemCount = is_array($items) ? count($items) : 0;
                                                    ?>
                                                    <span class="badge bg-zinc-700 text-gray-300 px-2 py-1 rounded text-xs mr-2"><?php echo e($itemCount); ?> items</span>
                                                    <span class="text-gray-400 text-xs"><?php echo e($pedido->metodo_pago === 'tarjeta' ? '💳 Tarjeta' : '💵 Efectivo'); ?></span>
                                                </div>
                                            </td>
                                            <td class="p-6">
                                                <span class="text-lg font-bold text-white">$<?php echo e(number_format($pedido->total, 2)); ?></span>
                                            </td>
                                            <td class="p-6">
                                                <?php if($pedido->estado === 'pendiente'): ?>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400 animate-pulse"></span>
                                                        Pendiente
                                                    </span>
                                                <?php elseif($pedido->estado === 'preparando'): ?>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse"></span>
                                                        Preparando
                                                    </span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400 border border-gray-500/20">
                                                        <?php echo e(ucfirst($pedido->estado)); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="p-6 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="<?php echo e(route('admin.pedidos.show', $pedido)); ?>" class="p-2 rounded-lg bg-zinc-700 text-gray-300 hover:bg-zinc-600 hover:text-white transition-colors" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    <?php if($pedido->estado === 'pendiente'): ?>
                                                        <form method="POST" action="<?php echo e(route('admin.pedidos.updateEstado', $pedido)); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="estado" value="preparando">
                                                            <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-medium text-sm transition-all shadow-lg shadow-blue-900/20">
                                                                <i class="fas fa-fire-burner"></i> Preparar
                                                            </button>
                                                        </form>
                                                    <?php elseif($pedido->estado === 'preparando'): ?>
                                                        <form method="POST" action="<?php echo e(route('admin.pedidos.updateEstado', $pedido)); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="estado" value="listo">
                                                            <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-500 text-white font-medium text-sm transition-all shadow-lg shadow-green-900/20">
                                                                <i class="fas fa-check"></i> Listo
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sección: Listos para Entrega -->
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center">
                        <i class="fas fa-bell text-green-400"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Listos para Entrega</h2>
                </div>

                <?php if($pedidosListos->isEmpty()): ?>
                    <div class="bg-zinc-800/50 rounded-2xl p-8 text-center border border-zinc-700/50 border-dashed">
                        <p class="text-gray-500">No hay pedidos esperando entrega.</p>
                    </div>
                <?php else: ?>
                    <div class="bg-zinc-800/80 backdrop-blur-md rounded-2xl border border-zinc-700/50 overflow-hidden shadow-xl">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-zinc-900/50 text-gray-400 text-sm uppercase tracking-wider border-b border-zinc-700/50">
                                        <th class="p-6 font-medium"># Pedido</th>
                                        <th class="p-6 font-medium">Cliente</th>
                                        <th class="p-6 font-medium">Total</th>
                                        <th class="p-6 font-medium text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-700/50">
                                    <?php $__currentLoopData = $pedidosListos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-zinc-700/30 transition-colors">
                                            <td class="p-6">
                                                <span class="font-mono text-green-400 font-bold">#<?php echo e($pedido->id); ?></span>
                                                <div class="text-xs text-gray-500 mt-1"><?php echo e($pedido->created_at->format('h:i A')); ?></div>
                                            </td>
                                            <td class="p-6">
                                                <p class="font-medium text-white"><?php echo e($pedido->cliente_nombre ?? 'Cliente Invitado'); ?></p>
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs bg-green-500/10 text-green-400 border border-green-500/20 mt-1">
                                                    Listo para entregar
                                                </span>
                                            </td>
                                            <td class="p-6">
                                                <span class="text-lg font-bold text-white">$<?php echo e(number_format($pedido->total, 2)); ?></span>
                                            </td>
                                            <td class="p-6 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="<?php echo e(route('admin.pedidos.show', $pedido)); ?>" class="p-2 rounded-lg bg-zinc-700 text-gray-300 hover:bg-zinc-600 hover:text-white transition-colors" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form method="POST" action="<?php echo e(route('admin.pedidos.updateEstado', $pedido)); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="estado" value="entregado">
                                                        <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-sm transition-all shadow-lg shadow-emerald-900/20">
                                                            <i class="fas fa-check-double"></i> Entregar
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Auto-Refresh Script (Listens to Global Layout Event) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Escuchar evento global disparado por admin/layout.blade.php
            document.addEventListener('new-order-received', function(e) {
                console.log('Nuevo pedido detectado, actualizando lista...');
                
                // Recargar contenido dinámicamente
                fetch(window.location.href + (window.location.href.includes('?') ? '&' : '?') + 't=' + new Date().getTime())
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.getElementById('orders-container').innerHTML;
                        document.getElementById('orders-container').innerHTML = newContent;
                        
                        // Opcional: Mostrar un toast específico de "Lista actualizada"
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            background: '#27272a',
                            color: '#fff'
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Lista actualizada'
                        });
                    })
                    .catch(err => console.error('Error recargando contenido:', err));
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/admin/pedidos/index.blade.php ENDPATH**/ ?>