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
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body data-theme="default">

  @include('public.secciones._navbar')
  @include('public.secciones._hero')

  <main>
    @include('public.secciones._menu')
    
    <!-- Sección de Promociones - Mismo diseño -->
    <section id="promociones" class="bg-gray-100 py-12 px-6 min-h-screen">
      <h2 class="text-3xl font-bold text-center mb-10 text-green-700">
        🎉 Promociones Activas
      </h2>

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
          <p class="text-center text-gray-600 text-lg">No hay promociones disponibles en este momento.</p>
      @else
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
              @foreach ($promociones as $promo)
                  <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition duration-300">
                      <h3 class="text-xl font-semibold text-green-600 mb-2">
                          {{ $promo->nombre }}
                      </h3>
                      <p class="text-gray-700 mb-3">
                          {{ Str::limit($promo->descripcion, 120) }}
                      </p>

                      <p class="mb-2">
                          <span class="font-semibold text-gray-800">Tipo de Descuento:</span>
                          <span class="text-gray-600">
                              {{ $promo->tipo_descuento === 'porcentaje' ? $promo->valor_descuento . '%' : '$' . number_format($promo->valor_descuento, 2) }}
                          </span>
                      </p>

                      <p class="text-sm text-gray-500">
                          <span class="font-semibold">Válido del:</span>
                          {{ \Carbon\Carbon::parse($promo->fecha_inicio)->format('d/m/Y') }}
                          <span class="font-semibold">al</span>
                          {{ \Carbon\Carbon::parse($promo->fecha_fin)->format('d/m/Y') }}
                      </p>

                      <div class="mt-4 flex justify-center">
                          <button class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-300">
                              ¡Aprovechar!
                          </button>
                      </div>
                  </div>
              @endforeach
          </div>
      @endif
    </section>
    
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
  </style>
</body>
</html>