
<?php $__env->startSection('title', 'Preguntas Frecuentes'); ?>

<?php $__env->startSection('content'); ?>
<section class="py-16">
  <div class="container mx-auto px-4">

      <!-- ENCABEZADO -->
      <header class="mb-12 text-center" data-aos="fade-up">
          <h1 class="section-title" style="color: #16a34a">Preguntas Frecuentes</h1>
          <p class="section-subtitle text-gray-300 max-w-2xl mx-auto">
              Encuentra respuestas rápidas a las dudas más comunes sobre productos, pedidos, entregas, facturación y más.
          </p>
          
      </header>

      <!-- ESTILOS FAQ -->
      <style>
        .faq-box {
            background: #111;
            border-left: 4px solid #16a34a;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            transition: all .25s ease;
            box-shadow: 0 2px 6px #0005;
        }

        .faq-box:hover {
            background: #161616;
            box-shadow: 0 8px 20px #0008;
            transform: translateY(-2px);
        }

        summary {
            color: #16a34a;
            font-weight: 600;
            cursor: pointer;
            font-size: 1.05rem;
            letter-spacing: .3px;
        }

        summary::-webkit-details-marker {
            display: none;
        }

        summary::after {
            content: "＋";
            float: right;
            color: #16a34a;
            transition: transform .3s ease;
        }

        details[open] summary::after {
            transform: rotate(45deg);
        }

        details p {
            color: #cfcfcf;
            font-size: .95rem;
            margin-top: .75rem;
            line-height: 1.45rem;
        }
      </style>


      <!-- SECCIONES FAQ COMPLETAS -->
      <?php
          $faq = [
              "Sobre los productos" => [
                  "¿Los ingredientes son frescos?" => "Sí, trabajamos con proveedores locales y recibimos insumos frescos todos los días.",
                  "¿Tienen opciones veganas?" => "Sí, contamos con bebidas vegetales, postres veganos y platillos sin ingredientes de origen animal.",
                  "¿Los postres contienen azúcar añadida?" => "Algunos sí, pero también ofrecemos opciones sin azúcar.",
                  "¿El café es importado?" => "Tenemos café nacional e importado, dependiendo de la temporada."
              ],

              "Pedidos y compras" => [
                  "¿Cómo hago un pedido en línea?" => "Solo entra al menú, selecciona tus productos y confirma la orden. Es rápido y sencillo.",
                  "¿Puedo modificar mi pedido después de enviarlo?" => "Sí, siempre y cuando aún no haya sido preparado. Escríbenos al chat.",
                  "¿Qué métodos de pago aceptan?" => "Efectivo, tarjeta, transferencia y pagos con QR.",
                  "¿Puedo hacer un pedido para otra persona?" => "Sí, solo especifica los datos de entrega."
              ],

              "Entregas y recolección" => [
                  "¿Cuánto tarda la entrega?" => "Generalmente entre 20 y 40 minutos, dependiendo de la zona.",
                  "¿Puedo pasar a recoger mi pedido?" => "Sí, puedes elegir la opción Pick-up al finalizar tu compra.",
                  "¿Tienen servicio a todo el municipio?" => "Dependemos de la zona; mostramos cobertura en tiempo real al ingresar tu dirección.",
                  "¿Qué hago si mi pedido llegó incompleto?" => "Contáctanos y lo solucionamos rápidamente, ya sea reponiendo el producto o con un cupón."
              ],

              "Facturación" => [
                  "¿Puedo solicitar factura?" => "Sí, después de tu compra puedes ingresar tus datos fiscales en el apartado de facturación.",
                  "¿Cuánto tiempo tengo para facturar?" => "El sistema te permite facturar dentro del mismo mes de la compra.",
                  "¿Qué datos necesito para facturar?" => "RFC, razón social, correo y uso de CFDI.",
                  "¿Cómo recibo mi factura?" => "La enviamos automáticamente a tu correo en formato XML y PDF."
              ],

              "Sobre la empresa" => [
                  "¿Qué es Desolé?" => "Somos un negocio local enfocado en ofrecer alimentos y bebidas con un estilo moderno y fresco.",
                  "¿Tienen sucursales?" => "Actualmente operamos desde un solo punto, con servicio a domicilio.",
                  "¿Hacen colaboraciones o eventos?" => "Sí, puedes escribirnos para eventos especiales, bazares o colaboraciones.",
                  "¿Cuál es su horario?" => "Abrimos de lunes a domingo de 9:00 AM a 10:00 PM."
              ],

              "Privacidad y soporte" => [
                  "¿Mis datos están seguros?" => "Sí, utilizamos cifrado y cumplimos con las normativas de protección de datos.",
                  "¿Dónde puedo pedir soporte?" => "Directamente en el chat de la página o a través del correo de atención.",
                  "¿Qué hago si tengo un problema con mi cuenta?" => "Podemos ayudarte a restablecer correo, contraseña o datos ya registrados.",
                  "¿Usan mis datos para publicidad?" => "Solo con tu consentimiento; nunca vendemos información a terceros."
              ]
          ];
      ?>


      <!-- Render de FAQ -->
      <?php $__currentLoopData = $faq; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $titulo => $preguntas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <section class="mb-10" data-aos="fade-up">
          <h2 class="section-title text-left mb-4" style="color: #ffffffff"><?php echo e($titulo); ?></h2>

          <div class="space-y-3">
              <?php $__currentLoopData = $preguntas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <details class="faq-box">
                  <summary><?php echo e($p); ?></summary>
                  <p><?php echo e($r); ?></p>
              </details>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
      </section>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      
      <!-- SECCIÓN DE AYUDA -->
      <div class="mt-16 text-center border-t border-gray-800 pt-10" data-aos="fade-up">
          <h3 class="text-xl font-semibold text-white mb-3">¿Aún tienes dudas?</h3>
          <p class="text-gray-400 mb-6 max-w-md mx-auto">Si no encontraste la respuesta que buscabas, nuestro equipo está listo para ayudarte.</p>
          
          <a href="<?php echo e(url('/#contacto')); ?>" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full transition-all transform hover:scale-105 shadow-lg shadow-green-900/20">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
              Contactar Soporte
          </a>
      </div>

  </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('public.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Desole\Desole\resources\views/public/faq.blade.php ENDPATH**/ ?>