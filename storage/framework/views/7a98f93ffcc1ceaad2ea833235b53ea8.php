<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Désolé')); ?> - <?php echo $__env->yieldContent('title', 'Bienvenido'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo e(asset('assets/favicon.ico')); ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Styles -->
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-900 text-white font-sans">
    <!-- Reusar navbar público (partial que usa exactamente el mismo header que Home) -->
    <?php echo $__env->make('public.secciones._navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Mobile Menu (hidden by default) - kept for small screens, uses same links as nav -->
    <div id="mobile-menu" class="md:hidden hidden bg-gray-800 border-b border-gray-700">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col space-y-4">
                <a href="<?php echo e(route('home')); ?>" class="text-gray-300 hover:text-white transition">Inicio</a>
                <a href="<?php echo e(route('menu')); ?>" class="text-gray-300 hover:text-white transition">Menú Completo</a>
                <a href="<?php echo e(url('/')); ?>#promociones" class="text-gray-300 hover:text-white transition">Promociones</a>
                <a href="<?php echo e(url('/')); ?>#reseñas" class="text-gray-300 hover:text-white transition">Reseñas</a>
                <a href="<?php echo e(url('/')); ?>#contacto" class="text-gray-300 hover:text-white transition">Contacto</a>
                <?php if(auth()->guard('cliente')->check()): ?>
                  <a href="<?php echo e(route('cliente.dashboard')); ?>" class="text-gray-300 hover:text-white transition">Mi Perfil</a>
                  <a href="<?php echo e(route('cliente.pedidos.index')); ?>" class="text-gray-300 hover:text-white transition">Mis Pedidos</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php if (! ($__env->hasSection('hide-footer'))): ?>
        <!-- Footer -->
        <footer class="bg-gray-800/50 border-t border-gray-700 mt-12">
            <div class="container mx-auto px-4 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-green-400 mb-4">Contacto</h3>
                        <div class="space-y-2 text-gray-300">
                            <p><i class="fas fa-map-marker-alt w-6"></i> 123 Calle Principal</p>
                            <p><i class="fas fa-phone w-6"></i> (123) 456-7890</p>
                            <p><i class="fas fa-envelope w-6"></i> info@desole.com</p>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold text-green-400 mb-4">Enlaces</h3>
                        <div class="space-y-2">
                            <p><a href="#" class="text-gray-300 hover:text-white transition">Términos y Condiciones</a></p>
                            <p><a href="#" class="text-gray-300 hover:text-white transition">Política de Privacidad</a></p>
                            <p><a href="#" class="text-gray-300 hover:text-white transition">Preguntas Frecuentes</a></p>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div>
                        <h3 class="text-lg font-semibold text-green-400 mb-4">Síguenos</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-300 hover:text-white transition">
                                <i class="fab fa-facebook text-2xl"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white transition">
                                <i class="fab fa-instagram text-2xl"></i>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-white transition">
                                <i class="fab fa-twitter text-2xl"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="mt-8 pt-4 border-t border-gray-700 text-center text-gray-400">
                    <p>&copy; <?php echo e(date('Y')); ?> Désolé. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    <?php endif; ?>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle (use specific IDs to avoid affecting other buttons)
        document.addEventListener('DOMContentLoaded', function() {
            var btn = document.getElementById('mobile-menu-btn');
            var menu = document.getElementById('mobile-menu');
            if (btn && menu) {
                btn.addEventListener('click', function() { menu.classList.toggle('hidden'); });
            }
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\Desole\resources\views/layouts/public.blade.php ENDPATH**/ ?>