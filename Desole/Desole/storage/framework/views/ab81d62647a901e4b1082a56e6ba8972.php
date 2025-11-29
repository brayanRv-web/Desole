<?php $__env->startSection('title', 'Menú Completo'); ?>

<?php $__env->startSection('content'); ?>

<section class="container py-5" style="min-height: 100vh;">

    <h2 class="text-center text-light mb-4 fw-bold">Nuestro Menú</h2>
    <p class="text-center text-secondary mb-5">Explora nuestra selección de platillos y bebidas.</p>

    <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($categoria->productos->count() > 0): ?>

        <!-- Nombre de la categoría -->
        <h3 class="text-light fw-semibold mb-3"><?php echo e($categoria->nombre); ?></h3>

        <div class="row g-4 mb-5">

            <?php $__currentLoopData = $categoria->productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

                <div class="card h-100 bg-dark border-0 shadow-sm">

                    <?php if($producto->imagen): ?>
                        <img src="<?php echo e(asset('storage/' . $producto->imagen)); ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                    <?php else: ?>
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="fa-solid fa-mug-saucer text-white-50 fs-1"></i>
                        </div>
                    <?php endif; ?>

                    <div class="card-body text-light d-flex flex-column">

                        <h5 class="card-title fw-bold"><?php echo e($producto->nombre); ?></h5>
                        <p class="card-text text-secondary small"><?php echo e($producto->descripcion); ?></p>

                        <div class="mt-auto">
                            <p class="mb-2 text-light fw-semibold">$<?php echo e(number_format($producto->precio, 2)); ?></p>

                            <button class="btn btn-success w-100 add-btn"
                                data-id="<?php echo e($producto->id); ?>"
                                data-name="<?php echo e($producto->nombre); ?>"
                                data-price="<?php echo e($producto->precio); ?>"
                                data-stock="<?php echo e($producto->stock); ?>"
                                data-imagen="<?php echo e($producto->imagen ?? ''); ?>">
                                <i class="fa-solid fa-cart-plus me-2"></i>Agregar al carrito
                            </button>

                        </div>

                    </div>

                </div>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</section>

<script>
document.addEventListener("click", function(e) {
    const btn = e.target.closest(".add-btn");
    if (!btn) return;
    const item = {
        id: Number(btn.dataset.id),
        nombre: btn.dataset.name,
        precio: Number(btn.dataset.price),
        stock: Number(btn.dataset.stock || 0),
        imagen: btn.dataset.imagen || ''
    };
    if (window.carrito && typeof window.carrito.agregar === 'function') {
        window.carrito.agregar(item);
    } else {
        console.warn('carrito no inicializado al intentar agregar:', item);
    }
});

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('cliente.layout.cliente', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Desole\resources\views/cliente/menu.blade.php ENDPATH**/ ?>