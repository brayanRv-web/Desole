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
          <i class="fas fa-star"></i>
          <span class="stat-number">{{ Auth::guard('cliente')->user()->puntos_fidelidad }}</span>
          <span class="stat-label">Puntos fidelidad</span>
        </div>
      </div>
    </div>
  </div>
</section>
