<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DÉSOLÉ - Cafetería nocturna</title>
  <meta name="description" content="DESOLE - Cafetería nocturna. Alitas, pizzadogs, frappés y promociones nocturnas. Pedidos por WhatsApp o en línea." />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- AOS Animation -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <!-- Estilos -->
  <link rel="stylesheet" href="{{ asset('css/desole.css') }}"> 
  <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
  <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
  <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
</head>
<body data-theme="default">
  <!-- ELEMENTOS ESTRUCTURALES GLOBALES -->
   @include('public.secciones._navbar')
   @include('public.secciones._hero')

  <main>
    <!-- ==================== CONTENIDO PARA VISITANTES ==================== -->
    @guest('cliente')
    
    

    <!-- Modal de Autenticación -->
    <div id="authModal" class="modal">
      <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-header">
          <i class="fas fa-lock"></i>
          <h3>Para continuar comprando</h3>
        </div>
        <p>Necesitas tener una cuenta en DÉSOLÉ para agregar productos al carrito</p>
        <div class="auth-options">
          <a href="{{ route('login.cliente') }}" class="btn btn-primary">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
          </a>
          <a href="{{ route('register') }}" class="btn btn-ghost">
            <i class="fas fa-user-plus"></i> Crear Cuenta
          </a>
        </div>
        <p class="modal-footer">Al registrarte aceptas nuestros <a href="#">Términos y Condiciones</a></p>
      </div>
    </div>

    @include('public.secciones._menu')

    <!-- PROMOCIONES TEASER -->
    <section id="promociones" class="promociones-section">
      <h2 class="promociones-title">Promociones Activas</h2>
      
      @if ($promociones->isEmpty())
        <div class="no-promociones">
          <i class="fas fa-tags"></i>
          <p>Por el momento no hay promociones activas</p>
          <p>Vuelve pronto para descubrir nuestras ofertas especiales</p>
        </div>
      @else
        <div class="promociones-grid">
          @foreach ($promociones as $promo)
            <div class="promo-card">
              <h3 class="promo-name">{{ $promo->nombre }}</h3>
              <p class="promo-desc">{{ $promo->descripcion }}</p>

              <p class="promo-detail">
                <strong>Tipo de Descuento:</strong>
                <span>
                  {{ $promo->tipo_descuento === 'porcentaje' ? $promo->valor_descuento . '%' : '$' . number_format($promo->valor_descuento, 2) }}
                </span>
              </p>

              <p class="promo-dates">
                <strong>Válido del:</strong>
                {{ \Carbon\Carbon::parse($promo->fecha_inicio)->format('d/m/Y') }}
                <strong>al</strong>
                {{ \Carbon\Carbon::parse($promo->fecha_fin)->format('d/m/Y') }}
              </p>

              <div class="promo-cta">
                <p class="promo-registro">⚠️ Esta promoción requiere registro</p>
                <a href="{{ route('register') }}" class="btn btn-outline">
                  <i class="fas fa-user-plus"></i> Registrarme para aprovechar
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </section>

    <!-- ==================== CONTENIDO PARA CLIENTES REGISTRADOS ==================== -->
    @else

    <!-- HERO CLIENTE -->
    <section class="cliente-hero">
      <div class="container">
        <div class="cliente-welcome" data-aos="fade-up">
          <h2>¡Hola, {{ Auth::guard('cliente')->user()->nombre }}! 👋</h2>
          <p>Bienvenido de nuevo a tu cafetería favorita</p>
          <div class="cliente-stats">
            <div class="stat-card">
              <i class="fas fa-gift"></i>
              <span class="stat-number">{{ $promociones->count() }}</span>
              <span class="stat-label">Promociones activas</span>
            </div>
            <div class="stat-card">
              <i class="fas fa-star"></i>
              <span class="stat-number">{{ Auth::guard('cliente')->user()->puntos_fidelidad }}</span>
              <span class="stat-label">Puntos fidelidad</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- MENÚ COMPLETO PARA CLIENTES -->
    <section id="menu-completo" class="menu-completo-section">
      <div class="container">
        <h2 class="section-title" data-aos="fade-up">Menú Completo</h2>
        
        @php
          $productosCompletos = App\Models\Producto::all();
        @endphp
        
        <div class="productos-grid-completo">
          @foreach($productosCompletos as $producto)
          <div class="producto-card-completo" data-aos="fade-up">
            <div class="producto-img">
              <img src="{{ asset($producto->imagen ?? 'assets/placeholder-food.jpg') }}" alt="{{ $producto->nombre }}">
            </div>
            <div class="producto-info">
              <h4>{{ $producto->nombre }}</h4>
              <p class="producto-desc">{{ $producto->descripcion }}</p>
              <div class="producto-precio">${{ number_format($producto->precio, 2) }}</div>
              
              <div class="producto-actions">
                <button class="btn-agregar-carrito" data-producto-id="{{ $producto->id }}">
                  <i class="fas fa-cart-plus"></i> Agregar
                </button>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    <!-- PROMOCIONES COMPLETAS PARA CLIENTES -->
    <section id="promociones-cliente" class="promociones-cliente-section">
      <div class="container">
        <h2 class="section-title" data-aos="fade-up">Tus Promociones Exclusivas</h2>
        
        <div class="promociones-grid-cliente">
          @foreach ($promociones as $promo)
          <div class="promo-card-cliente" data-aos="fade-up">
            <div class="promo-badge">EXCLUSIVO</div>
            <h3 class="promo-name">{{ $promo->nombre }}</h3>
            <p class="promo-desc">{{ $promo->descripcion }}</p>

            <div class="promo-details">
              <div class="promo-discount">
                <strong>Descuento:</strong>
                <span class="discount-value">
                  {{ $promo->tipo_descuento === 'porcentaje' ? $promo->valor_descuento . '%' : '$' . number_format($promo->valor_descuento, 2) }}
                </span>
              </div>
              
              <div class="promo-dates">
                <strong>Válido hasta:</strong>
                {{ \Carbon\Carbon::parse($promo->fecha_fin)->format('d/m/Y') }}
              </div>
            </div>

            <button class="btn-aplicar-promo">
              <i class="fas fa-tag"></i> Disponible para tu pedido
            </button>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    @endguest

    <!-- SECCIONES QUE SE MUESTRAN A TODOS -->
    @include('public.secciones._reseñas')
    @include('public.secciones._contacto')
  </main>

  <!-- Scripts -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="{{ asset('js/hero-carousel.js') }}"></script>
  <script src="{{ asset('js/auth-modals.js') }}"></script>
  <script src="{{ asset('js/cart.js') }}"></script>
  <script src="{{ asset('js/cliente-carrito.js') }}"></script>
</body>
</html>