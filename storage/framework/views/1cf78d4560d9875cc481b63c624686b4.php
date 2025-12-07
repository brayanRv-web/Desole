<section id="contacto" class="contact-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="contact-title">Contáctanos</h2>
                <p class="contact-subtitle">Estamos aquí para servirte</p>
            </div>
        </div>

        <!-- Contacto Minimalista con Iconos -->
        <div class="contact-minimal">
            <!-- WhatsApp -->
            <div class="contact-item-minimal" data-whatsapp="<?php echo e(config('contacto.whatsapp')); ?>">
                <i class="fab fa-whatsapp contact-icon-minimal"></i>
                <h5>WhatsApp</h5>
                <a href="https://wa.me/<?php echo e(config('contacto.whatsapp')); ?>?text=Hola%20DESOLE,%20me%20gustaría%20hacer%20un%20pedido" target="_blank">
                    Ordenar ahora
                </a>
            </div>

            <!-- Teléfono -->
            <div class="contact-item-minimal">
                <i class="fas fa-phone contact-icon-minimal"></i>
                <h5>Teléfono</h5>
                <a href="tel:<?php echo e(config('contacto.telefono')); ?>">
                    <?php echo e(config('contacto.telefono')); ?>

                </a>
            </div>

            <!-- Email -->
            <div class="contact-item-minimal">
                <i class="fas fa-envelope contact-icon-minimal"></i>
                <h5>Email</h5>
                <a href="mailto:<?php echo e(config('contacto.email')); ?>">
                    <?php echo e(config('contacto.email')); ?>

                </a>
            </div>
        </div>

        <!-- Horario Compacto -->
        <div class="horario-compact">
            <div class="horario-header">
                <i class="fas fa-clock"></i>
                <h5>Horario de Atención</h5>
            </div>
            <div class="horario-content">
                <div class="horario-line">
                    <span>Lunes - Viernes: 7:00 PM - 12:00 PM</span>
                    <span class="horario-separator">|</span>
                    <span>Sábado - Domingo: 7:00 PM - 12:00 PM</span>
                </div>
            </div>
        </div>

      
    </div>
</section>

 
<footer class="bg-zinc-800/50 border-t border-zinc-700 mt-12">
      <div class="container mx-auto px-4 py-8">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div>
                  <h3 class="text-lg font-semibold text-green-400 mb-4">Contacto</h3>
                  <div class="space-y-2 text-gray-300">
                      <p><i class="fas fa-phone w-6"></i> (961) 456-46-97</p>
                      <p><i class="fas fa-envelope w-6"></i> desole.cafeteria@gmail.com</p>
                  </div>
              </div>
              <div>
                  <h3 class="text-lg font-semibold text-green-400 mb-4">Síguenos</h3>
                  <div class="flex space-x-4">
                      <a href="https://www.facebook.com/share/1FPD5T2i9E/?mibextid=wwXIfr"  class="text-gray-300 hover:text-white transition">
                          <i class="fab fa-facebook text-2xl"></i>
                      </a>
                      <a href="https://www.instagram.com/desole_cafe?igsh=M2M2a3VsaTR6OG12" class="text-gray-300 hover:text-white transition">
                          <i class="fab fa-instagram text-2xl"></i>
                      </a>
                  </div>
              </div>
          </div>
          <div class="mt-8 pt-4 border-t border-zinc-700 text-center text-gray-400">
              <p>&copy; <?php echo e(date('Y')); ?> Désolé. Todos los derechos reservados.</p>
          </div>
      </div>
</footer>


<!-- Botón Flotante de WhatsApp Minimalista -->
<a href="https://wa.me/<?php echo e(config('contacto.whatsapp')); ?>?text=Hola%20DESOLE,%20me%20gustaría%20hacer%20un%20pedido" 
   class="whatsapp-float-minimal" 
   target="_blank"
   title="Ordenar por WhatsApp">
   <i class="fab fa-whatsapp"></i>
</a>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-whatsapp]').forEach(function(element) {
        element.addEventListener('click', function() {
            const whatsapp = this.getAttribute('data-whatsapp');
            window.open('https://wa.me/' + whatsapp + '?text=Hola%20DESOLE,%20me%20gustaría%20hacer%20un%20pedido', '_blank');
        });
    });
});
</script><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/public/secciones/_contacto.blade.php ENDPATH**/ ?>