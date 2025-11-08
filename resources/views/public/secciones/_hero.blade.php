<section id="home" class="hero">
  {{-- === CARRUSEL HERO === --}}
  @if(isset($heroImages) && $heroImages->count() > 0)
    <div class="hero-carousel">
      @foreach($heroImages as $index => $img)
        <div class="hero-slide {{ $index === 0 ? 'active' : '' }}">
          <img src="{{ asset('storage/' . $img->ruta) }}" alt="{{ $img->titulo ?? 'Imagen hero' }}">
          @if($img->titulo || $img->descripcion)
            <div class="hero-caption">
              <h2>{{ $img->titulo }}</h2>
              @if($img->descripcion)
                <p>{{ $img->descripcion }}</p>
              @endif
            </div>
          @endif
        </div>
      @endforeach
    </div>
  @else
    {{-- Fallback si no hay imágenes dinámicas --}}
    <div class="hero-carousel">
      <div class="hero-slide active">
        <img src="{{ asset('uploads/hero_default.jpg') }}" alt="Bienvenido a DÉSOLÉ">
        <div class="hero-caption">
          <h2>DÉSOLÉ - Cafetería Nocturna</h2>
          <p>Disfruta de un ambiente único con nuestro café y postres nocturnos.</p>
        </div>
      </div>
    </div>
  @endif

  {{-- === CONTENIDO PRINCIPAL HERO === --}}
  <div class="hero-content">
    <h1>DÉSOLÉ - Cafetería Nocturna</h1>
    <p>
      <i class="fas fa-clock"></i>
      @php
        use Carbon\Carbon;
        $horarios = \App\Models\Horario::ordenados()->get();
        $diaHoy = strtolower(now()->locale('es')->isoFormat('dddd'));
        $horarioHoy = $horarios->firstWhere('dia_semana', $diaHoy);
        if ($horarioHoy) {
            $apertura = Carbon::createFromFormat('H:i:s', $horarioHoy->apertura)->format('g:i A');
            $cierre   = Carbon::createFromFormat('H:i:s', $horarioHoy->cierre)->format('g:i A');
        }
      @endphp

      @if($horarioHoy && $horarioHoy->activo)
        Pedidos {{ $apertura }} - {{ $cierre }}
      @else
        Cerrado hoy
      @endif
      <br>
      <i class="fas fa-map-marker-alt"></i> San Fernando · 
      <i class="fas fa-car"></i> Delivery Disponible
    </p>

    <div class="hero-ctas">
      <a href="#menu" class="btn-primary">
        <i class="fas fa-utensils"></i> Ver Menú
      </a>
      <button id="quick-order" class="btn-ghost">
        <i class="fab fa-whatsapp"></i> Ordenar por WhatsApp
      </button>
    </div>
  </div>

  <div class="hero-art">
    @php
      $productoDestacado = \App\Models\Producto::where('estado', 'activo')->inRandomOrder()->first();
    @endphp
    @if($productoDestacado && $productoDestacado->imagen)
      <img src="{{ asset('storage/' . $productoDestacado->imagen) }}" alt="{{ $productoDestacado->nombre }}" />
    @else
      <img src="{{ asset('uploads/productos/alitas.jpg') }}" alt="Especialidades DESOLÉ" />
    @endif
  </div>
</section>

{{-- Pequeño script para rotar automáticamente --}}
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll(".hero-slide");
    let index = 0;
    if (slides.length > 1) {
      setInterval(() => {
        slides[index].classList.remove("active");
        index = (index + 1) % slides.length;
        slides[index].classList.add("active");
      }, 5000);
    }
  });
</script>
