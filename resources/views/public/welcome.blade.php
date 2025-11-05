<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DÉSOLÉ - Cafetería nocturna</title>
  <meta name="description" content="DESOLE - Cafetería nocturna. Alitas, pizzadogs, frappés y promociones nocturnas. Pedidos por WhatsApp o en línea." />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Estilos -->
  <link rel="stylesheet" href="{{ asset('css/desole.css') }}"> <!-- Hace referencia al CSS desole.css de la carpeta public no la de resources --> 
  <link rel="stylesheet" href="{{ asset('css/hero.css') }}">
  <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
</head>

<body data-theme="default">
  <!-- ELEMENTOS ESTRUCTURALES GLOBALES -->
   @include('public.secciones._navbar')
   @include('public.secciones._hero')

  <main>
    @include('public.secciones._menu')
    
    <!-- Sección de Promociones con datos reales, promociones con base de datos -->
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

              <button class="aprovechar-btn" data-promo-id="{{ $promo->id }}" data-promo-name="{{ $promo->nombre }}">
                ¡Aprovechar!
              </button>
            </div>
          @endforeach
        </div>
      @endif
    </section>
    
    @include('public.secciones._especiales')
    @include('public.secciones._reseñas')
    @include('public.secciones._contacto')
  </main>

  <!-- Scripts -->
  <script src="{{ asset('js/cart.js') }}"></script>
</body>
</html>