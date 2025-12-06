<?php $__env->startSection('content'); ?>
<?php
    $validezColors = [
        true => 'bg-green-500/20 text-green-400 border-green-500',
        false => 'bg-yellow-500/20 text-yellow-400 border-yellow-500',
    ];

    $validezIcons = [
        true => 'fas fa-check-circle',
        false => 'fas fa-exclamation-triangle',
    ];

    $estadoColors = [
        true => 'bg-green-500/20 text-green-400 border-green-500',
        false => 'bg-gray-500/20 text-gray-400 border-gray-500',
    ];

    $estadoIcons = [
        true => 'fas fa-play-circle',
        false => 'fas fa-pause-circle',
    ];

    $estadoTextos = [
        true => 'Activada',
        false => 'Desactivada'
    ];
?>

<div class="flex flex-col space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-green-400 flex items-center gap-3">
                <i class="fas fa-tags text-2xl"></i>
                Gestión de Promociones
            </h2>
            <p class="text-gray-400 mt-2">Administra descuentos y ofertas especiales para productos</p>
        </div>
        
        <a href="<?php echo e(route('admin.promociones.create')); ?>" 
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-green-500/25 transition-all duration-200 flex items-center gap-2 font-semibold group">
            <i class="fas fa-plus-circle group-hover:scale-110 transition-transform"></i>
            Nueva Promoción
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-500/10 border border-green-500/50 text-green-400 px-6 py-4 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle text-green-400"></i>
            <span class="font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-400"></i>
            <span class="font-medium"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <!-- Promotions Counter -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gray-800/30 border border-green-700/20 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-green-600/20 flex items-center justify-center">
                    <i class="fas fa-tags text-green-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Total</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($promociones->count()); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800/30 border border-blue-700/20 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-blue-600/20 flex items-center justify-center">
                    <i class="fas fa-play-circle text-blue-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Activadas</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($promociones->where('activa', true)->count()); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800/30 border border-yellow-700/20 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-yellow-600/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-yellow-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Válidas Ahora</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($promociones->filter(fn($p) => $p->esValida())->count()); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800/30 border border-red-700/20 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-red-600/20 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Con Problemas</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($promociones->filter(fn($p) => !$p->esValida() && $p->activa)->count()); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-400 mt-1"></i>
            <div class="flex-1">
                <h4 class="font-semibold text-blue-300 mb-1">¿Cómo funciona el sistema de promociones?</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-blue-200/80">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                        <span><strong>Estado:</strong> Control manual (Activar/Desactivar)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-yellow-400 rounded-full"></span>
                        <span><strong>Validez:</strong> Automática (fechas + productos activos)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-red-400 rounded-full"></span>
                        <span><strong>Problemas:</strong> Productos inactivos o fuera de fecha</span>
                    </div>
                </div>
                <div class="mt-2 text-xs text-blue-300 flex items-center gap-1">
                    <i class="fas fa-calendar-check"></i>
                    <span><strong>Año de trabajo:</strong> 2025 - Todas las fechas se evalúan en el contexto del año 2025</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Promotions Table -->
    <div class="bg-gray-800/50 rounded-2xl border border-green-700/30 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-green-700/40 to-green-800/20 border-b border-green-600/30">
                    <tr>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-tag mr-2"></i>Promoción
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-percentage mr-2"></i>Descuento
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-boxes mr-2"></i>Productos
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-calendar-alt mr-2"></i>Vigencia
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-toggle-on mr-2"></i>Estado
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-check-double mr-2"></i>Validez
                        </th>
                        <th class="px-6 py-4 text-center text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2"></i>Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    <?php $__empty_1 = true; $__currentLoopData = $promociones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promocion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $esValida = $promocion->esValida();
                        $productosInactivos = $promocion->productos_inactivos->count();
                        $productosActivosCount = $promocion->productosActivos->count();
                        $totalProductos = $promocion->productos->count();
                        
                        // ✅ CORREGIDO: Usar solo la lógica del modelo para determinar validez
                        // Ya no hacemos cálculos separados aquí
                    ?>
                    <tr class="hover:bg-green-900/20 transition-all duration-200 group">
                        <!-- Promotion Name & Details -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-600/20 border border-purple-600/30 flex items-center justify-center">
                                    <i class="fas fa-tag text-purple-400 text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="font-semibold text-white group-hover:text-green-300 transition-colors truncate">
                                        <?php echo e($promocion->nombre); ?>

                                    </div>
                                    <?php if($promocion->descripcion): ?>
                                    <div class="text-xs text-gray-400 mt-1 truncate">
                                        <?php echo e(Str::limit($promocion->descripcion, 60)); ?>

                                    </div>
                                    <?php endif; ?>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs px-2 py-1 rounded-full <?php echo e($promocion->tipo_descuento === 'porcentaje' ? 'bg-blue-500/20 text-blue-400 border border-blue-500/30' : 'bg-orange-500/20 text-orange-400 border border-orange-500/30'); ?>">
                                            <?php echo e($promocion->tipo_descuento === 'porcentaje' ? 'Descuento %' : 'Monto Fijo'); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Discount Details -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-700/30 text-purple-300 border border-purple-600/50">
                                    <?php if($promocion->tipo_descuento === 'porcentaje'): ?>
                                        <i class="fas fa-percentage mr-1 text-xs"></i>
                                        <?php echo e($promocion->valor_descuento); ?>% OFF
                                    <?php else: ?>
                                        <i class="fas fa-dollar-sign mr-1 text-xs"></i>
                                        $<?php echo e(number_format($promocion->valor_descuento, 2)); ?> OFF
                                    <?php endif; ?>
                                </span>
                                <div class="text-xs text-gray-400">
                                    <?php if($promocion->tipo_descuento === 'porcentaje'): ?>
                                        Ej: $100 → $<?php echo e(number_format(100 * (1 - $promocion->valor_descuento/100), 2)); ?>

                                    <?php else: ?>
                                        Descuento fijo por producto
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>

                        <!-- Products Info -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-white font-medium">
                                        <?php echo e($totalProductos); ?> productos
                                    </span>
                                    <?php if($productosActivosCount > 0): ?>
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                                        <?php echo e($productosActivosCount); ?> activos
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <?php if($productosInactivos > 0): ?>
                                <div class="flex items-center gap-1 text-xs text-red-400 bg-red-500/10 px-2 py-1 rounded border border-red-500/20">
                                    <i class="fas fa-exclamation-triangle text-xs"></i>
                                    <span><?php echo e($productosInactivos); ?> producto(s) inactivo(s)</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Dates & Vigencia -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="text-sm">
                                    <div class="text-white"><?php echo e($promocion->fecha_inicio->format('d/m/Y H:i')); ?></div>
                                    <div class="text-gray-400 text-xs">hasta <?php echo e($promocion->fecha_fin->format('d/m/Y H:i')); ?></div>
                                </div>
                                <!-- ✅ CORREGIDO: Mostrar estado basado en la validez del modelo -->
                                <div class="text-xs <?php echo e($esValida ? 'text-green-400' : ($promocion->activa ? 'text-red-400' : 'text-gray-400')); ?> flex items-center gap-1">
                                    <i class="fas fa-clock text-xs"></i>
                                    <?php if(!$promocion->activa): ?>
                                        Desactivada
                                    <?php elseif($esValida): ?>
                                        En vigencia
                                    <?php else: ?>
                                        Expirada
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>

                        <!-- Manual Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="<?php echo e(route('admin.promociones.toggle-status', $promocion)); ?>" method="POST" class="inline-block">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1 rounded-full border text-sm font-medium transition-all duration-200 hover:shadow-lg <?php echo e($estadoColors[$promocion->activa]); ?> hover:scale-105 cursor-pointer group/status tooltip"
                                        title="<?php echo e($promocion->activa ? 'Click para desactivar' : 'Click para activar'); ?>">
                                    <i class="<?php echo e($estadoIcons[$promocion->activa]); ?> mr-1 text-xs group-hover/status:scale-110 transition-transform"></i>
                                    <?php echo e($estadoTextos[$promocion->activa]); ?>

                                </button>
                            </form>
                            <div class="text-xs text-gray-400 mt-1">Control manual</div>
                        </td>

                        <!-- Automatic Validity -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border <?php echo e($validezColors[$esValida]); ?> tooltip"
                                      title="<?php echo e($esValida ? '✅ Promoción funcionando correctamente - Está dentro del tiempo establecido' : '⚠️ Revisar: ' . (!$promocion->activa ? 'Estado desactivado' : 'Fuera del tiempo establecido o productos inactivos')); ?>">
                                    <i class="<?php echo e($validezIcons[$esValida]); ?> mr-1 text-xs"></i>
                                    <?php echo e($esValida ? 'Válida' : 'Revisar'); ?>

                                </span>
                                <div class="text-xs text-gray-400">
                                    <?php echo e($esValida ? 'Dentro del tiempo establecido' : 'Fuera del tiempo establecido'); ?>

                                </div>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Edit Button -->
                                <a href="<?php echo e(route('admin.promociones.edit', $promocion)); ?>" 
                                   class="bg-blue-600/20 hover:bg-blue-600/40 border border-blue-500/50 hover:border-blue-400 text-blue-400 hover:text-blue-300 p-2 rounded-xl transition-all duration-200 group/edit tooltip"
                                   title="Editar promoción">
                                    <i class="fas fa-edit group-hover/edit:scale-110 transition-transform"></i>
                                </a>

                                <!-- Quick Status Toggle -->
                                <form action="<?php echo e(route('admin.promociones.toggle-status', $promocion)); ?>" method="POST" class="inline-block">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" 
                                            class="bg-<?php echo e($promocion->activa ? 'yellow' : 'green'); ?>-600/20 hover:bg-<?php echo e($promocion->activa ? 'yellow' : 'green'); ?>-600/40 border border-<?php echo e($promocion->activa ? 'yellow' : 'green'); ?>-500/50 hover:border-<?php echo e($promocion->activa ? 'yellow' : 'green'); ?>-400 text-<?php echo e($promocion->activa ? 'yellow' : 'green'); ?>-400 hover:text-<?php echo e($promocion->activa ? 'yellow' : 'green'); ?>-300 p-2 rounded-xl transition-all duration-200 group/toggle tooltip"
                                            title="<?php echo e($promocion->activa ? 'Desactivar promoción' : 'Activar promoción'); ?>">
                                        <i class="fas <?php echo e($promocion->activa ? 'fa-pause' : 'fa-play'); ?> group-hover/toggle:scale-110 transition-transform"></i>
                                    </button>
                                </form>

                                <!-- Delete Button -->
                                <form action="<?php echo e(route('admin.promociones.destroy', $promocion)); ?>" method="POST" 
                                      onsubmit="return confirm('¿Está seguro de que desea eliminar permanentemente esta promoción?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="bg-red-600/20 hover:bg-red-600/40 border border-red-500/50 hover:border-red-400 text-red-400 hover:text-red-300 p-2 rounded-xl transition-all duration-200 group/delete tooltip"
                                            title="Eliminar promoción">
                                        <i class="fas fa-trash group-hover/delete:scale-110 transition-transform"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fas fa-tags text-4xl mb-4 text-gray-600"></i>
                                <h3 class="text-lg font-semibold text-gray-400 mb-2">No hay promociones registradas</h3>
                                <p class="text-sm text-gray-500 mb-4">Comienza creando tu primera oferta especial</p>
                                <a href="<?php echo e(route('admin.promociones.create')); ?>" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                                    <i class="fas fa-plus"></i>
                                    Crear Primera Promoción
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($promociones->count() > 0): ?>
        <div class="px-6 py-4 border-t border-gray-700/50 bg-gray-800/30">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">
                    Mostrando <span class="text-green-400 font-semibold"><?php echo e($promociones->count()); ?></span> promociones
                </div>
                <div class="text-xs text-gray-500">
                    <i class="fas fa-sync-alt mr-1"></i>
                    Actualizado: <?php echo e(now()->format('d/m/Y H:i')); ?>

                    <span class="ml-2 text-green-400">• Año 2025</span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.tooltip {
    position: relative;
}

.tooltip:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.9);
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    margin-bottom: 8px;
    max-width: 250px;
    word-wrap: break-word;
    white-space: normal;
    text-align: center;
}

.tooltip:hover::before {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.9);
    margin-bottom: -2px;
    z-index: 1000;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/admin/promociones/index.blade.php ENDPATH**/ ?>