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

        <!-- SECCIÓN DE PROMOCIONES ACTIVAS -->
        <?php if($promociones->isNotEmpty()): ?>
        <section class="container mx-auto px-4 py-6 mb-8">
            <div class="bg-gradient-to-r from-green-900/40 to-gray-900/40 rounded-2xl p-6 border border-green-500/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-green-500/20 rounded-full blur-xl"></div>
                <h3 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-fire text-orange-500"></i> Promociones Activas
                </h3>
                <div class="<?php echo e($promociones->count() === 1 ? 'flex justify-center' : 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3'); ?> gap-4">
                    <?php $__currentLoopData = $promociones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $totalOriginal = 0;
                            $totalDescuento = 0;
                            foreach($promo->productos as $prod) {
                                $totalOriginal += $prod->precio;
                                $totalDescuento += $prod->precio_descuento;
                            }
                            $ahorro = $totalOriginal - $totalDescuento;
                        ?>
                        <div class="bg-zinc-900/80 backdrop-blur-md border border-zinc-800 rounded-xl p-4 flex flex-col shadow-lg hover:border-green-500/30 transition-all duration-300 group <?php echo e($promociones->count() === 1 ? 'w-full max-w-md' : ''); ?>">
                            <!-- Header Compacto -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-lg font-bold text-white leading-tight mb-1"><?php echo e($promo->nombre); ?></h3>
                                    <div class="flex items-center gap-2 text-xs text-gray-400">
                                        <span class="bg-green-500/10 text-green-400 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">
                                            <?php echo e($promo->tipo_descuento === 'porcentaje' ? '-' . $promo->valor_descuento . '%' : 'Ahorra $' . number_format($promo->valor_descuento, 0)); ?>

                                        </span>
                                        <span><i class="far fa-clock text-[10px]"></i> <?php echo e($promo->fecha_fin->format('d M')); ?></span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-green-400 leading-none">$<?php echo e(number_format($totalDescuento, 2)); ?></div>
                                    <div class="text-xs text-gray-500 line-through">$<?php echo e(number_format($totalOriginal, 2)); ?></div>
                                </div>
                            </div>

                            <p class="text-gray-400 text-xs mb-3 line-clamp-2"><?php echo e($promo->descripcion); ?></p>

                            <!-- Productos Compactos -->
                            <?php if($promo->productos->isNotEmpty()): ?>
                                <div class="flex-1 bg-zinc-950/50 rounded-lg p-2 mb-3 border border-zinc-800/50">
                                    <p class="text-[10px] text-gray-500 mb-1.5 font-medium uppercase tracking-wide">Incluye:</p>
                                    <div class="space-y-1.5 max-h-24 overflow-y-auto pr-1 custom-scrollbar">
                                        <?php $__currentLoopData = $promo->productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center gap-2">
                                                <img src="<?php echo e($prod->imagen ? asset($prod->imagen) : asset('assets/placeholder.svg')); ?>" 
                                                     alt="<?php echo e($prod->nombre); ?>" 
                                                     class="w-6 h-6 rounded object-cover opacity-80">
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-xs text-gray-300 truncate"><?php echo e($prod->nombre); ?></div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Footer / Botón -->
                            <div class="mt-auto pt-2 border-t border-zinc-800">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs text-green-400 font-medium">
                                        <i class="fas fa-piggy-bank mr-1"></i> Ahorras: $<?php echo e(number_format($ahorro, 2)); ?>

                                    </span>
                                </div>
                                <button class="btn-agregar-promocion w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-500 hover:to-green-600 text-white text-xs font-bold py-2 px-3 rounded-lg transition-all shadow-lg shadow-green-900/20 flex items-center justify-center gap-2 transform active:scale-95"
                                        data-promocion-id="<?php echo e($promo->id); ?>">
                                    <i class="fas fa-cart-plus"></i> Agregar Pack al Carrito
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <main class="container mx-auto px-4 py-8">

            <!-- Productos (solo listado simple) -->
        <?php if($productos->isNotEmpty()): ?>
            <div class="productos-grid">
                <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $precioFinal = $producto->precio;
                        $tieneDescuento = false;
                        $promocionActiva = $producto->promocionActiva;
                        
                        if ($promocionActiva) {
                            $precioFinal = $producto->precio_descuento;
                            $tieneDescuento = true;
                        }
                    ?>
                    <div class="producto-card group relative" data-producto-id="<?php echo e($producto->id); ?>"
                             data-nombre="<?php echo e(htmlspecialchars($producto->nombre, ENT_QUOTES)); ?>"
                             data-precio="<?php echo e(number_format($precioFinal, 2, '.', '')); ?>"
                             data-stock="<?php echo e($producto->stock ?? 0); ?>"
                             data-imagen="<?php echo e($producto->imagen ? asset($producto->imagen) : asset('assets/placeholder.svg')); ?>">
                        
                        <?php if($tieneDescuento): ?>
                            <div class="absolute top-3 right-3 z-10 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg animate-pulse">
                                <?php if($promocionActiva->tipo_descuento == 'porcentaje'): ?>
                                    -<?php echo e($promocionActiva->valor_descuento); ?>%
                                <?php else: ?>
                                    -$<?php echo e(number_format($promocionActiva->valor_descuento, 0)); ?>

                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="producto-img">
                            <img src="<?php echo e($producto->imagen ? asset($producto->imagen) : asset('assets/placeholder.svg')); ?>" alt="<?php echo e($producto->nombre); ?>">
                        </div>
                        <div class="producto-info">
                            <h3><?php echo e($producto->nombre); ?></h3>
                            <p class="producto-desc"><?php echo e(Str::limit($producto->descripcion, 120)); ?></p>
                            
                            <div class="producto-precio flex items-center gap-2">
                                <?php if($tieneDescuento): ?>
                                    <span class="text-gray-500 line-through text-sm">$<?php echo e(number_format($producto->precio, 2)); ?></span>
                                    <span class="text-green-400 font-bold text-xl">$<?php echo e(number_format($precioFinal, 2)); ?></span>
                                <?php else: ?>
                                    <span>$<?php echo e(number_format($producto->precio, 2)); ?></span>
                                <?php endif; ?>
                            </div>

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
    <script src="<?php echo e(asset('js/cliente-carrito.js')); ?>?v=<?php echo e(time()); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Desole\resources\views/public/menu.blade.php ENDPATH**/ ?>