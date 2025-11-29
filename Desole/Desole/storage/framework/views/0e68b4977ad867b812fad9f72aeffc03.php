
<?php $__env->startSection('title', 'Contacto'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-16" style="background: #0d0d0d;">
  <div class="container mx-auto px-4">

      <!-- ENCABEZADO -->
      <header class="mb-12 text-center" data-aos="fade-up">
          <h1 class="section-title" style="color: #16a34a">Contáctanos</h1>
          <p class="section-subtitle text-gray-300 max-w-2xl mx-auto">
              Estamos aquí para escucharte. ¿Tienes alguna duda, sugerencia o quieres hacer un pedido especial?
          </p>
      </header>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-5xl mx-auto">
          
          <!-- INFORMACIÓN DE CONTACTO -->
          <div class="space-y-8" data-aos="fade-right">
              
              <!-- Tarjeta WhatsApp -->
              <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800 hover:border-green-600 transition-colors group">
                  <div class="flex items-start gap-4">
                      <div class="bg-green-900/30 p-3 rounded-lg text-green-500 group-hover:bg-green-600 group-hover:text-white transition-colors">
                          <i class="fab fa-whatsapp text-2xl"></i>
                      </div>
                      <div>
                          <h3 class="text-xl font-bold text-white mb-2">WhatsApp</h3>
                          <p class="text-gray-400 mb-4">Envíanos un mensaje para pedidos rápidos.</p>
                          <a href="https://wa.me/52<?php echo e($whatsapp_number); ?>" target="_blank" class="text-green-500 font-semibold hover:text-green-400 flex items-center gap-2">
                              Enviar mensaje <i class="fas fa-arrow-right text-sm"></i>
                          </a>
                      </div>
                  </div>
              </div>

              <!-- Tarjeta Teléfono -->
              <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800 hover:border-green-600 transition-colors group">
                  <div class="flex items-start gap-4">
                      <div class="bg-green-900/30 p-3 rounded-lg text-green-500 group-hover:bg-green-600 group-hover:text-white transition-colors">
                          <i class="fas fa-phone-alt text-2xl"></i>
                      </div>
                      <div>
                          <h3 class="text-xl font-bold text-white mb-2">Llámanos</h3>
                          <p class="text-gray-400 mb-4">Atención personalizada por llamada.</p>
                          <a href="tel:+52<?php echo e($whatsapp_number); ?>" class="text-green-500 font-semibold hover:text-green-400">
                              <?php echo e($telefono); ?>

                          </a>
                      </div>
                  </div>
              </div>

              <!-- Tarjeta Email -->
              <div class="bg-zinc-900 p-6 rounded-xl border border-zinc-800 hover:border-green-600 transition-colors group">
                  <div class="flex items-start gap-4">
                      <div class="bg-green-900/30 p-3 rounded-lg text-green-500 group-hover:bg-green-600 group-hover:text-white transition-colors">
                          <i class="fas fa-envelope text-2xl"></i>
                      </div>
                      <div>
                          <h3 class="text-xl font-bold text-white mb-2">Correo Electrónico</h3>
                          <p class="text-gray-400 mb-4">Para sugerencias, quejas o facturación.</p>
                          <a href="mailto:<?php echo e($email); ?>" class="text-green-500 font-semibold hover:text-green-400">
                              <?php echo e($email); ?>

                          </a>
                      </div>
                  </div>
              </div>

          </div>

          <!-- FORMULARIO DE CONTACTO -->
          <div class="bg-zinc-900 p-8 rounded-2xl border border-zinc-800" data-aos="fade-left">
              <h3 class="text-2xl font-bold text-white mb-6">Envíanos un mensaje</h3>
              
              <form action="#" method="POST" class="space-y-6">
                  <?php echo csrf_field(); ?>
                  <div>
                      <label for="nombre" class="block text-gray-400 mb-2 text-sm uppercase tracking-wider">Nombre</label>
                      <input type="text" id="nombre" name="nombre" class="w-full bg-black border border-zinc-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:outline-none transition-colors" placeholder="Tu nombre completo">
                  </div>

                  <div>
                      <label for="email" class="block text-gray-400 mb-2 text-sm uppercase tracking-wider">Correo Electrónico</label>
                      <input type="email" id="email" name="email" class="w-full bg-black border border-zinc-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:outline-none transition-colors" placeholder="tucorreo@ejemplo.com">
                  </div>

                  <div>
                      <label for="mensaje" class="block text-gray-400 mb-2 text-sm uppercase tracking-wider">Mensaje</label>
                      <textarea id="mensaje" name="mensaje" rows="4" class="w-full bg-black border border-zinc-700 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:outline-none transition-colors" placeholder="¿En qué podemos ayudarte?"></textarea>
                  </div>

                  <button type="button" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-lg transition-all transform hover:scale-[1.02] shadow-lg shadow-green-900/20">
                      Enviar Mensaje
                  </button>
                  <p class="text-xs text-gray-500 text-center mt-4">
                      * Este formulario es demostrativo por ahora.
                  </p>
              </form>
          </div>

      </div>

      <!-- MAPA O UBICACIÓN (OPCIONAL) -->
      <div class="mt-16" data-aos="fade-up">
          <div class="bg-zinc-900 p-4 rounded-xl border border-zinc-800 h-80 flex items-center justify-center text-gray-500">
              <div class="text-center">
                  <i class="fas fa-map-marker-alt text-4xl mb-4 text-green-600"></i>
                  <p class="text-lg">Ubicación del Restaurante</p>
                  <p class="text-sm">(Aquí iría un mapa de Google Maps)</p>
              </div>
          </div>
      </div>

  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Desole\Desole\resources\views/public/contacto.blade.php ENDPATH**/ ?>