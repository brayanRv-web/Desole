<header class="nav-wrap">
  <nav class="nav">
    <a class="brand" href="{{ url('/') }}">
      <img src="{{ asset('uploads/banners/logo.png') }}" alt="DESOLE" class="logo" />
    </a>

    @if(isset($isProfilePage) && $isProfilePage)
      <!-- Navbar para Página de Perfil -->
      <div class="nav-links">
        <li><a href="{{ url('/') }}#home"><i class="fas fa-home"></i> Inicio</a></li>
        <a href="{{ route('cliente.dashboard') }}" class="{{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">
          <i class="fas fa-user me-1"></i> Mi perfil
        </a>
      </div>

      <div class="nav-buttons">
        <form method="POST" action="{{ route('logout.cliente') }}">
          @csrf
          <button type="submit" class="cart-btn logout-nav-btn" title="Cerrar Sesión">
            <i class="fas fa-sign-out-alt"></i>
          </button>
        </form>
      </div>

    @else
      <!-- Navbar Pública / Usuarios logueados -->
      <ul class="nav-links">
        <li><a href="#home" class="scroll-link"><i class="fas fa-home"></i> Inicio</a></li>

        {{-- 🔹 Menú y promociones con scroll suave --}}
        <li><a href="#menu" class="scroll-link"><i class="fas fa-utensils"></i> Menú</a></li>
        <li><a href="#promociones" class="scroll-link"><i class="fas fa-gift"></i> Promociones</a></li>

        <!--<li><a href="#especiales"><i class="fas fa-star"></i> Especiales</a></li>-->
        <li><a href="#reseñas" class="scroll-link"><i class="fas fa-comment"></i> Reseñas</a></li>
        <li><a href="#contacto" class="scroll-link"><i class="fas fa-phone"></i> Contacto</a></li>

        @auth('cliente')
          <li><a href="{{ route('cliente.dashboard') }}"><i class="fas fa-user"></i> Mi Cuenta</a></li>
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
          <button id="cart-toggle" class="cart-btn" aria-label="Abrir carrito">
            <i class="fas fa-shopping-cart"></i> <span id="cart-count">0</span>
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
