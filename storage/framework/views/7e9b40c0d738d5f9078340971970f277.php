<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'DÉSOLÉ - Cafetería Nocturna'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/desole.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/perfil.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/carrito.css')); ?>">
    <link rel="icon" href="<?php echo e(asset('assets/favicon.ico')); ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        zinc: {
                            700: '#3f3f46',
                            800: '#27272a',
                            900: '#18181b',
                        }
                    }
                }
            }
        }
    </script>
  <style>
  /* Notification container positioning preserved */
  #notificaciones-container {
    position: fixed;
    bottom: 1rem;
    right: 1rem;
    /* Ensure notifications are above most page elements */
    z-index: 10001;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Navbar Específico para Cliente -->
    <?php echo $__env->make('public.secciones._navbar', ['isProfilePage' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="cliente-main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(asset('js/base-config.js')); ?>"></script>
    <script src="<?php echo e(asset('js/carrito.js')); ?>"></script>
    <script src="<?php echo e(asset('js/cart.js')); ?>"></script>
    <script src="<?php echo e(asset('js/cliente-carrito.js')); ?>"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
<script>
(function(){
  // Monkey-patch setItem para detectar quién escribe 'carrito'
  const originalSetItem = Storage.prototype.setItem;
  Storage.prototype.setItem = function(key, value) {
    try {
      if (key === 'carrito') {
        // Intenta extraer un stack trace del Error para ver el origen
        const err = new Error();
        const stack = err.stack ? err.stack.split('\n').slice(1,6).join('\n') : 'no stack';
        console.group('%c[DETECTOR carrito] localStorage.setItem llamado', 'color:#22c55e;font-weight:700');
        console.log('key:', key);
        console.log('value:', value);
        console.log('stack (primeros frames):\n' + stack);
        console.trace();
        console.groupEnd();
      }
    } catch (e) {
      console.error('Error en detector carrito', e);
    }
    return originalSetItem.apply(this, arguments);
  };

  // También parchear removeItem por si lo usan
  const originalRemoveItem = Storage.prototype.removeItem;
  Storage.prototype.removeItem = function(key) {
    if (key === 'carrito') {
      console.warn('[DETECTOR carrito] localStorage.removeItem("carrito") llamado', new Error().stack);
    }
    return originalRemoveItem.apply(this, arguments);
  };

  // Y log inicial para el contenido actual
  try {
    const existing = localStorage.getItem('carrito');
    if (existing) {
      console.info('[DETECTOR carrito] Valor actual de carrito en localStorage:', existing);
    } else {
      console.info('[DETECTOR carrito] No hay carrito en localStorage actualmente.');
    }
  } catch(e) {
    console.error('[DETECTOR carrito] No se pudo leer localStorage', e);
  }
})();
</script>

</html><?php /**PATH C:\xampp\htdocs\Desole\resources\views/cliente/layout/cliente.blade.php ENDPATH**/ ?>