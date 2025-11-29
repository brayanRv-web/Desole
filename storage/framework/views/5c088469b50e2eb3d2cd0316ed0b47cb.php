<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DÉSOLÉ - Cafetería nocturna</title>
  <meta name="description" content="DESOLE - Cafetería nocturna. Alitas, pizzadogs, frappés y promociones nocturnas. Pedidos por WhatsApp o en línea." />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <!-- Estilos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <link rel="stylesheet" href="<?php echo e(asset('css/desole.css')); ?>"> 
  <link rel="stylesheet" href="<?php echo e(asset('css/hero.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('css/carrito.css')); ?>">
  <link rel="icon" href="<?php echo e(asset('assets/favicon.ico')); ?>">
</head>
<body data-theme="default">
  <!-- ELEMENTOS ESTRUCTURALES GLOBALES -->
   <?php echo $__env->make('public.secciones._navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
   <?php echo $__env->make('public.secciones._hero', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <main>

    <!-- SECCIÓN PRODUCTOS DESTACADOS (VISIBLE PARA TODOS) -->
    <section id="destacados" class="destacados-section">
      <div class="container">
        <h2 class="section-title" data-aos="fade-up">Nuestros Favoritos</h2>
        <p class="section-subtitle" data-aos="fade-up" data-aos-delay="200">
          Una probadita de lo que te espera en nuestro menú
        </p>
        
        <div class="productos-grid">
          <?php $__currentLoopData = $productosDestacados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="producto-card" data-aos="fade-up" data-aos-delay="<?php echo e($loop->index * 100); ?>"
               data-producto-id="<?php echo e($producto->id); ?>"
               data-nombre="<?php echo e(htmlspecialchars($producto->nombre, ENT_QUOTES)); ?>"
               data-precio="<?php echo e(number_format($producto->precio, 2, '.', '')); ?>"
               data-stock="<?php echo e($producto->stock ?? 0); ?>"
               data-imagen="<?php echo e($producto->imagen ? asset($producto->imagen) : asset('assets/placeholder.svg')); ?>">
            <div class="producto-img">
              <img src="<?php echo e($producto->imagen ? asset($producto->imagen) : asset('assets/placeholder.svg')); ?>" alt="<?php echo e($producto->nombre); ?>">
            </div>
            <div class="producto-info">
              <h3><?php echo e($producto->nombre); ?></h3>
              <p class="producto-desc"><?php echo e(Str::limit($producto->descripcion, 80)); ?></p>
              <div class="producto-precio">$<?php echo e(number_format($producto->precio, 2)); ?></div>
              <div class="producto-actions">
                <button class="btn-agregar-carrito" type="button">
                  <i class="fas fa-plus"></i>
                  Agregar
                </button>
              </div>
            </div>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <?php if(auth()->guard('cliente')->guest()): ?>
        <div class="cta-registro" data-aos="fade-up">
          <div class="cta-content">
            <h3>¿Quieres ver el menú completo?</h3>
            <p>Regístrate y disfruta de:</p>
            <ul>
              <li><i class="fas fa-check"></i> Menú completo con todas las categorías</li>
              <li><i class="fas fa-check"></i> Carrito de compras integrado</li>
              <li><i class="fas fa-check"></i> Promociones exclusivas para miembros</li>
              <li><i class="fas fa-check"></i> Seguimiento de pedidos en tiempo real</li>
              <li><i class="fas fa-check"></i> Programa de fidelidad con puntos</li>
            </ul>
            <a href="<?php echo e(route('register')); ?>" class="btn btn-primary">
              <i class="fas fa-user-plus"></i> Registrarme y ver menú completo
            </a>
            <p class="cta-small">¿Ya tienes cuenta? <a href="<?php echo e(route('login.cliente')); ?>">Inicia sesión aquí</a></p>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- PROMOCIONES TEASER -->
    <section id="promociones" class="promociones-section">
      <h2 class="promociones-title">Promociones Activas</h2>
      
      <?php if($promociones->isEmpty()): ?>
        <div class="no-promociones">
          <i class="fas fa-tags"></i>
          <p>Por el momento no hay promociones activas</p>
          <p>Vuelve pronto para descubrir nuestras ofertas especiales</p>
        </div>
      <?php else: ?>
        <div class="promociones-grid">
          <?php $__currentLoopData = $promociones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="promo-card">
              <h3 class="promo-name"><?php echo e($promo->nombre); ?></h3>
              <p class="promo-desc"><?php echo e($promo->descripcion); ?></p>

              <p class="promo-detail">
                <strong>Tipo de Descuento:</strong>
                <span>
                  <?php echo e($promo->tipo_descuento === 'porcentaje' ? $promo->valor_descuento . '%' : '$' . number_format($promo->valor_descuento, 2)); ?>

                </span>
              </p>

              <p class="promo-dates">
                <strong>Válido del:</strong>
                <?php echo e(\Carbon\Carbon::parse($promo->fecha_inicio)->format('d/m/Y')); ?>

                <strong>al</strong>
                <?php echo e(\Carbon\Carbon::parse($promo->fecha_fin)->format('d/m/Y')); ?>

              </p>

              <div class="promo-cta">
                <p class="promo-registro">⚠️ Esta promoción requiere registro</p>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-outline">
                  <i class="fas fa-user-plus"></i> Registrarme para aprovechar
                </a>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </section>

  <!-- ==================== CONTENIDO PARA CLIENTES REGISTRADOS ==================== -->
  <?php if(auth()->guard('cliente')->check()): ?>

    <!-- HERO CLIENTE -->
    <section class="cliente-hero">
      <div class="container">
        <div class="cliente-welcome" data-aos="fade-up">
          <h2>¡Hola, <?php echo e(Auth::guard('cliente')->user()->nombre); ?>! 👋</h2>
          <p>Bienvenido de nuevo a tu cafetería favorita</p>
          <div class="cliente-stats">
            <div class="stat-card">
              <i class="fas fa-gift"></i>
              <span class="stat-number"><?php echo e($promociones->count()); ?></span>
              <span class="stat-label">Promociones activas</span>
            </div>
            <div class="stat-card">
              <i class="fas fa-star"></i>
              <span class="stat-number"><?php echo e(Auth::guard('cliente')->user()->puntos_fidelidad); ?></span>
              <span class="stat-label">Puntos fidelidad</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- MENÚ COMPLETO eliminado del Home: los productos del menú se muestran en la página /menu -->

    <!-- PROMOCIONES COMPLETAS PARA CLIENTES -->
    <section id="promociones-cliente" class="promociones-cliente-section">
      <div class="container">
        <h2 class="section-title" data-aos="fade-up">Tus Promociones Exclusivas</h2>
        
        <div class="promociones-grid-cliente">
          <?php $__currentLoopData = $promociones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="promo-card-cliente" data-aos="fade-up">
            <div class="promo-badge">EXCLUSIVO</div>
            <h3 class="promo-name"><?php echo e($promo->nombre); ?></h3>
            <p class="promo-desc"><?php echo e($promo->descripcion); ?></p>

            <div class="promo-details">
              <div class="promo-discount">
                <strong>Descuento:</strong>
                <span class="discount-value">
                  <?php echo e($promo->tipo_descuento === 'porcentaje' ? $promo->valor_descuento . '%' : '$' . number_format($promo->valor_descuento, 2)); ?>

                </span>
              </div>
              
              <div class="promo-dates">
                <strong>Válido hasta:</strong>
                <?php echo e(\Carbon\Carbon::parse($promo->fecha_fin)->format('d/m/Y')); ?>

              </div>
            </div>

            <button class="btn-aplicar-promo">
              <i class="fas fa-tag"></i> Disponible para tu pedido
            </button>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </section>

  <?php endif; ?>

    <!-- SECCIONES QUE SE MUESTRAN A TODOS -->
    <?php echo $__env->make('public.secciones._reseñas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('public.secciones._contacto', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </main>

  <!-- Scripts -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    // Initialize AOS animations so elements with data-aos become visible
    document.addEventListener('DOMContentLoaded', function() {
      if (window.AOS && typeof window.AOS.init === 'function') {
        AOS.init({ once: true, duration: 600 });
      }
    });
  </script>
  <script src="<?php echo e(asset('js/base-config.js')); ?>"></script>
  <script src="<?php echo e(asset('js/carrito.js')); ?>"></script>
  <script src="<?php echo e(asset('js/cart.js')); ?>"></script>
  <script src="<?php echo e(asset('js/cliente-carrito.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/public/welcome.blade.php ENDPATH**/ ?>