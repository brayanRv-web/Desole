<?php $__env->startSection('content'); ?>
<?php
    use Illuminate\Support\Str;

    $estadoColors = [
        'activo' => 'bg-green-500/20 text-green-400 border-green-500',
        'inactivo' => 'bg-gray-500/20 text-gray-400 border-gray-500',
        'agotado' => 'bg-red-500/20 text-red-400 border-red-500',
    ];

    $estadoIcons = [
        'activo' => 'fas fa-check-circle',
        'inactivo' => 'fas fa-pause-circle',
        'agotado' => 'fas fa-times-circle',
    ];
?>

<div class="flex flex-col space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-green-400 flex items-center gap-3">
                <i class="fas fa-box text-2xl"></i>
                Gestión de Productos
            </h2>
            <p class="text-gray-400 mt-2">Administra y organiza los productos de tu cafetería</p>
        </div>
        
        <a href="<?php echo e(route('admin.productos.create')); ?>" 
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-green-500/25 transition-all duration-200 flex items-center gap-2 font-semibold group">
            <i class="fas fa-plus-circle group-hover:scale-110 transition-transform"></i>
            Nuevo Producto
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

    <!-- ✅ ALERTA DE STOCK BAJO - Solo muestra si hay productos con stock bajo -->
    <?php if($stockBajo > 0): ?>
    <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-yellow-500/20 flex items-center justify-center">
                    <i class="fas fa-bell text-yellow-400"></i>
                </div>
                <div>
                    <p class="text-yellow-400 font-semibold">⚠️ Alertas de Stock Bajo</p>
                    <p class="text-yellow-300 text-sm">Tienes <?php echo e($stockBajo); ?> producto(s) que necesitan reposición</p>
                </div>
            </div>
            <button onclick="toggleStockBajoList()" 
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                <i class="fas fa-list mr-1"></i> Ver Lista
            </button>
        </div>

        <!-- Lista desplegable de productos con stock bajo -->
        <div id="stockBajoList" class="mt-4 hidden">
            <div class="grid gap-2">
                <?php $__currentLoopData = $productosStockBajo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 bg-yellow-500/5 rounded-lg border border-yellow-500/20">
                    <div class="flex items-center gap-3">
                        <?php if($producto->imagen): ?>
                        <img src="<?php echo e(asset('storage/' . $producto->imagen)); ?>" 
                             class="w-8 h-8 rounded object-cover">
                        <?php endif; ?>
                        <div>
                            <p class="text-white font-medium"><?php echo e($producto->nombre); ?></p>
                            <p class="text-yellow-300 text-xs">
                                <?php echo e($producto->categoria->nombre ?? 'Sin categoría'); ?> • 
                                <span class="font-bold"><?php echo e($producto->stock); ?> unidades</span>
                            </p>
                        </div>
                    </div>
                    <a href="<?php echo e(route('admin.productos.edit', $producto)); ?>" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-xs transition-colors">
                        <i class="fas fa-edit mr-1"></i> Reponer
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- ✅ TABLA DE PRODUCTOS (ORIGINAL) -->
    <div class="bg-gray-800/50 rounded-2xl border border-green-700/30 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-green-700/40 to-green-800/20 border-b border-green-600/30">
                    <tr>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-cube mr-2"></i>Producto
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-tags mr-2"></i>Categoría
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-align-left mr-2"></i>Descripción
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-dollar-sign mr-2"></i>Precio
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-boxes mr-2"></i>Stock
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-circle mr-2"></i>Estado
                        </th>
                        <th class="px-6 py-4 text-center text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2"></i>Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    <?php $__empty_1 = true; $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-green-900/20 transition-all duration-200 group">
                        <!-- Product Name -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <?php if($producto->imagen): ?>
                                    <img src="<?php echo e(asset('storage/' . $producto->imagen)); ?>" 
                                         alt="<?php echo e($producto->nombre); ?>"
                                         class="w-10 h-10 rounded-lg object-cover border border-green-600/30">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-lg bg-green-600/20 border border-green-600/30 flex items-center justify-center">
                                        <i class="fas fa-coffee text-green-400 text-sm"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="font-semibold text-white group-hover:text-green-300 transition-colors">
                                        <?php echo e($producto->nombre); ?>

                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Category -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-700/50 text-gray-300 border border-gray-600/50">
                                <i class="fas fa-tag mr-1 text-xs"></i>
                                <?php echo e($producto->categoria->nombre ?? 'Sin categoría'); ?>

                            </span>
                        </td>

                        <!-- Description -->
                        <td class="px-6 py-4 max-w-xs">
                            <div class="text-sm text-gray-400 leading-relaxed">
                                <?php echo e($producto->descripcion ? Str::limit($producto->descripcion, 80, '...') : 'Sin descripción'); ?>

                            </div>
                        </td>

                        <!-- Price -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold text-green-400">
                                $<?php echo e(number_format($producto->precio, 2)); ?>

                            </span>
                        </td>

                        <!-- Stock -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-bold <?php echo e($producto->stock > 0 ? 'text-green-400' : 'text-red-400'); ?>">
                                    <?php echo e($producto->stock); ?>

                                </span>
                                <?php if($producto->stock <= 5 && $producto->stock > 0): ?>
                                    <span class="text-xs text-yellow-400 bg-yellow-500/20 px-2 py-1 rounded-full">
                                        <i class="fas fa-exclamation-triangle"></i> Bajo stock
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Status - VERSIÓN CORREGIDA -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="<?php echo e(route('admin.productos.updateEstado', $producto)); ?>" method="POST" id="form-estado-<?php echo e($producto->id); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <div class="relative">
                                    <select name="estado" 
                                            data-producto-id="<?php echo e($producto->id); ?>"
                                            onchange="actualizarEstado(this)"
                                            class="w-full px-4 py-2 rounded-xl border text-sm font-medium transition-all duration-200 cursor-pointer appearance-none bg-gray-800 text-white <?php echo e($estadoColors[$producto->estado] ?? 'border-gray-500'); ?>">
                                        <option value="activo" <?php echo e($producto->estado == 'activo' ? 'selected' : ''); ?>>🟢 Activo</option>
                                        <option value="inactivo" <?php echo e($producto->estado == 'inactivo' ? 'selected' : ''); ?>>⚪ Inactivo</option>
                                        <option value="agotado" <?php echo e($producto->estado == 'agotado' ? 'selected' : ''); ?>>🔴 Agotado</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Edit Button -->
                                <a href="<?php echo e(route('admin.productos.edit', $producto)); ?>" 
                                   class="bg-blue-600/20 hover:bg-blue-600/40 border border-blue-500/50 hover:border-blue-400 text-blue-400 hover:text-blue-300 p-2 rounded-xl transition-all duration-200 group/edit tooltip"
                                   title="Editar producto">
                                    <i class="fas fa-edit group-hover/edit:scale-110 transition-transform"></i>
                                </a>

                                <!-- Delete Button -->
                                <form action="<?php echo e(route('admin.productos.destroy', $producto)); ?>" method="POST" 
                                      onsubmit="return confirm('¿Está seguro de que desea eliminar permanentemente este producto?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="bg-red-600/20 hover:bg-red-600/40 border border-red-500/50 hover:border-red-400 text-red-400 hover:text-red-300 p-2 rounded-xl transition-all duration-200 group/delete tooltip"
                                            title="Eliminar producto">
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
                                <i class="fas fa-box-open text-4xl mb-4 text-gray-600"></i>
                                <h3 class="text-lg font-semibold text-gray-400 mb-2">No hay productos registrados</h3>
                                <p class="text-sm text-gray-500 mb-4">Comienza agregando tu primer producto al menú</p>
                                <a href="<?php echo e(route('admin.productos.create')); ?>" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                                    <i class="fas fa-plus"></i>
                                    Crear Primer Producto
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Simple Counter (sin paginación) -->
        <?php if($productos->count() > 0): ?>
        <div class="px-6 py-4 border-t border-gray-700/50 bg-gray-800/30">
            <div class="text-center text-sm text-gray-400">
                Mostrando <span class="text-green-400 font-semibold"><?php echo e($productos->count()); ?></span> productos en total
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Toggle para mostrar/ocultar lista de stock bajo
function toggleStockBajoList() {
    const list = document.getElementById('stockBajoList');
    if (list) {
        list.classList.toggle('hidden');
    }
}

