<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

  <link rel="stylesheet" href="{{ asset('css/desole.css') }}"> 
  <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
  <link rel="stylesheet" href="{{ asset('css/carrito.css') }}">
  <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
</head>
<body data-theme="default">
  <!-- ELEMENTOS ESTRUCTURALES GLOBALES -->
   @include('public.secciones._navbar')
   @include('public.secciones._hero')

  <main>

    <!-- SECCIÓN PRODUCTOS DESTACADOS (VISIBLE PARA TODOS) -->
    <section id="destacados" class="destacados-section">
      <div class="container">
        <h2 class="section-title" data-aos="fade-up">Nuestros Favoritos</h2>
        <p class="section-subtitle" data-aos="fade-up" data-aos-delay="200">
          Una probadita de lo que te espera en nuestro menú
        </p>
        
        <div class="productos-grid">
          @foreach($productosDestacados as $producto)
          <div class="producto-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}"
               data-producto-id="{{ $producto->id }}"
               data-nombre="{{ htmlspecialchars($producto->nombre, ENT_QUOTES) }}"
               data-precio="{{ number_format($producto->precio, 2, '.', '') }}"
               data-stock="{{ $producto->stock ?? 0 }}"
               data-imagen="{{ $producto->imagen ? asset($producto->imagen) : asset('assets/placeholder.svg') }}">
            <div class="producto-img">
              <img src="{{ $producto->imagen ? asset($producto->imagen) : asset('assets/placeholder.svg') }}" alt="{{ $producto->nombre }}">
            </div>
            <div class="producto-info">
              <h3>{{ $producto->nombre }}</h3>
              <p class="producto-desc">{{ Str::limit($producto->descripcion, 80) }}</p>
              <div class="producto-precio">${{ number_format($producto->precio, 2) }}</div>
              <div class="producto-actions">
                <button class="btn-agregar-carrito" type="button">
                  <i class="fas fa-plus"></i>
                  Agregar
                </button>
              </div>
            </div>
          </div>
          @endforeach
        </div>
        
        @guest('cliente')
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
            <a href="{{ route('register') }}" class="btn btn-primary">
              <i class="fas fa-user-plus"></i> Registrarme y ver menú completo
            </a>
            <p class="cta-small">¿Ya tienes cuenta? <a href="{{ route('login.cliente') }}">Inicia sesión aquí</a></p>
          </div>
        </div>
        @endguest
      </div>
    </section>

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
  @auth('cliente')

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

    <!-- MENÚ COMPLETO eliminado del Home: los productos del menú se muestran en la página /menu -->

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

  @endauth

    <!-- SECCIONES QUE SE MUESTRAN A TODOS -->
    @include('public.secciones._reseñas')
    @include('public.secciones._contacto')
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
  <script src="{{ asset('js/base-config.js') }}"></script>
  <script src="{{ asset('js/carrito.js') }}"></script>
  <script src="{{ asset('js/cart.js') }}"></script>
  <script src="{{ asset('js/cliente-carrito.js') }}"></script>
</body>
</html>
