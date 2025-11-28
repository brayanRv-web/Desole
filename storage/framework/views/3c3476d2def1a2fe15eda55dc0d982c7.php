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

        <!-- Mapa con Animación -->
        <div class="mapa-animado">
            <div class="mapa-header">
                <i class="fas fa-map-marker-alt"></i>
                <h5>Nuestra Ubicación</h5>
            </div>
            <div class="map-container-minimal">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d337.47763374956827!2d-93.20833979079931!3d16.869780574649454!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2smx!4v1762201718722!5m2!1ses-419!2smx&z=16" 
                    width="100%" 
                    height="200" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
            <p class="text-center mt-3" style="color: var(--color-muted); font-size: 0.9rem;">
                Visítanos en nuestra cafetería
            </p>
        </div>

        <!-- Redes Sociales al Final -->
        <div class="social-section">
            <p class="social-title">Síguenos en redes sociales</p>
            <div class="social-links-minimal">
                <a href="https://www.facebook.com/share/1FPD5T2i9E/?mibextid=wwXIfr" class="social-link-minimal" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://www.instagram.com/desole_cafe?igsh=M2M2a3VsaTR6OG12" class="social-link-minimal" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="social-link-minimal" title="TikTok">
                    <i class="fab fa-tiktok"></i>
                </a>
            </div>
        </div>
    </div>
</section>

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
</script><?php /**PATH C:\xampp\htdocs\Desole\resources\views/public/secciones/_contacto.blade.php ENDPATH**/ ?>