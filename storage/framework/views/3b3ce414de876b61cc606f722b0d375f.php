<?php $__env->startSection('content'); ?>
<?php
    // Calcular estado del local
    $horarioHoy = $horarios->firstWhere('dia_semana', strtolower(now()->isoFormat('dddd')));
    $estaAbierto = $horarioHoy && $horarioHoy->activo && $horarioHoy->estaAbierto();
?>

<!-- Header Mejorado -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h2 class="text-3xl font-bold text-green-400">
            <i class="fas fa-tachometer-alt mr-2"></i>Panel Principal
        </h2>
        <p class="text-gray-400 mt-1">Resumen operativo de Désolé</p>
    </div>
    <div class="text-right">
        <div class="text-sm text-gray-400">
            <i class="fas fa-calendar-alt mr-1"></i> <?php echo e(now()->format('d/m/Y')); ?>

        </div>
        <div class="text-xs text-<?php echo e($estaAbierto ? 'green' : 'red'); ?>-400 mt-1">
            <i class="fas fa-circle mr-1"></i>
            <?php echo e($estaAbierto ? 'Abierto ahora' : 'Cerrado'); ?>

        </div>
    </div>
</div>

<!-- Sección de Acciones Rápidas -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-green-400 mb-4 flex items-center">
        <i class="fas fa-bolt mr-2"></i> Acciones Rápidas
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
        <a href="<?php echo e(route('admin.productos.create')); ?>" class="bg-gray-800 hover:bg-gray-750 p-4 rounded-lg border border-green-600/40 hover:border-green-500 transition-all duration-200 flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-green-600/20 flex items-center justify-center group-hover:bg-green-600/30 transition">
                <i class="fas fa-plus text-green-400"></i>
            </div>
            <div>
                <p class="font-medium text-white">Nuevo Producto</p>
                <p class="text-xs text-gray-400">Agregar al menú</p>
            </div>
        </a>
        
        <a href="<?php echo e(route('admin.usuarios.create')); ?>" class="bg-gray-800 hover:bg-gray-750 p-4 rounded-lg border border-purple-600/40 hover:border-purple-500 transition-all duration-200 flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-purple-600/20 flex items-center justify-center group-hover:bg-purple-600/30 transition">
                <i class="fas fa-user-plus text-purple-400"></i>
            </div>
            <div>
                <p class="font-medium text-white">Nuevo Personal</p>
                <p class="text-xs text-gray-400">Agregar usuario</p>
            </div>
        </a>
        
        <a href="<?php echo e(route('admin.promociones.create')); ?>" class="bg-gray-800 hover:bg-gray-750 p-4 rounded-lg border border-yellow-600/40 hover:border-yellow-500 transition-all duration-200 flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-yellow-600/20 flex items-center justify-center group-hover:bg-yellow-600/30 transition">
                <i class="fas fa-tag text-yellow-400"></i>
            </div>
            <div>
                <p class="font-medium text-white">Nueva Promoción</p>
                <p class="text-xs text-gray-400">Crear oferta</p>
            </div>
        </a>

        <a href="<?php echo e(route('admin.horarios.index')); ?>" class="bg-gray-800 hover:bg-gray-750 p-4 rounded-lg border border-indigo-600/40 hover:border-indigo-500 transition-all duration-200 flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-indigo-600/20 flex items-center justify-center group-hover:bg-indigo-600/30 transition">
                <i class="fas fa-clock text-indigo-400"></i>
            </div>
            <div>
                <p class="font-medium text-white">Horarios</p>
                <p class="text-xs text-gray-400">Configurar</p>
            </div>
        </a>
        
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-600/40 flex items-center gap-3 opacity-70 cursor-not-allowed">
            <div class="w-10 h-10 rounded-full bg-gray-600/20 flex items-center justify-center">
                <i class="fas fa-chart-bar text-gray-400"></i>
            </div>
            <div>
                <p class="font-medium text-gray-400">Reportes</p>
                <p class="text-xs text-gray-500">Próximamente</p>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Desole\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>