<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DESOLE - Cafetería nocturna | San Fernando</title>
  <meta name="description" content="DESOLE - Cafetería nocturna. Alitas, pizzadogs, frappés y promociones nocturnas. Pedidos por WhatsApp o en línea." />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Estilos -->
  <link rel="stylesheet" href="{{ asset('css/desole.css') }}">
  <link rel="icon" href="{{ asset('assets/favicon.png') }}">
  
  <style>
    .logo { width: 120px; height: auto; object-fit: contain; display: block; }
    .nav-wrap { background: rgba(0, 0, 0, 0.6); position: fixed; top: 0; width: 100%; z-index: 1000; }
    .hero { display: flex; justify-content: center; align-items: center; min-height: 90vh;
            background-color: #5d7c66; color: white; text-align: center; padding-top: 100px; }
    .hero img { max-width: 400px; height: auto; border-radius: 10px; }
    .notification { position: fixed; top: 20px; right: 20px; background: #d35400; color: #fff;
                    padding: 12px 18px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                    transform: translateX(400px); transition: transform 0.3s ease; z-index: 9999; font-weight: 500; }
    .notification.show { transform: translateX(0); }
    
    /* Estilos para la sección de promociones */
    .promociones-section {
      background: #1a1a1a;
      color: white;
      padding: 60px 20px;
      min-height: 70vh;
    }
    
    .promociones-title {
      text-align: center;
      font-size: 2.5rem;
      font-weight: bold;
      margin-bottom: 50px;
      color: #22c55e;
    }
    
    .promociones-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .promo-card {
      background: #2d2d2d;
      border-radius: 15px;
      padding: 25px;
      border: 1px solid #333;
      transition: all 0.3s ease;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .promo-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(34, 197, 94, 0.2);
      border-color: #22c55e;
    }
    
    .promo-name {
      font-size: 1.5rem;
      font-weight: bold;
      color: #22c55e;
      margin-bottom: 15px;
    }
    
    .promo-desc {
      color: #e5e5e5;
      margin-bottom: 15px;
      line-height: 1.5;
    }
    
    .promo-detail {
      margin-bottom: 10px;
      color: #ccc;
    }
    
    .promo-detail strong {
      color: white;
    }
    
    .promo-dates {
      font-size: 0.9rem;
      color: #999;
      margin-top: 15px;
    }
    
    .aprovechar-btn {
      background: #22c55e;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 20px;
      width: 100%;
    }
    
    .aprovechar-btn:hover {
      background: #16a34a;
      transform: scale(1.05);
    }
    
    .no-promociones {
      text-align: center;
      color: #999;
      font-size: 1.2rem;
      margin-top: 50px;
    }
    
    .no-promociones i {
      font-size: 3rem;
      margin-bottom: 20px;
      color: #444;
    }
  </style>
</head>

<body data-theme="default">

  @include('public.secciones._navbar')
  @include('public.secciones._hero')

  <main>
    @include('public.secciones._menu')
    
    <!-- Sección de Promociones con datos reales -->
    <section id="promociones" class="promociones-section">
      <h2 class="promociones-title">🎉 Promociones Activas</h2>

      @php
          use Illuminate\Support\Str;
          use App\Models\Promocion;

          // Trae solo las promociones activas (activa = 1)
          $promociones = Promocion::where('activa', 1)
              ->where('fecha_inicio', '<=', now())
              ->where('fecha_fin', '>=', now())
              ->orderBy('fecha_inicio', 'desc')
              ->get();
      @endphp

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
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];

      const updateCartCount = () => {
        const count = cart.reduce((t, i) => t + i.quantity, 0);
        document.getElementById('cart-count').textContent = count;
      };

      const showNotification = msg => {
        const existing = document.querySelector('.notification');
        if (existing) existing.remove();

        const n = document.createElement('div');
        n.className = 'notification';
        n.innerHTML = `<i class="fas fa-bell"></i> ${msg}`;
        document.body.appendChild(n);
        setTimeout(() => n.classList.add('show'), 100);
        setTimeout(() => { n.classList.remove('show'); n.remove(); }, 3200);
      };

      document.querySelectorAll('.add-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const id = btn.dataset.id;
          const name = btn.dataset.name;
          const price = parseFloat(btn.dataset.price);
          const item = cart.find(i => i.id === id);
          item ? item.quantity++ : cart.push({ id, name, price, quantity: 1 });
          localStorage.setItem('cart', JSON.stringify(cart));
          updateCartCount();
          showNotification('✅ Producto agregado');
        });
      });

      const quickOrder = document.getElementById('quick-order');
      if (quickOrder) {
        quickOrder.addEventListener('click', () => {
          if (!cart.length) return showNotification('🛒 Agrega productos primero');
          let msg = '¡Hola! Quiero hacer un pedido en DESOLE:\n\n';
          cart.forEach(i => msg += `• ${i.quantity}x ${i.name} - $${(i.price * i.quantity).toFixed(2)}\n`);
          msg += `\n💰 Total: $${cart.reduce((t, i) => t + i.price * i.quantity, 0).toFixed(2)}`;
          window.open(`https://wa.me/529614564697?text=${encodeURIComponent(msg)}`, '_blank');
        });
      }

      // Agregar funcionalidad a los botones de promociones
      document.querySelectorAll('.aprovechar-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          const promoId = this.dataset.promoId;
          const promoName = this.dataset.promoName;
          showNotification(`🎊 Promoción "${promoName}" aplicada a tu pedido`);
          
          // Aquí puedes agregar lógica adicional para aplicar la promoción
          // Por ejemplo, agregar un código de descuento al carrito
        });
      });

      updateCartCount();

      document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', e => {
          e.preventDefault();
          const target = document.querySelector(link.getAttribute('href'));
          if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
      });
    });
  </script>
</body>
</html>