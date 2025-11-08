<!-- CARRUSEL DE PRODUCTOS -->
<div class="productos-carousel">
  <div class="swiper-container productos-swiper">
    <div class="swiper-wrapper">
      @php
        $productos = App\Models\Producto::whereNotNull('imagen')
          ->where('estado', 'activo')
          ->inRandomOrder()
          ->take(6)
          ->get();
      @endphp

      @foreach($productos as $producto)
      <div class="swiper-slide">
        <div class="producto-slide">
          <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="slide-image">
        </div>
      </div>
      @endforeach
    </div>

    <!-- Eliminamos las flechas -->
    <div class="swiper-pagination"></div>
  </div>
</div>

<!-- Estilos del carrusel -->
<style>
.productos-carousel {
  position: relative;
  width: 100%;
  background: var(--color-bg-alt);
  margin: 0;
  padding: 0;
}

.productos-swiper {
  width: 100%;
  height: 70vh;
  max-height: 600px;
  min-height: 300px;
}

.swiper-slide {
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
}

.producto-slide {
  position: relative;
  width: 100%;
  height: 100%;
  overflow: hidden;
  background: #000;
}

.slide-image {
  width: 100%;
  height: 100%;
  object-fit: contain;
  object-position: center;
  transition: transform 0.3s ease-in-out;
}

.swiper-pagination-bullet {
  width: 10px;
  height: 10px;
  background: white;
  opacity: 0.5;
  transition: all 0.3s ease;
}

.swiper-pagination-bullet-active {
  opacity: 1;
  transform: scale(1.2);
  background: var(--color-primary);
}

@media (max-width: 768px) {
  .productos-swiper { max-height: 500px; }
}

@media (max-width: 480px) {
  .productos-swiper { max-height: 400px; }
}
</style>

<!-- Scripts del carrusel -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<script>
document.addEventListener('DOMContentLoaded', function() {
  new Swiper('.productos-swiper', {
    slidesPerView: 1,
    loop: true,
    centeredSlides: true,

    autoplay: {
      delay: 1  000, // 2 segundos
      disableOnInteraction: false,
      pauseOnMouseEnter: true,
    },

    effect: 'creative',
    creativeEffect: {
      prev: { translate: [0, 0, -400] },
      next: { translate: ['100%', 0, 0] },
    },

    preloadImages: true,
    lazy: { loadPrevNext: true },
    grabCursor: true,
    speed: 1000,

    // Sin navegación manual (solo paginación)
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
  });
});
</script>
