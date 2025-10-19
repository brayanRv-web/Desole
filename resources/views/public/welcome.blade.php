<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>DESOLE - Cafetería nocturna | San Fernando</title>
  <meta name="description" content="DESOLE - Cafetería nocturna. Alitas, pizzadogs, frappés y combos nocturnos. Pedidos por WhatsApp o en línea." />

  <script src="../src/js/app.js" defer></script>
  <link rel="stylesheet" href="../resources/css/desole.css" />


  <link rel="icon" href="/assets/favicon.png" />
</head>
<body data-theme="default">
  <!-- navbar -->
  <header class="nav-wrap">
    <nav class="nav">
      <a class="brand" href="#">
        <img src="{{ asset('uploads/banners/logo.png') }}" alt="DESOLE"
     style="width: 80px; height: auto;" />
      </a>

      <ul class="nav-links">
        <li><a href="#home">Inicio</a></li>
        <li><a href="#menu">Menú</a></li>
        <li><a href="#combos">Combos</a></li>
        <li><a href="../src/pedidos_especial.html">Pedidos especiales</a></li>
        <li><a href="#reseñas">Reseñas</a></li>
        <li><a href="#contacto">Contacto</a></li>
      </ul>

      <div class="nav-actions">
        <button id="cart-toggle" class="cart-btn" aria-label="Abrir carrito">
          🛒 <span id="cart-count">0</span>
        </button>
        <button id="mobile-menu-btn" class="hamburger" aria-label="Abrir menú móvil">☰</button>
      </div>
    </nav>
  </header>

  <!--hero-->
  <section id="home" class="hero">
    <div class="hero-content">
      <h1>DÉSOLÉ - Cafetería nocturna</h1>
      <p>Alitas, pizzadogs y frappés perfectos para la noche en San Fernando. Pedidos 8:00 pm - 12:00 am</p>
      <div class="hero-ctas">
        <a href="#menu" class="btn-primary">Ver Menú</a>
        <button id="quick-order" class="btn-ghost">Ordena por WhatsApp</button>
      </div>
    </div>
    <div class="hero-art">
     <img src="{{ asset('uploads/productos/alitas.jpg') }}" alt="Alitas" class="img-fluid rounded shadow-lg" />
  </section>

  <!--seccion de menu-->
  <main>
    <section id="menu" class="menu-section">
      <header class="section-header">
        <h2>Menú</h2>
        <p>Elige tus favoritos. Toque para agregar al carrito.</p>
      </header>

      <div class="menu-grid">
        <!-- Alitas -->
        <article class="menu-category">
          <h3>Alitas</h3>
          <ul class="menu-list">
            <li class="menu-item" data-id="a1" data-name="Alitas 8 piezas" data-price="85">8 piezas — $85 <button class="add-btn">Agregar</button></li>
            <li class="menu-item" data-id="a2" data-name="Alitas 12 piezas" data-price="125">12 piezas — $125 <button class="add-btn">Agregar</button></li>
            <li class="menu-item" data-id="a3" data-name="Alitas 18 piezas" data-price="185">18 piezas — $185 <button class="add-btn">Agregar</button></li>
            <li class="menu-item" data-id="a4" data-name="Alitas 24 piezas" data-price="240">24 piezas — $240 <button class="add-btn">Agregar</button></li>
            <li class="menu-item" data-id="a5" data-name="Alitas 36 piezas" data-price="350">36 piezas — $350 <button class="add-btn">Agregar</button></li>
          </ul>
        </article>

        <!-- Boneless -->
        <article class="menu-category">
          <h3>Boneless</h3>
          <ul class="menu-list">
            <li class="menu-item" data-id="b1" data-name="Boneless 250 g" data-price="80">250 g — $80 <button class="add-btn">Agregar</button></li>
            <li class="menu-item" data-id="b2" data-name="Boneless 500 g" data-price="150">500 g — $150 <button class="add-btn">Agregar</button></li>
          </ul>
        </article>

        <!-- frappes -->
        <article class="menu-category">
          <h3>Frappés</h3>
          <ul class="menu-list">
            <!-- Cada frappe comparte precio 70 -->
            <li class="menu-item" data-id="f1" data-name="Frappé Mazapán" data-price="70">Mazapán — $70 <button class="add-btn">Agregar</button></li>
            <li class="menu-item" data-id="f2" data-name="Frappé Rompope" data-price="70">Rompope — $70 <button class="add-btn">Agregar</button></li>
            <li class="menu-item" data-id="f3" data-name="Frappé Café" data-price="70">Café — $70 <button class="add-btn">Agregar</button></li>
            <!-- añadir mas sabores en /data/menu.json o en la base de datos luego, se puede agregar despues xd -->
          </ul>
        </article>

        <!--hamburguesas y hotdogs resume-->
        <article class="menu-category">
          <h3>Hamburguesas y Hotdogs</h3>
          <ul class="menu-list">
            <li class="menu-item" data-id="h1" data-name="Hamburguesa Italiana" data-price="70">Italiana — $70 <button class="add-btn">Agregar</button></li>
            <li class="menu-item" data-id="hd1" data-name="Hotdog Clásico" data-price="50">Hotdog Clásico — $50 <button class="add-btn">Agregar</button></li>
            <li class="menu-item" data-id="pd1" data-name="Pizzadog" data-price="50">Pizzadog — $50 <button class="add-btn">Agregar</button></li>
          </ul>
        </article>
      </div>
    </section>

    <!--seccion de combos-->
    <section id="combos" class="combos-section">
      <header class="section-header">
        <h2>Combos</h2>
        <p>Promos pensadas para compartir.</p>
      </header>

      <ul class="combos-list">
        <li class="combo-item" data-id="c1" data-name="Combo Doglitas I" data-price="130">
          <strong>Combo Doglitas I</strong> — 1 hotdog, 6 alitas y refresco 600ml — $130 <button class="add-btn">Agregar combo</button>
        </li>
        <li class="combo-item" data-id="c2" data-name="Combo Doglitas II" data-price="170">
          <strong>Combo Doglitas II</strong> — 1 hotdog, 6 alitas y 1 frappé — $170 <button class="add-btn">Agregar combo</button>
        </li>
      </ul>
    </section>

   <!--pedidos especiales-->
    <section id="especiales" class="special-orders">
      <header class="section-header">
        <h2>Pedidos especiales</h2>
        <p>Diseñamos combos personalizados para regalos: decoración, globos, tarjetas y más.</p>
        <div class="btn-spacing"></div>
        <a href="../src/pedidos_especial.html" class="btn-primary">Solicitar pedido especial</a>
      </header>
    </section>

    <!--reseñas-->
    <section id="reseñas" class="reviews-section">
      <header class="section-header">
        <h2>Reseñas y valoraciones</h2>
        <p>Deja tu opinión para ayudar a otros.</p>
      </header>

      <div class="reviews-wrap">
        <ul id="reviews-list" class="reviews-list">
          <!-- se podrian generar reseñas por js -->
        </ul>

        <form id="review-form" class="review-form">
          <label for="name">Nombre</label>
          <input id="name" name="name" required />

          <label for="rating">Calificación</label>
          <select id="rating" name="rating">
            <option value="5">5 — Excelente</option>
            <option value="4">4 — Muy bueno</option>
            <option value="3">3 — Bueno</option>
            <option value="2">2 — Regular</option>
            <option value="1">1 — Malo</option>
          </select>

          <label for="comment">Comentario</label>
          <textarea id="comment" name="comment" rows="3"></textarea>

          <button type="submit" class="btn-primary">Enviar reseña</button>
        </form>
      </div>
    </section>

    <!--contacto-->
    <section id="contacto" class="contact-section">
      <h2>Contacto</h2>
      <p>Pedidos por WhatsApp: <a href="https://wa.me/529614564697">(55) XXX XXXX</a> — También recibimos llamadas.</p>
      <p>Horario: 8:00 pm — 12:00 am (noches)</p>

      <div class="payment-methods">
        <h4>Métodos de pago</h4>
        <p>Efectivo, transferencia, pago con QR (depende disponibilidad), pago en línea (próximamente)</p>
      </div>
    </section>
  </main>

  <!-- carrito-->
  <aside id="cart" class="cart" aria-hidden="true">
    <header>
      <h3>Tu pedido</h3>
      <button id="close-cart" aria-label="Cerrar carrito">✕</button>
    </header>
    <div id="cart-items" class="cart-items">
      <!-- Items añadidos via JS -->
    </div>
    <footer class="cart-footer">
      <div class="cart-total">Total: $<span id="cart-total">0</span></div>
      <div class="cart-actions">
        <button id="checkout-btn" class="btn-primary">Pagar</button>
        <button id="clear-cart" class="btn-ghost">Vaciar</button>
      </div>
      <small class="delivery-note">Costo extra si aplica por entrega a domicilio</small>
    </footer>
  </aside>

<!--boton flotante de chat-->
  <button id="chat-toggle" class="chat-toggle-btn" aria-label="Abrir chat de ayuda">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
      <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
    </svg>
  </button>

<!--chatbox-->
  <div id="chatbox" class="chatbox" aria-hidden="true" style="display: none;">
    <header class="chat-header">
      <span>¿Tienes dudas?</span>
      <button id="chat-close" class="chat-close-btn" aria-label="Cerrar chat">✕</button>
    </header>
    <div id="chat-messages" class="chat-messages">
      <div class="bot-message">Hola 👋. Puedes preguntar por menú, tiempos de entrega o combos.</div>
    </div>
    <form id="chat-form" class="chat-form">
      <input id="chat-input" placeholder="Escribe tu mensaje..." />
      <button type="submit" class="chat-send-btn">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M2 21L23 12L2 3V10L17 12L2 14V21Z" fill="currentColor"/>
        </svg>
        <span class="sr-only">Enviar mensaje</span>
      </button>
    </form>
  </div>

  <!--boton de ws-->
  <a class="whatsapp-float" href="https://wa.me/529614564697" aria-label="Contactar por WhatsApp">WhatsApp</a>


</body>
</html>

