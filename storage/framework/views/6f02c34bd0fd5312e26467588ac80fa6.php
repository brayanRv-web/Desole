

<?php $__env->startSection('content'); ?>
<div class="container py-4">

    <h2 class="text-center mb-4">Todas las Reseñas</h2>

    <?php if($reseñas->count() > 0): ?>

        <?php $__currentLoopData = $reseñas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reseña): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-1">
                        <?php echo e($reseña->nombre); ?>

                        <span class="text-warning">
                            <?php for($i = 1; $i <= $reseña->calificacion; $i++): ?>
                                ★
                            <?php endfor; ?>
                        </span>
                    </h5>

                    <p class="mb-2 text-muted small">
                        <?php echo e($reseña->created_at->format('d/m/Y')); ?>

                    </p>

                    <p><?php echo e($reseña->comentario); ?></p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($reseñas->links()); ?>

        </div>

    <?php else: ?>
        <div class="alert alert-info text-center">
            No hay reseñas disponibles por ahora.
        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/public/secciones/reseñas_publicas.blade.php ENDPATH**/ ?>