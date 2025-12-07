
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DÉSOLÉ - Cafetería nocturna - <?php echo $__env->yieldContent('title', 'Reseñas'); ?></title>

  <script>
      window.APP_URL = "<?php echo e(url('/')); ?>";
  </script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
      tailwind.config = {
          darkMode: 'class',
          theme: {
              extend: {
                  fontFamily: {
                      sans: ['Poppins', 'sans-serif'],
                  },
                  colors: {
                      zinc: { 700: '#3f3f46', 800: '#27272a', 900: '#18181b' }
                  }
              }
          }
      }
  </script>

  <!-- Estilos -->
  <link rel="stylesheet" href="<?php echo e(asset('css/desole.css')); ?>"> 
  <link rel="icon" href="<?php echo e(asset('assets/favicon.ico')); ?>">
</head>
<body data-theme="default">

  
  <?php echo $__env->make('public.secciones._navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


  
<main class="min-h-screen py-8">
  <div class="container mx-auto px-4">

      <h1 class="text-3xl font-bold text-green-500 mb-6 text-center">Todas las Reseñas</h1>

      <?php if($resenas->isEmpty()): ?>
          <div class="text-center p-6 bg-gray-700/50 rounded-lg border border-gray-600">
              <p class="text-gray-300">No hay reseñas disponibles por ahora.</p>
          </div>
      <?php else: ?>
          <div class="space-y-4">
              <?php $__currentLoopData = $resenas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resena): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-zinc-800/50 p-5 rounded-lg border border-zinc-700 shadow-md hover:shadow-lg transition max-w-5xl mx-auto">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-semibold text-green-400 text-lg"><?php echo e($resena->nombre); ?></span>
                            <span class="text-gray-400 text-sm"><?php echo e($resena->created_at->format('d M Y')); ?></span>
                        </div>

                        <div class="text-yellow-400 mb-2">
                            <?php for($i = 0; $i < $resena->calificacion; $i++): ?>
                                <i class="fas fa-star"></i>
                            <?php endfor; ?>
                        </div>

                        <p class="text-gray-200"><?php echo e($resena->comentario); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

          </div>

      <?php endif; ?>

  </div>
</main>


  
<footer class="bg-zinc-800/50 border-t border-zinc-700 mt-12">
      <div class="container mx-auto px-4 py-8">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div>
                  <h3 class="text-lg font-semibold text-green-400 mb-4">Contacto</h3>
                  <div class="space-y-2 text-gray-300">
                      <p><i class="fas fa-phone w-6"></i> (961) 456-46-97</p>
                      <p><i class="fas fa-envelope w-6"></i> desole.cafeteria@gmail.com</p>
                  </div>
              </div>
              <div>
                  <h3 class="text-lg font-semibold text-green-400 mb-4">Enlaces</h3>
                  <div class="space-y-2">
                      <p><a href="#" class="text-gray-300 hover:text-white transition">Preguntas Frecuentes</a></p>
                  </div>
              </div>
              <div>
                  <h3 class="text-lg font-semibold text-green-400 mb-4">Síguenos</h3>
                  <div class="flex space-x-4">
                      <a href="#" class="text-gray-300 hover:text-white transition">
                          <i class="fab fa-facebook text-2xl"></i>
                      </a>
                      <a href="#" class="text-gray-300 hover:text-white transition">
                          <i class="fab fa-instagram text-2xl"></i>
                      </a>
                  </div>
              </div>
          </div>
          <div class="mt-8 pt-4 border-t border-zinc-700 text-center text-gray-400">
              <p>&copy; <?php echo e(date('Y')); ?> Désolé. Todos los derechos reservados.</p>
          </div>
      </div>
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
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/layouts/public.blade.php ENDPATH**/ ?>