<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Login | Désolé</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="<?php echo e(asset('assets/favicon.ico')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
    
</head>
<body class="flex items-center justify-center min-h-screen" style="background-color: var(--color-bg);">

    <div class="w-full max-w-md login-container">
        <form action="<?php echo e(route('admin.authenticate')); ?>" method="POST" 
              class="p-8 rounded-2xl shadow-2xl border" 
              style="background-color: var(--color-bg-alt); border-color: rgba(101, 207, 114, 0.3);">
            <?php echo csrf_field(); ?>
            
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold mb-2 flex items-center justify-center gap-2" style="color: var(--color-primary);">
                    <i class="fas fa-user-shield"></i>
                    Désolé
                </h2>
                <p style="color: var(--color-muted);">Sistema de administración</p>
            </div>

            <!-- Mensajes de éxito -->
            <?php if(session('success')): ?>
                <div class="px-4 py-3 rounded-lg mb-4 flex items-center gap-2" 
                     style="background: rgba(101, 207, 114, 0.1); border: 1px solid rgba(101, 207, 114, 0.3); color: var(--color-primary);">
                    <i class="fas fa-check-circle"></i>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <!-- Mensajes de error -->
            <?php if($errors->any()): ?>
                <div class="px-4 py-3 rounded-lg mb-4" 
                     style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p class="flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo e($error); ?>

                        </p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <!-- Campos del formulario -->
            <div class="mb-4">
                <label class="block font-medium mb-2" for="email" style="color: var(--color-text);">
                    <i class="fas fa-envelope mr-2" style="color: var(--color-primary);"></i>
                    Email
                </label>
                <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" 
                       placeholder="admin@desole.com" 
                       class="w-full px-4 py-3 rounded-xl transition-all duration-200 login-input"
                       required autofocus>
            </div>

            <div class="mb-6">
                <label class="block font-medium mb-2" for="password" style="color: var(--color-text);">
                    <i class="fas fa-lock mr-2" style="color: var(--color-primary);"></i>
                    Contraseña
                </label>
                <div class="password-container">
                    <input id="password" type="password" name="password" 
                           placeholder="••••••••" 
                           class="w-full px-4 py-3 pr-10 rounded-xl transition-all duration-200 login-input"
                           required>
                    <button type="button" class="password-toggle" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" 
                    class="w-full py-3 text-white rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 group mb-4 btn-hover-effect"
                    style="background-color: var(--color-primary); color: var(--color-bg);">
                <i class="fas fa-sign-in-alt group-hover:scale-110 transition-transform"></i>
                Ingresar al Sistema
            </button>

            <!-- Opciones adicionales -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm" style="color: var(--color-muted);">
                    <input type="checkbox" name="remember" class="mr-2 rounded" 
                           style="background-color: #1a1a1a; border-color: #333; accent-color: var(--color-primary);">
                    Recuérdame
                </label>
                <a href="#" class="text-sm transition-colors" style="color: var(--color-primary);">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <!-- Footer -->
            <div class="pt-4 border-t" style="border-color: rgba(255, 255, 255, 0.1);">
                <p class="text-sm text-center" style="color: var(--color-muted);">
                    <i class="fas fa-info-circle mr-1" style="color: var(--color-primary);"></i>
                    Acceso restringido al personal autorizado
                </p>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const icon = togglePassword.querySelector('i');

            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle the icon
                if (type === 'password') {
                    icon.className = 'fas fa-eye';
                } else {
                    icon.className = 'fas fa-eye-slash';
                }
            });
        });
    </script>

</body>
</html><?php /**PATH C:\xampp\htdocs\Desole\resources\views/admin/login.blade.php ENDPATH**/ ?>