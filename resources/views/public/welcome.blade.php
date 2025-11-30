<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DÉSOLÉ - Cafetería nocturna</title>
  <meta name="description" content="DESOLE - Cafetería nocturna. Alitas, pizzadogs, frappés y promociones nocturnas. Pedidos por WhatsApp o en línea." />
  <script>
      window.APP_URL = "{{ url('/') }}";
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
        <div class="{{ $promociones->count() === 1 ? 'flex justify-center' : 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3' }} gap-4">
          @foreach ($promociones as $promo)
            @php
                $totalOriginal = 0;
                $totalDescuento = 0;
                // Usar productosActivos si existe, sino fallback a productos (aunque en welcome siempre debería ser activos)
                $productos = $promo->productosActivos ?? $promo->productos;
                
                foreach($productos as $prod) {
                    $totalOriginal += $prod->precio;
                    $totalDescuento += $prod->precio_descuento;
                }
                $ahorro = $totalOriginal - $totalDescuento;
            @endphp
            <div class="bg-zinc-900/80 backdrop-blur-md border border-zinc-800 rounded-xl p-4 flex flex-col shadow-lg hover:border-green-500/30 transition-all duration-300 group {{ $promociones->count() === 1 ? 'w-full max-w-md' : '' }}" data-aos="fade-up">
                <!-- Header Compacto -->
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="text-lg font-bold text-white leading-tight mb-1">{{ $promo->nombre }}</h3>
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            <span class="bg-green-500/10 text-green-400 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider">
                                {{ $promo->tipo_descuento === 'porcentaje' ? '-' . $promo->valor_descuento . '%' : 'Ahorra $' . number_format($promo->valor_descuento, 0) }}
                            </span>
                            <span><i class="far fa-clock text-[10px]"></i> {{ \Carbon\Carbon::parse($promo->fecha_fin)->format('d M') }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-green-400 leading-none">${{ number_format($totalDescuento, 2) }}</div>
                        <div class="text-xs text-gray-500 line-through">${{ number_format($totalOriginal, 2) }}</div>
                    </div>
                </div>

                <p class="text-gray-400 text-xs mb-3 line-clamp-2">{{ $promo->descripcion }}</p>

                <div class="mt-auto">
                    @auth('cliente')
                        <!-- Productos Compactos (Solo clientes) -->
                        @if($productos->isNotEmpty())
                            <div class="bg-zinc-950/50 rounded-lg p-2 mb-3 border border-zinc-800/50">
                                <p class="text-[10px] text-gray-500 mb-1.5 font-medium uppercase tracking-wide">Incluye:</p>
                                <div class="space-y-1.5 max-h-24 overflow-y-auto pr-1 custom-scrollbar">
                                    @foreach($productos as $prod)
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $prod->imagen ? asset($prod->imagen) : asset('assets/placeholder.svg') }}" 
                                                 alt="{{ $prod->nombre }}" 
                                                 class="w-6 h-6 rounded object-cover opacity-80">
                                            <div class="flex-1 min-w-0">
                                                <div class="text-xs text-gray-300 truncate">{{ $prod->nombre }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                             <p class="text-xs text-gray-400 italic mb-2">Ver productos en el menú</p>
                        @endif

                        <!-- Footer / Botón -->
                        <div class="pt-2 border-t border-zinc-800">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs text-green-400 font-medium">
                                    <i class="fas fa-piggy-bank mr-1"></i> Ahorras: ${{ number_format($ahorro, 2) }}
                                </span>
                            </div>
                            <button class="btn-agregar-promocion w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-500 hover:to-green-600 text-white text-xs font-bold py-2 px-3 rounded-lg transition-all shadow-lg shadow-green-900/20 flex items-center justify-center gap-2 transform active:scale-95"
                                    data-promocion-id="{{ $promo->id }}">
                                <i class="fas fa-cart-plus"></i> Agregar Pack al Carrito
                            </button>
                        </div>
                    @else
                        <!-- Guest View -->
                        <div class="pt-2 border-t border-zinc-800 mt-2">
                            <p class="text-xs text-amber-500 mb-2 flex items-center gap-1">
                                <i class="fas fa-lock"></i> Requiere registro
                            </p>
                            <a href="{{ route('register') }}" class="w-full block text-center border border-zinc-700 hover:bg-zinc-800 text-gray-300 text-xs font-medium py-2 px-3 rounded-lg transition-colors">
                                Registrarme para aprovechar
                            </a>
                        </div>
                    @endauth
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
              <i class="fas fa-shopping-bag"></i>
              <span class="stat-number">{{ Auth::guard('cliente')->user()->total_pedidos }}</span>
              <span class="stat-label">Pedidos realizados</span>
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
