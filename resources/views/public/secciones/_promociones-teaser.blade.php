<section id="promociones" class="promociones-section">
  <div class="container">
    <h2 class="section-title" data-aos="fade-up">Promociones Activas</h2>

    @php
      $promociones = App\Models\Promocion::where('activa', 1)
                  ->where('fecha_inicio', '<=', now())
                  ->where('fecha_fin', '>=', now())
                  ->orderBy('fecha_inicio', 'desc')
                  ->get();
    @endphp

    @if ($promociones->isEmpty())
      <div class="no-promociones" data-aos="fade-up">
        <i class="fas fa-tags"></i>
        <p>Por el momento no hay promociones activas</p>
        <p>Vuelve pronto para descubrir nuestras ofertas especiales</p>
      </div>
    @else
      <div class="promociones-grid">
        @foreach ($promociones as $promo)
          <div class="promo-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
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
  </div>
</section>
