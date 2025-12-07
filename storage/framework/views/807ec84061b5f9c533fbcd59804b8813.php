<?php $__env->startSection('title', 'Reseñas de Clientes'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo e(url()->previous()); ?>" 
           class="bg-gray-700 hover:bg-gray-600 text-white p-3 rounded-xl transition-all duration-200 group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h2 class="text-3xl font-bold text-green-400 flex items-center gap-3">
                <i class="fas fa-star text-2xl"></i>
                Reseñas de Clientes
            </h2>
            <p class="text-gray-400 mt-1">Gestiona las reseñas y comentarios de tus clientes</p>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="bg-green-500/10 border border-green-500/50 text-green-400 px-6 py-4 rounded-xl mb-6">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <span class="font-medium"><?php echo e(session('success')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-gray-800/50 border border-green-700/30 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-700/30 rounded-xl p-6 border border-gray-600/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">No. de Reseñas</p>
                            <p class="text-2xl font-bold text-white mt-1"><?php echo e($reseñas->count()); ?></p>
                        </div>
                        <div class="bg-green-500/20 p-3 rounded-lg">
                            <i class="fas fa-user-check text-green-400 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-700/30 rounded-xl p-6 border border-gray-600/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Promedio</p>
                            <p class="text-2xl font-bold text-white mt-1"><?php echo e(number_format($reseñas->avg('calificacion') ?? 0, 1)); ?>/5</p>
                        </div>
                        <div class="bg-purple-500/20 p-3 rounded-lg">
                            <i class="fas fa-star text-purple-400 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-gray-700/30 rounded-xl p-6 border border-gray-600/50">
                <h3 class="text-lg font-semibold text-green-400 mb-6 flex items-center gap-2">
                    <i class="fas fa-list"></i>
                    Lista de Reseñas
                </h3>

                <div class="overflow-x-auto rounded-xl">
                    <table class="w-full text-white">
                        <thead>
                            <tr class="bg-gray-600/50 border-b border-gray-600">
                                <th class="py-4 px-6 text-left font-semibold text-green-300">Cliente</th>
                                <th class="py-4 px-6 text-left font-semibold text-green-300">Calificación</th>
                                <th class="py-4 px-6 text-left font-semibold text-green-300">Comentario</th>
                                <th class="py-4 px-6 text-left font-semibold text-green-300">Fecha</th>
                                <th class="py-4 px-6 text-left font-semibold text-green-300">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $reseñas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reseña): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-gray-700/50 hover:bg-gray-600/30 transition-all duration-200">
                                <td class="py-4 px-6">
                                    <div>
                                        <strong class="text-white block"><?php echo e($reseña->nombre); ?></strong>
                                        <small class="text-gray-400 text-sm"><?php echo e($reseña->email); ?></small>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center gap-1 bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded-full">
                                                <i class="fas fa-user-check text-xs"></i>
                                                Cliente Registrado
                                            </span>
                                            <br>
                                            <small class="text-blue-400 text-xs">
                                                📞 <?php echo e($reseña->cliente->telefono ?? 'Sin teléfono'); ?>

                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6"><?php echo e($reseña->calificacion); ?>/5</td>
                                <td class="py-4 px-6"><?php echo e($reseña->comentario); ?></td>
                                <td class="py-4 px-6"><?php echo e($reseña->created_at->format('d/m/Y H:i')); ?></td>
                                <td class="py-4 px-6">
                                    <form action="<?php echo e(route('admin.reseñas.destroy', $reseña->id)); ?>" method="POST" class="deleteResenaForm inline-block">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button"
                                                class="deleteResenaBtn bg-red-600/20 hover:bg-red-600/40 border border-red-500/50 text-red-400 hover:text-red-300 px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 group"
                                                data-name="<?php echo e($reseña->nombre); ?>">
                                            <i class="fas fa-trash group-hover:scale-110 transition-transform"></i>
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400">
                                    No hay reseñas aún
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if($reseñas->hasPages()): ?>
                <div class="mt-6 flex justify-center">
                    <div class="bg-gray-700/50 rounded-xl p-4 border border-gray-600/30">
                        <?php echo e($reseñas->links('vendor.pagination.custom')); ?>

                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteResenaModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-gray-900 rounded-2xl p-6 w-96 max-w-full shadow-lg">
        <h3 class="text-lg font-semibold text-white mb-4">Confirmar Eliminación</h3>
        <p class="text-gray-300 mb-6">¿Está seguro de que desea eliminar permanentemente esta reseña? Esta acción no se puede deshacer.</p>
        <div class="flex justify-end gap-3">
            <button id="cancelDeleteResena" class="px-4 py-2 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">Cancelar</button>
            <button id="confirmDeleteResena" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white transition">Eliminar</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteResenaModal');
    const confirmBtn = document.getElementById('confirmDeleteResena');
    const cancelBtn = document.getElementById('cancelDeleteResena');
    let activeForm = null;

    // Abrir modal al dar click en botón de eliminar
    document.querySelectorAll('.deleteResenaBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            activeForm = btn.closest('form');
            modal.classList.remove('hidden');
        });
    });

    // Cancelar eliminación
    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        activeForm = null;
    });

    // Confirmar eliminación
    confirmBtn.addEventListener('click', () => {
        if (activeForm) activeForm.submit();
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/admin/resenas/index.blade.php ENDPATH**/ ?>