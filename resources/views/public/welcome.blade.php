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
  <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
</head>
<body data-theme="default">

  <!-- ELEMENTOS GLOBALES -->
  @include('public.secciones._navbar')
  @include('public.secciones._hero')
  @include('public.secciones._menu')

  <main>
    @guest('cliente')
      @include('public.secciones._promociones-teaser')
    @else
      @include('public.secciones._cliente-hero')
      
      <!-- MENÚ NORMAL PARA TODOS -->
      <section id="menu" class="menu-section">
        <div class="container">
          <h2 class="section-title" data-aos="fade-up">Menú</h2>
          @php $productos = App\Models\Producto::all(); @endphp
          <div class="productos-grid">
            @foreach($productos as $producto)
            <div class="producto-card" data-aos="fade-up">
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

      <!-- PROMOCIONES -->
      <section id="promociones" class="promociones-section">
        <div class="container">
          <h2 class="section-title" data-aos="fade-up">Tus Promociones</h2>
          <div class="promociones-grid">
            @foreach ($promociones as $promo)
            <div class="promo-card" data-aos="fade-up">
              <h3 class="promo-name">{{ $promo->nombre }}</h3>
              <p class="promo-desc">{{ $promo->descripcion }}</p>
              <div class="promo-details">
                <strong>Descuento:</strong>
                <span>{{ $promo->tipo_descuento === 'porcentaje' ? $promo->valor_descuento . '%' : '$' . number_format($promo->valor_descuento, 2) }}</span>
                <br>
                <strong>Válido hasta:</strong> {{ \Carbon\Carbon::parse($promo->fecha_fin)->format('d/m/Y') }}
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </section>
    @endguest

    @include('public.secciones._reseñas')
    @include('public.secciones._contacto')
  </main>

  <!-- Scripts -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="{{ asset('js/cart.js') }}"></script>
  <script src="{{ asset('js/cliente-carrito.js') }}"></script>

  <!-- Scroll suave solo para menu y promociones -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const navbarHeight = 80; // Ajusta según altura navbar
      const scrollLinks = document.querySelectorAll('a.scroll-link');

      scrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          const targetID = this.getAttribute('href').substring(1);

          if(targetID === 'menu' || targetID === 'promociones') {
            e.preventDefault();
            const section = document.getElementById(targetID);
            if(section) {
              window.scrollTo({
                top: section.offsetTop - navbarHeight,
                behavior: 'smooth'
              });
            }

            // Cerrar menú móvil
            const mobileMenu = document.querySelector('.nav-links.active');
            if(mobileMenu) mobileMenu.classList.remove('active');
          }
        });
      });

      // Toggle menú móvil
      const mobileBtn = document.getElementById('mobile-menu-btn');
      const navLinks = document.querySelector('.nav-links');
      if(mobileBtn && navLinks) {
        mobileBtn.addEventListener('click', () => {
          navLinks.classList.toggle('active');
        });
      }
    });
  </script>

</body>
</html>
