<header class="nav-wrap">
  <nav class="nav">
    <a class="brand" href="<?php echo e(url('/')); ?>">
      <span style="font-size: 1.5rem; font-weight: bold; color: #fff;">DÉSOLÉ</span>
    </a>

    <?php if(isset($isProfilePage) && $isProfilePage): ?>
      <!-- Navbar para Página de Perfil (usar misma apariencia que Home/Menu público) -->
      <ul class="nav-links">
        <li><a href="<?php echo e(url('/')); ?>#home"><i class="fas fa-home"></i> Inicio</a></li>
        <li><a href="<?php echo e(route('cliente.menu')); ?>" class="<?php echo e(request()->routeIs('cliente.menu') ? 'active' : ''); ?>"><i class="fas fa-utensils"></i> Menú Completo</a></li>
        <li><a href="<?php echo e(url('/')); ?>#promociones"><i class="fas fa-gift"></i> Promociones</a></li>
        <li><a href="<?php echo e(url('/')); ?>#reseñas"><i class="fas fa-comment"></i> Reseñas</a></li>
        <li><a href="<?php echo e(url('/')); ?>#contacto"><i class="fas fa-phone"></i> Contacto</a></li>
        <li><a href="<?php echo e(route('cliente.dashboard')); ?>" class="<?php echo e(request()->routeIs('cliente.dashboard') ? 'active' : ''); ?>"><i class="fas fa-user"></i> Mi Perfil</a></li>
        <li><a href="<?php echo e(route('cliente.pedidos.index')); ?>" class="<?php echo e(request()->routeIs('cliente.pedidos.*') ? 'active' : ''); ?>"><i class="fas fa-history"></i> Mis Pedidos</a></li>
      </ul>

      <div class="nav-actions">
        <!-- Igual que en la versión pública: carrito + texto de salir -->
        <button id="cartBtn" class="cart-btn" aria-label="Abrir carrito">
          <i class="fas fa-shopping-cart"></i>
          <span id="cart-count" class="cart-count">0</span>
        </button>
        <form method="POST" action="<?php echo e(route('logout.cliente')); ?>" style="display: inline;">
          <?php echo csrf_field(); ?>
          <button type="submit" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Salir
          </button>
        </form>
        <button id="mobile-menu-btn" class="hamburger" aria-label="Abrir menú móvil">
          <i class="fas fa-bars"></i>
        </button>
      </div>

    <?php else: ?>
      <!-- Navbar Original Público -->
      <ul class="nav-links">
        <li><a href="<?php echo e(url('/')); ?>#home"><i class="fas fa-home"></i> Inicio</a></li>
        
        <?php if(auth()->guard('cliente')->check()): ?>
          <li><a href="<?php echo e(route('cliente.menu')); ?>"><i class="fas fa-utensils"></i> Menú Completo</a></li>
        <?php else: ?>
          <li><a href="<?php echo e(route('menu')); ?>"><i class="fas fa-utensils"></i> Menú</a></li>
        <?php endif; ?>
        
  <li><a href="<?php echo e(url('/')); ?>#promociones"><i class="fas fa-gift"></i> Promociones</a></li>
        <li><a href="<?php echo e(url('/')); ?>#reseñas"><i class="fas fa-comment"></i> Reseñas</a></li>
        <li><a href="<?php echo e(url('/')); ?>#contacto"><i class="fas fa-phone"></i> Contacto</a></li>
        
        <?php if(auth()->guard('cliente')->check()): ?>
          <li><a href="<?php echo e(route('cliente.dashboard')); ?>"><i class="fas fa-user"></i> Mi Perfil</a></li>
          <li><a href="<?php echo e(route('cliente.pedidos.index')); ?>"><i class="fas fa-history"></i> Mis Pedidos</a></li>
        <?php endif; ?>
      </ul>

      <div class="nav-actions">
        <?php if(auth()->guard('cliente')->guest()): ?>
          <a href="<?php echo e(route('login.cliente')); ?>" class="login-btn">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
          </a>
          <a href="<?php echo e(route('register')); ?>" class="register-btn">
            <i class="fas fa-user-plus"></i> Registrarse
          </a>
        <?php else: ?>
          <button id="cartBtn" class="cart-btn" aria-label="Abrir carrito">
            <i class="fas fa-shopping-cart"></i>
            <span id="cart-count" class="cart-count">0</span>
          </button>
          <form method="POST" action="<?php echo e(route('logout.cliente')); ?>" style="display: inline;">
            <?php echo csrf_field(); ?>
            <button type="submit" class="logout-btn">
              <i class="fas fa-sign-out-alt"></i> Salir
            </button>
          </form>
        <?php endif; ?>
        
        <button id="mobile-menu-btn" class="hamburger" aria-label="Abrir menú móvil">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    <?php endif; ?>
  </nav>
</header><?php /**PATH C:\xampp\htdocs\Desole\resources\views/public/secciones/_navbar.blade.php ENDPATH**/ ?>