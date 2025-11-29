<?php $__env->startSection('title', 'Menú'); ?>

<?php $__env->startSection('hide-footer'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/desole.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/hero.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/carrito.css')); ?>">
    <style>
        /* Make Menu page background match Home hero background */
        body {
            background: linear-gradient(135deg, #0f0f0f, #191919) !important;
            /* keep text color consistent */
            color: var(--color-text);
        }
        /* If layout applies a min-height to main, ensure contrast similar to home */
        main { background: transparent; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

        

        <?php if(auth()->guard('cliente')->check()): ?>
        <!-- INFORMACIÓN DEL CLIENTE EN MENÚ -->
        <section class="cliente-hero">
            <div class="container mx-auto">
                <div class="cliente-welcome" data-aos="fade-up">
                    <h2>¡Hola, <?php echo e(Auth::guard('cliente')->user()->nombre); ?>! 👋</h2>
                    <p>Bienvenido de nuevo, echa un vistazo a nuestro menú completo</p>
                    <div class="cliente-stats">
                        <div class="stat-card">
                            <i class="fas fa-gift"></i>
                            <span class="stat-number"><?php echo e($promociones->count() ?? 0); ?></span>
                            <span class="stat-label">Promociones activas</span>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-star"></i>
                            <span class="stat-number"><?php echo e(Auth::guard('cliente')->user()->puntos_fidelidad ?? 0); ?></span>
                            <span class="stat-label">Puntos fidelidad</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <main class="container mx-auto px-4 py-8">

            <!-- Productos (solo listado simple) -->
        <?php if($productos->isNotEmpty()): ?>
            <div class="productos-grid">
                <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="producto-card" data-producto-id="<?php echo e($producto->id); ?>"
                             data-nombre="<?php echo e(htmlspecialchars($producto->nombre, ENT_QUOTES)); ?>"
                             data-precio="<?php echo e(number_format($producto->precio, 2, '.', '')); ?>"
                             data-stock="<?php echo e($producto->stock ?? 0); ?>"
                             data-imagen="<?php echo e($producto->imagen ? asset($producto->imagen) : asset('assets/placeholder.svg')); ?>">
                        <div class="producto-img">
                            <img src="<?php echo e($producto->imagen ? asset($producto->imagen) : asset('assets/placeholder.svg')); ?>" alt="<?php echo e($producto->nombre); ?>">
                        </div>
                        <div class="producto-info">
                            <h3><?php echo e($producto->nombre); ?></h3>
                            <p class="producto-desc"><?php echo e(Str::limit($producto->descripcion, 120)); ?></p>
                            <div class="producto-precio">$<?php echo e(number_format($producto->precio, 2)); ?></div>
                            <div class="producto-actions">
                                <button class="btn-agregar-carrito" type="button">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800/50 mb-4">
                    <i class="fas fa-utensils text-2xl text-gray-500"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No hay productos disponibles</h3>
                <p class="text-gray-400">No se encontraron productos en esta categoría.</p>
            </div>
        <?php endif; ?>

        <!-- Paginación -->
        <?php if($productos->hasPages()): ?>
            <div class="mt-8">
                <?php echo e($productos->links()); ?>

            </div>
        <?php endif; ?>

    </main>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.AOS && typeof window.AOS.init === 'function') {
                AOS.init({ once: true, duration: 600 });
            }
        });
    </script>
    <script>
        // If the products grid contains only one product, add a class to constrain its width
        document.addEventListener('DOMContentLoaded', function () {
            try {
                var grid = document.querySelector('.productos-grid');
                if (grid) {
                    var cards = grid.querySelectorAll('.producto-card');
                    if (cards.length === 1) {
                        grid.classList.add('single-item');
                    } else {
                        grid.classList.remove('single-item');
                    }
                }
            } catch (e) {
                // fail silently
                console.error('productos-grid check error', e);
            }
        });
    </script>
    <script src="<?php echo e(asset('js/base-config.js')); ?>"></script>
    <script src="<?php echo e(asset('js/carrito.js')); ?>"></script>
    <script src="<?php echo e(asset('js/cart.js')); ?>"></script>
    <script src="<?php echo e(asset('js/cliente-carrito.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/public/menu.blade.php ENDPATH**/ ?>