// Función para actualizar estado
function actualizarEstado(selectElement) {
    const productoId = selectElement.getAttribute('data-producto-id');
    const form = document.getElementById(`form-estado-${productoId}`);
    if (form && selectElement.value) {
        form.submit();
    }
}

// Mostrar notificación toast si hay stock bajo
document.addEventListener('DOMContentLoaded', function() {
    const stockBajoCount = <?php echo $stockBajo; ?>;
    if (stockBajoCount > 0) {
        showStockNotification(stockBajoCount);
    }
});

function showStockNotification(count) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-yellow-500 text-white p-4 rounded-lg shadow-lg z-50 animate-bounce';
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-yellow-600 flex items-center justify-center">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <p class="font-semibold">⚠️ Stock Bajo</p>
                <p class="text-sm">${count} producto(s) necesitan atención</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Prevenir envíos vacíos
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form[id^="form-estado-"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const select = this.querySelector('select[name="estado"]');
            if (!select || !select.value) {
                e.preventDefault();
                alert('Por favor selecciona un estado válido');
            }
        });
    });
});
</script>

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
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    margin-bottom: 5px;
}

.tooltip:hover::before {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.8);
    margin-bottom: -5px;
    z-index: 1000;
}

.animate-bounce {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.animate-ping {
    animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
}

@keyframes ping {
    75%, 100% {
        transform: scale(2);
        opacity: 0;
    }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/admin/productos/index.blade.php ENDPATH**/ ?>