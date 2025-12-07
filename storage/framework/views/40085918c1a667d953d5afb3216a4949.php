
<section id="home" class="hero">
  <!-- Carrusel de Imágenes -->
  <div class="hero-carousel">
    <div class="carousel-track">
      <!-- Imagen 1 -->
      <div class="carousel-slide active">
        <img src="<?php echo e(asset('img/carousel/slide-1.jpg')); ?>" alt="Ambiente nocturno DÉSOLÉ" />
        <div class="slide-overlay"></div>
      </div>
      <!-- Imagen 2 -->
      <div class="carousel-slide">
        <img src="<?php echo e(asset('img/carousel/slide-2.jpg')); ?>" alt="Bebidas especiales DÉSOLÉ" />
        <div class="slide-overlay"></div>
      </div>
      <!-- Imagen 3 -->
      <div class="carousel-slide">
        <img src="<?php echo e(asset('img/carousel/slide-3.jpg')); ?>" alt="Alitas DÉSOLÉ" />
        <div class="slide-overlay"></div>
      </div>
    </div>

    <!-- Controles de Navegación -->
    <button class="carousel-control prev" aria-label="Imagen anterior">
      <i class="fas fa-chevron-left"></i>
    </button>
    <button class="carousel-control next" aria-label="Siguiente imagen">
      <i class="fas fa-chevron-right"></i>
    </button>

    <!-- Indicadores -->
    <div class="carousel-indicators">
      <?php for($i = 0; $i < 3; $i++): ?>
        <button class="indicator <?php echo e($i === 0 ? 'active' : ''); ?>" data-slide="<?php echo e($i); ?>"></button>
      <?php endfor; ?>
    </div>
  </div>

   <!-- Contenido del Hero SUPERPUESTO -->
    <div class="hero-content-overlay">
      <h1 class="hero-title">DÉSOLÉ<br><span class="hero-subtitle">Cafetería Nocturna</span></h1>
      
      <div class="hero-info">
        <div class="info-item">
          <i class="fas fa-clock"></i>
          <span>
            <?php
              use Carbon\Carbon;
              $horarios = \App\Models\Horario::ordenados()->get();
              $diaHoy = strtolower(now()->locale('es')->isoFormat('dddd'));
              $horarioHoy = $horarios->firstWhere('dia_semana', $diaHoy);

              if($horarioHoy && $horarioHoy->activo) {
                  $apertura = Carbon::createFromFormat('H:i:s', $horarioHoy->apertura)->format('g:i A');
                  $cierre = Carbon::createFromFormat('H:i:s', $horarioHoy->cierre)->format('g:i A');
                  echo "Pedidos {$apertura} - {$cierre}";
              } else {
                  echo "Cerrado hoy";
              }
            ?>
          </span>
        </div>
        
        <div class="info-item">
          <i class="fas fa-map-marker-alt"></i>
          <span>San Fernando</span>
        </div>
        
        <div class="info-item">
          <i class="fas fa-car"></i>
          <span>Delivery Disponible</span>
        </div>
      </div>
    </div>
  </div>
</section>
<?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/public/secciones/_hero.blade.php ENDPATH**/ ?>