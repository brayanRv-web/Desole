<section id="reseñas" class="reseñas-section">
    <div class="container">
        <!-- Header Section más compacto -->
        <div class="text-center mb-6">
            <h2 class="section-title">Reseñas de Nuestros Clientes</h2>
            <p class="section-subtitle">Tu opinión es muy importante para nosotros</p>
        </div>

        <?php
            use App\Models\Resena;
            $reseñas = Resena::orderBy('created_at', 'desc')->limit(6)->get();
            $promedio = $reseñas->avg('calificacion');
            $totalReseñas = $reseñas->count();
        ?>

        <!-- Stats y Formulario - Diseño más compacto -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card form-reseña-card shadow-lg border-0">
                    <div class="card-body p-4"> <!-- Reducido de p-5 a p-4 -->
                        <div class="row align-items-center">
                            <!-- Stats Column más compacta -->
                            <div class="col-md-4 mb-4 mb-md-0"> <!-- Reducido de col-md-5 a col-md-4 -->
                                <div class="text-center">
                                    <div class="promedio-calificacion mb-3">
                                        <div class="display-4 fw-bold text-warning mb-1"><?php echo e(number_format($promedio, 1)); ?></div> <!-- Reducido de display-1 a display-4 -->
                                        <div class="estrellas-promedio mb-2">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= floor($promedio)): ?>
                                                    <span class="text-warning">★</span>
                                                <?php else: ?>
                                                    <span class="text-secondary">☆</span>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="text-muted small">Basado en <?php echo e($totalReseñas); ?> reseñas</p> <!-- Texto más pequeño -->
                                    </div>
                                    
                                    <div class="distribucion-calificaciones">
                                        <?php for($i = 5; $i >= 1; $i--): ?>
                                            <?php
                                                $count = $reseñas->where('calificacion', $i)->count();
                                                $percentage = $totalReseñas > 0 ? ($count / $totalReseñas) * 100 : 0;
                                            ?>
                                            <div class="d-flex align-items-center mb-1"> <!-- Reducido espacio -->
                                                <span class="text-warning me-1 small"><?php echo e($i); ?>★</span> <!-- Texto más pequeño -->
                                                <div class="progress flex-grow-1 mx-1 distribucion-progress">
                                                    <div class="progress-bar bg-warning distribucion-progress-bar" 
                                                         data-width="<?php echo e(number_format($percentage, 2)); ?>"></div>
                                                </div>
                                                <span class="text-muted small"><?php echo e($count); ?></span>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Column más compacto -->
                            <div class="col-md-8"> <!-- Aumentado de col-md-7 a col-md-8 -->
                                <h5 class="card-title text-center mb-3 text-white"> <!-- Reducido de h4 a h5 -->
                                    <i class="fas fa-edit me-2"></i>Comparte Tu Experiencia
                                </h5>
                                
                                <form id="form-reseña" action="<?php echo e(route('reseñas.store')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    
                                    <!-- Campos en una sola fila para ahorrar espacio -->
                                    <div class="row g-2 mb-3"> <!-- Reducido espacio -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre" class="form-label small"> 
                                                    <i class="fas fa-user me-1"></i> Tu Nombre *
                                                </label>
                                                <input type="text" class="form-control"
                                                       id="nombre" name="nombre" 
                                                       placeholder="Tu nombre" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="form-label small">
                                                <i class="fas fa-envelope me-1"></i>
                                                    Email (opcional)
                                                </label>
                                                <input type="email" class="form-control" 
                                                       id="email" name="email" 
                                                       placeholder="tu@email.com">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Calificación más compacta -->
                                    <div class="form-group mb-3"> <!-- Reducido espacio -->
                                        <label class="form-label small mb-2"> <!-- Texto más pequeño -->
                                            <i class="fas fa-star me-1"></i>Tu Calificación *
                                        </label>
                                        <div class="estrellas-form-container text-center">
                                            <div class="estrellas-form">
                                                <?php for($i = 5; $i >= 1; $i--): ?>
                                                    <input type="radio" id="star<?php echo e($i); ?>" name="calificacion" value="<?php echo e($i); ?>" required>
                                                    <label for="star<?php echo e($i); ?>" title="<?php echo e($i); ?> estrellas">★</label>
                                                <?php endfor; ?>
                                            </div>
                                            <div class="text-calificacion mt-1"> <!-- Reducido espacio -->
                                                <small class="text-muted" id="texto-calificacion"> Selecciona una calificación</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Comentario más compacto -->
                                    <div class="form-group mb-3"> <!-- Reducido espacio -->
                                        <label for="comentario" class="form-label small"> <!-- Texto más pequeño -->
                                            <i class="fas fa-comment me-1"></i> Tu Comentario *
                                        </label>
                                        <textarea class="form-control" id="comentario" name="comentario" 
                                                  rows="3" placeholder="Cuéntanos tu experiencia..."
                                                  required></textarea>
                                        <div class="d-flex justify-content-between mt-1"> <!-- Reducido espacio -->
                                            <small class="text-muted">Mín. 10 caracteres</small> <!-- Texto más corto -->
                                            <small class="text-muted"><span id="contador-caracteres">0</span>/500</small>
                                        </div>
                                    </div>
                                    
                                    <!-- Botón más compacto -->
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-enviar-reseña btn-sm px-4"> <!-- Reducido a btn-sm -->
                                            Publicar Reseña
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reseñas Existentes -->
        <?php if($reseñas->count() > 0): ?>
        <div class="row mt-5"> <!-- Reducido espacio -->
            <div class="col-12">
                <h4 class="text-center mb-4 text-white">Opiniones Recientes</h4> <!-- Reducido de h3 a h4 -->
                <div class="row g-3"> <!-- Reducido espacio entre cards -->
                    <?php $__currentLoopData = $reseñas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reseña): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card reseña-card h-100">
                            <div class="card-body p-3"> <!-- Reducido padding -->
                                <!-- Header de la reseña más compacto -->
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="card-title mb-0 text-white"><?php echo e($reseña->nombre); ?></h6> <!-- Reducido espacio -->
                                        <?php if($reseña->email): ?>
                                        <small class="text-muted"><?php echo e($reseña->email); ?></small>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted"><?php echo e($reseña->created_at->format('d/m/Y')); ?></small>
                                </div>
                                
                                <!-- Estrellas -->
                                <div class="estrellas mb-2"> <!-- Reducido espacio -->
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <= $reseña->calificacion): ?>
                                            <span class="text-warning">★</span>
                                        <?php else: ?>
                                            <span class="text-secondary">☆</span>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="text-muted ms-1">(<?php echo e($reseña->calificacion); ?>)</span> <!-- Reducido espacio -->
                                </div>
                                
                                <!-- Comentario -->
                                <p class="reseña-texto mb-2"><?php echo e(Str::limit($reseña->comentario, 100)); ?></p> <!-- Limitar texto -->
                                
                                <!-- Badge de verificación -->
                                <div class="text-end">
                                    <small class="badge bg-success bg-opacity-20 text-success">
                                        <i class="fas fa-check-circle me-1"></i>Verificada
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <!-- Ver más reseñas -->
                <div class="text-center mt-4"> <!-- Reducido espacio -->
                    <a href="<?php echo e(route('reseñas.index')); ?>" class="btn btn-outline-warning btn-sm"> <!-- Botón más pequeño -->
                        <i class="fas fa-list me-1"></i>Ver Todas las Reseñas
                    </a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Estado vacío más compacto -->
        <div class="row mt-5"> <!-- Reducido espacio -->
            <div class="col-12 text-center">
                <div class="card form-reseña-card">
                    <div class="card-body py-4"> <!-- Reducido padding -->
                        <i class="fas fa-comments display-6 text-muted mb-3"></i> <!-- Icono más pequeño -->
                        <h5 class="text-white mb-2">Aún no hay reseñas</h5> <!-- Texto más pequeño -->
                        <p class="text-muted mb-0">Sé el primero en compartir tu experiencia</p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Animación para las barras de progreso
    function animarBarrasProgreso() {
        const progressBars = document.querySelectorAll('.distribucion-progress-bar');
        progressBars.forEach(bar => {
            const width = bar.getAttribute('data-width');
            setTimeout(() => {
                bar.style.width = width + '%';
            }, 300);
        });
    }
    animarBarrasProgreso();

    // 2. Contador de caracteres
    const comentario = document.getElementById('comentario');
    const contador = document.getElementById('contador-caracteres');
    
    if (comentario && contador) {
        comentario.addEventListener('input', function() {
            contador.textContent = this.value.length;
            
            if (this.value.length < 10) {
                contador.classList.add('text-danger');
                contador.classList.remove('text-muted');
            } else {
                contador.classList.remove('text-danger');
                contador.classList.add('text-muted');
            }
        });
    }
    
    // 3. Texto descriptivo para calificación
    const estrellas = document.querySelectorAll('input[name="calificacion"]');
    const textoCalificacion = document.getElementById('texto-calificacion');
    
    const textosCalificacion = {
        1: 'Muy Malo - No lo recomiendo',
        2: 'Malo - Podría mejorar',
        3: 'Regular - Cumple lo básico',
        4: 'Bueno - Lo recomiendo',
        5: 'Excelente - ¡Increíble!'
    };
    
    estrellas.forEach(estrella => {
        estrella.addEventListener('change', function() {
            textoCalificacion.textContent = textosCalificacion[this.value];
            textoCalificacion.classList.remove('text-muted');
            textoCalificacion.classList.add('text-warning');
        });
    });
    
    // 4. Validación del formulario
    const form = document.getElementById('form-reseña');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const comentario = document.getElementById('comentario').value.trim();
            const calificacion = document.querySelector('input[name="calificacion"]:checked');
            
            let errores = [];
            
            if (!nombre) {
                errores.push('El nombre es obligatorio');
            }
            
            if (!calificacion) {
                errores.push('Debes seleccionar una calificación');
            }
            
            if (!comentario) {
                errores.push('El comentario es obligatorio');
            } else if (comentario.length < 10) {
                errores.push('El comentario debe tener al menos 10 caracteres');
            } else if (comentario.length > 500) {
                errores.push('El comentario no puede tener más de 500 caracteres');
            }
            
            if (errores.length > 0) {
                e.preventDefault();
                alert('Por favor corrige los siguientes errores:\n\n• ' + errores.join('\n• '));
            }
        });
    }
    
    // 5. Efecto hover en cards de reseñas
    const reseñaCards = document.querySelectorAll('.reseña-card');
    reseñaCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)'; // Reducido para nuevo diseño
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/public/secciones/_reseñas.blade.php ENDPATH**/ ?>