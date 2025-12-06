  

<?php $__env->startSection('title', 'Mi Perfil - DÉSOLÉ'); ?>

<?php $__env->startSection('content'); ?>
<section class="profile-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <!-- Formulario de Perfil -->
                <div class="profile-form-card">
                    <div class="form-header">
                        <h4><i class="fas fa-user-edit me-2"></i>Editar Mi Perfil</h4>
                    </div>
                    <div class="form-body">
                        <?php if(session('success')): ?>
                            <div class="success-message">
                                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('cliente.perfil.update')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Nombre Completo</label>
                                        <input type="text" class="form-control profile-input" name="nombre" value="<?php echo e($cliente->nombre); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control profile-input" value="<?php echo e($cliente->email); ?>" disabled>
                                        <small class="text-muted">El email no se puede modificar</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control profile-input" name="telefono" value="<?php echo e($cliente->telefono); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Dirección</label>
                                <input type="text" class="form-control profile-input" name="direccion" value="<?php echo e($cliente->direccion); ?>" placeholder="Calle, número, colonia...">
                            </div>
                            
                            <div class="form-actions">
                                <a href="<?php echo e(route('cliente.dashboard')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Volver al Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Actualizar Perfil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('cliente.layout.cliente', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/cliente/perfil.blade.php ENDPATH**/ ?>