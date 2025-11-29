<header class="nav-wrap">
  <nav class="nav">
    <a class="brand" href="{{ url('/') }}">
      <span style="font-size: 1.5rem; font-weight: bold; color: #fff;">DÉSOLÉ</span>
    </a>

    @if(isset($isProfilePage) && $isProfilePage)
      <!-- Navbar para Página de Perfil (usar misma apariencia que Home/Menu público) -->
      <ul class="nav-links">
        <li><a href="{{ url('/') }}#home"><i class="fas fa-home"></i> Inicio</a></li>
        <li><a href="{{ route('cliente.menu') }}" class="{{ request()->routeIs('cliente.menu') ? 'active' : '' }}"><i class="fas fa-utensils"></i> Menú Completo</a></li>
        <li><a href="{{ url('/') }}#promociones"><i class="fas fa-gift"></i> Promociones</a></li>
        <li><a href="{{ url('/') }}#reseñas"><i class="fas fa-comment"></i> Reseñas</a></li>
        <li><a href="{{ url('/') }}#contacto"><i class="fas fa-phone"></i> Contacto</a></li>
        <li><a href="{{ route('cliente.dashboard') }}" class="{{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}"><i class="fas fa-user"></i> Mi Perfil</a></li>
        <li><a href="{{ route('cliente.pedidos.index') }}" class="{{ request()->routeIs('cliente.pedidos.*') ? 'active' : '' }}"><i class="fas fa-history"></i> Mis Pedidos</a></li>
      </ul>

      <div class="nav-actions">
        <!-- Igual que en la versión pública: carrito + texto de salir -->
        <button id="cartBtn" class="cart-btn" aria-label="Abrir carrito">
          <i class="fas fa-shopping-cart"></i>
          <span id="cart-count" class="cart-count">0</span>
        </button>
        <form method="POST" action="{{ route('logout.cliente') }}" style="display: inline;">
          @csrf
          <button type="submit" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Salir
          </button>
        </form>
        <button id="mobile-menu-btn" class="hamburger" aria-label="Abrir menú móvil">
          <i class="fas fa-bars"></i>
        </button>
      </div>

    @else
      <!-- Navbar Original Público -->
      <ul class="nav-links">
        <li><a href="{{ url('/') }}#home"><i class="fas fa-home"></i> Inicio</a></li>
        
        @auth('cliente')
          <li><a href="{{ route('cliente.menu') }}"><i class="fas fa-utensils"></i> Menú Completo</a></li>
        @else
          <li><a href="{{ route('menu') }}"><i class="fas fa-utensils"></i> Menú</a></li>
        @endauth
        
  <li><a href="{{ url('/') }}#promociones"><i class="fas fa-gift"></i> Promociones</a></li>
        <li><a href="{{ url('/') }}#reseñas"><i class="fas fa-comment"></i> Reseñas</a></li>
        <li><a href="{{ url('/') }}#contacto"><i class="fas fa-phone"></i> Contacto</a></li>
        
        @auth('cliente')
          <li><a href="{{ route('cliente.dashboard') }}"><i class="fas fa-user"></i> Mi Perfil</a></li>
          <li><a href="{{ route('cliente.pedidos.index') }}"><i class="fas fa-history"></i> Mis Pedidos</a></li>
        @endauth
      </ul>

      <div class="nav-actions">
        @guest('cliente')
          <a href="{{ route('login.cliente') }}" class="login-btn">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
          </a>
          <a href="{{ route('register') }}" class="register-btn">
            <i class="fas fa-user-plus"></i> Registrarse
          </a>
        @else
          <button id="cartBtn" class="cart-btn" aria-label="Abrir carrito">
            <i class="fas fa-shopping-cart"></i>
            <span id="cart-count" class="cart-count">0</span>
          </button>
          <form method="POST" action="{{ route('logout.cliente') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">
              <i class="fas fa-sign-out-alt"></i> Salir
            </button>
          </form>
        @endguest
        
        <button id="mobile-menu-btn" class="hamburger" aria-label="Abrir menú móvil">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    @endif
  </nav>
</header>