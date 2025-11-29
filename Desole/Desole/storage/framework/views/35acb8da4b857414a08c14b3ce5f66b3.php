<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desole - <?php echo $__env->yieldContent('title', 'Promociones'); ?></title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Estilos -->
    <link rel="stylesheet" href="<?php echo e(asset('css/desole.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/hero.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/carrito.css')); ?>">

    <!-- Tailwind CSS (Mantener por si acaso se usa en contenido específico) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="icon" href="<?php echo e(asset('assets/favicon.ico')); ?>">
</head>
<body class="bg-[#0d0d0d] text-gray-100">
    
    <!-- Navbar Global -->
    <?php echo $__env->make('public.secciones._navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Contenido dinámico -->
    <main class="container mx-auto mt-24 px-4 min-h-screen">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 mt-12 py-6 text-center text-sm">
        © <?php echo e(date('Y')); ?> Désole. Todos los derechos reservados.
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.AOS && typeof window.AOS.init === 'function') {
                AOS.init({ once: true, duration: 600 });
            }
        });
    </script>
    <script src="<?php echo e(asset('js/base-config.js')); ?>"></script>
    <script src="<?php echo e(asset('js/carrito.js')); ?>"></script>
    <script src="<?php echo e(asset('js/cart.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Desole\Desole\resources\views/public/layout.blade.php ENDPATH**/ ?>