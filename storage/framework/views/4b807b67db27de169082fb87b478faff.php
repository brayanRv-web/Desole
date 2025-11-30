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
                            <p class="text-gray-400 text-sm">Total Reseñas</p>
                            <p class="text-2xl font-bold text-white mt-1"><?php echo e($reseñas->total()); ?></p>
                        </div>
                        <div class="bg-blue-500/20 p-3 rounded-lg">
                            <i class="fas fa-comments text-blue-400 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-700/30 rounded-xl p-6 border border-gray-600/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Clientes Registrados</p>
                            <p class="text-2xl font-bold text-white mt-1">
                                <?php echo e($reseñas->where('tipo_cliente', 'registrado')->count()); ?>

                            </p>
                        </div>
                        <div class="bg-green-500/20 p-3 rounded-lg">
                            <i class="fas fa-user-check text-green-400 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-700/30 rounded-xl p-6 border border-gray-600/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Visitantes</p>
                            <p class="text-2xl font-bold text-white mt-1">
                                <?php echo e($reseñas->where('tipo_cliente', 'anonimo')->count()); ?>

                            </p>
                        </div>
                        <div class="bg-yellow-500/20 p-3 rounded-lg">
                            <i class="fas fa-user-clock text-yellow-400 text-xl"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-700/30 rounded-xl p-6 border border-gray-600/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Promedio</p>
                            <p class="text-2xl font-bold text-white mt-1">
                                <?php echo e(number_format($reseñas->avg('calificacion') ?? 0, 1)); ?>/5
                            </p>
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
                                <th class="py-4 px-6 text-left font-semibold text-green-300">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-user text-xs"></i>
                                        Cliente
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left font-semibold text-green-300">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-star text-xs"></i>
                                        Calificación
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left font-semibold text-green-300">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-comment text-xs"></i>
                                        Comentario
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left font-semibold text-green-300">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar text-xs"></i>
                                        Fecha
                                    </div>
                                </th>
                                <th class="py-4 px-6 text-left font-semibold text-green-300">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-cog text-xs"></i>
                                        Acciones
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $reseñas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reseña): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-gray-700/50 hover:bg-gray-600/30 transition-all duration-200">
                                <td class="py-4 px-6">
                                    <div>
                                        <strong class="text-white block"><?php echo e($reseña->nombre); ?></strong>
                                        <?php if($reseña->email): ?>
                                        <small class="text-gray-400 text-sm"><?php echo e($reseña->email); ?></small>
                                        <?php endif; ?>
                                        
                                        
                                        <div class="mt-1">
                                            <?php if($reseña->tipo_cliente === 'registrado' && $reseña->cliente): ?>
                                                <span class="inline-flex items-center gap-1 bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded-full">
                                                    <i class="fas fa-user-check text-xs"></i>
                                                    Cliente Registrado
                                                </span>
                                                <br>
                                                <small class="text-blue-400 text-xs">
                                                    📞 <?php echo e($reseña->cliente->telefono ?? 'Sin teléfono'); ?>

                                                </small>
                                                <br>
                                                <small class="text-yellow-400 text-xs">
                                                    🛒 <?php echo e($reseña->cliente->total_pedidos); ?> pedidos
                                                </small>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 bg-gray-500/20 text-gray-400 text-xs px-2 py-1 rounded-full">
                                                    <i class="fas fa-user-clock text-xs"></i>
                                                    Visitante
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <form action="<?php echo e(route('admin.resenas.destroy', $reseña->id)); ?>" 
                                        method="POST" class="inline-block">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="bg-red-600/20 hover:bg-red-600/40 border border-red-500/50 text-red-400 hover:text-red-300 px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 group"
                                                onclick="return confirmDelete()">
                                            <i class="fas fa-trash group-hover:scale-110 transition-transform"></i>
                                            Eliminar
                                        </button>
                                    </form>

                                    
                                    <?php if($reseña->tipo_cliente === 'registrado' && $reseña->cliente): ?>
                                    <a href="<?php echo e(route('admin.clientes.show', $reseña->cliente->id)); ?>" 
                                    class="bg-blue-600/20 hover:bg-blue-600/40 border border-blue-500/50 text-blue-400 hover:text-blue-300 px-4 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 group"
                                    title="Ver perfil del cliente">
                                        <i class="fas fa-eye group-hover:scale-110 transition-transform"></i>
                                        Perfil
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-comments text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">No hay reseñas aún</p>
                                        <p class="text-sm mt-1">Los comentarios de tus clientes aparecerán aquí</p>
                                    </div>
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

    <!-- Quick Tips -->
    <div class="mt-8 bg-blue-500/10 border border-blue-500/30 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-blue-400 mb-3 flex items-center gap-2">
            <i class="fas fa-lightbulb"></i>
            Consejos para gestionar reseñas
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-300">
            <div class="flex items-start gap-3">
                <i class="fas fa-check text-blue-400 mt-1"></i>
                <p>Revisa regularmente las reseñas para conocer la opinión de tus clientes</p>
            </div>
            <div class="flex items-start gap-3">
                <i class="fas fa-check text-blue-400 mt-1"></i>
                <p>Responde a los comentarios negativos de manera profesional</p>
            </div>
            <div class="flex items-start gap-3">
                <i class="fas fa-check text-blue-400 mt-1"></i>
                <p>Utiliza el feedback para mejorar tus productos y servicios</p>
            </div>
            <div class="flex items-start gap-3">
                <i class="fas fa-check text-blue-400 mt-1"></i>
                <p>Elimina solo reseñas inapropiadas o spam</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar comentario completo -->
<div id="commentModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 border border-green-700/30 rounded-2xl p-6 max-w-lg w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-green-400 flex items-center gap-2">
                <i class="fas fa-comment"></i>
                Comentario Completo
            </h3>
            <button type="button" onclick="closeCommentModal()" 
                    class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="bg-gray-700/30 rounded-xl p-4 border border-gray-600/50">
            <p id="fullComment" class="text-gray-300 whitespace-pre-wrap"></p>
        </div>
        <div class="flex justify-end mt-4">
            <button type="button" onclick="closeCommentModal()"
                    class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-xl transition-all duration-200">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    return confirm('¿Estás seguro de que deseas eliminar esta reseña? Esta acción no se puede deshacer.');
}

function showFullComment(comment) {
    document.getElementById('fullComment').textContent = comment;
    document.getElementById('commentModal').classList.remove('hidden');
}

function closeCommentModal() {
    document.getElementById('commentModal').classList.add('hidden');
}

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCommentModal();
    }
});

// Cerrar modal haciendo clic fuera
document.getElementById('commentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCommentModal();
    }
});
</script>

<style>
/* Custom scrollbar for table */
.table-responsive {
    scrollbar-width: thin;
    scrollbar-color: #4ade80 #1f2937;
}

.table-responsive::-webkit-scrollbar {
    height: 6px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #1f2937;
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #4ade80;
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #22c55e;
}

/* Smooth transitions */
button, a {
    transition: all 0.2s ease-in-out;
}

/* Hover effects */
tr:hover {
    transform: translateY(-1px);
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Desole\resources\views/admin/resenas/index.blade.php ENDPATH**/ ?>