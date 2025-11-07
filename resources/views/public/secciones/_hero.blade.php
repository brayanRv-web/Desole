<section id="home" class="hero">
  <div class="hero-content">
    <h1>DÉSOLÉ - Cafetería Nocturna</h1>
    <p>
      <i class="fas fa-clock"></i>
      @php
        use Carbon\Carbon;

        // Traemos los horarios
        $horarios = \App\Models\Horario::ordenados()->get();

        // Día de hoy en español
        $diaHoy = strtolower(now()->locale('es')->isoFormat('dddd'));

        // Obtenemos el horario correspondiente al día de hoy
        $horarioHoy = $horarios->firstWhere('dia_semana', $diaHoy);

        // Convertimos apertura y cierre a Carbon si existe
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
      <img src="{{ asset('uploads/productos/alitas.jpg') }}" alt="Especialidades DESOLE" />
    @endif
  </div>
</section>
