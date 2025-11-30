<section id="home" class="hero">
  <div class="hero-content">
    <h1>DÉSOLÉ - Cafetería Nocturna</h1>
    <p>
      <i class="fas fa-clock"></i>
      <?php
        use Carbon\Carbon;

        // Traemos los horarios
        $horarios = \App\Models\Horario::ordenados()->get();

        // Día de hoy en español
        $diaHoy = strtolower(now()->locale('es')->isoFormat('dddd'));
        
        // Normalizar día (quitar acentos) para coincidir con BD
        $diaHoy = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $diaHoy);

        // Obtenemos el horario correspondiente al día de hoy
        $horarioHoy = $horarios->firstWhere('dia_semana', $diaHoy);

        // Convertimos apertura y cierre a Carbon si existe
        if ($horarioHoy) {
            $apertura = Carbon::createFromFormat('H:i:s', $horarioHoy->apertura)->format('g:i A');
            $cierre   = Carbon::createFromFormat('H:i:s', $horarioHoy->cierre)->format('g:i A');
        }
      ?>

      <?php if($horarioHoy && $horarioHoy->activo): ?>
        Pedidos <?php echo e($apertura); ?> - <?php echo e($cierre); ?>

      <?php else: ?>
        Cerrado hoy
      <?php endif; ?>
      <br>
      <i class="fas fa-map-marker-alt"></i> San Fernando · 
      <i class="fas fa-car"></i> Delivery Disponible
    </p>
    <div class="hero-ctas">
      <a href="<?php echo e(route('menu')); ?>" class="btn-primary">
        <i class="fas fa-utensils"></i> Ver Menú
      </a>
      <a href="https://wa.me/<?php echo e($whatsapp_number ?? '9614564697'); ?>" target="_blank" class="btn-ghost">
        <i class="fab fa-whatsapp"></i> Ordenar por WhatsApp
      </a>
    </div>
  </div>

  <div class="hero-art">
    <?php
      $productoDestacado = \App\Models\Producto::where('status', 'activo')->inRandomOrder()->first();
    ?>
    <?php if($productoDestacado && $productoDestacado->imagen): ?>
      <img src="<?php echo e(asset('storage/' . $productoDestacado->imagen)); ?>" alt="<?php echo e($productoDestacado->nombre); ?>" />
    <?php else: ?>
      <img src="<?php echo e(asset('uploads/productos/alitas.jpg')); ?>" alt="Especialidades DESOLE" />
    <?php endif; ?>
  </div>
</section>
<?php /**PATH C:\xampp\htdocs\Desole\resources\views/public/secciones/_hero.blade.php ENDPATH**/ ?>