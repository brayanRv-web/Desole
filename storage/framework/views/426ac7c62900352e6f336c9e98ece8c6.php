<?php $__env->startSection('content'); ?>
<div class="flex flex-col space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-purple-400 flex items-center gap-3">
                <i class="fas fa-user-plus text-2xl"></i>
                Crear Nuevo Usuario
            </h2>
            <p class="text-gray-400 mt-2">Agrega un nuevo usuario al sistema</p>
        </div>
        
        <a href="<?php echo e(route('admin.usuarios.index')); ?>" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-gray-500/25 transition-all duration-200 flex items-center gap-2 font-semibold group">
            <i class="fas fa-arrow-left group-hover:scale-110 transition-transform"></i>
            Volver a Usuarios
        </a>
    </div>

    <!-- Form Section -->
    <div class="bg-gray-800/50 rounded-2xl border border-purple-700/30 shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-purple-700/40 to-purple-800/20 border-b border-purple-600/30 px-6 py-4">
            <h3 class="text-lg font-semibold text-purple-300 flex items-center gap-2">
                <i class="fas fa-user-circle"></i>
                Información del Usuario
            </h3>
        </div>

        <div class="p-6">
            <form action="<?php echo e(route('admin.usuarios.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Información Básica -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-user mr-2 text-purple-400"></i>
                            Nombre Completo *
                        </label>
                        <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>"
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Ej: Juan Pérez García"
                               required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-envelope mr-2 text-purple-400"></i>
                            Correo Electrónico *
                        </label>
                        <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>"
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Ej: juan.perez@empresa.com"
                               required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Contraseñas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-400"></i>
                            Contraseña *
                        </label>
                        <input type="password" id="password" name="password"
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Mínimo 8 caracteres"
                               required>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-400"></i>
                            Confirmar Contraseña *
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200"
                               placeholder="Repite la contraseña"
                               required>
                    </div>
                </div>

                <!-- Tipo de Usuario y Rol -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tipo de Usuario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-user-tag mr-2 text-purple-400"></i>
                            Tipo de Usuario *
                        </label>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="radio" id="panel_admin" name="user_type" value="panel_admin" 
                                       class="hidden peer" <?php echo e(old('user_type') == 'panel_admin' ? 'checked' : ''); ?>>
                                <label for="panel_admin" 
                                       class="flex items-center justify-between w-full p-4 border-2 border-gray-600 rounded-xl cursor-pointer transition-all duration-200 peer-checked:border-red-500 peer-checked:bg-red-500/10 hover:border-red-400">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-red-500/20 flex items-center justify-center">
                                            <i class="fas fa-shield-alt text-red-400"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-white">Administrador</div>
                                            <div class="text-sm text-gray-400">Acceso completo al panel administrativo</div>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 border-2 border-gray-400 rounded-full peer-checked:bg-red-500 peer-checked:border-red-500 transition-all duration-200"></div>
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="radio" id="system_user" name="user_type" value="system_user" 
                                       class="hidden peer" <?php echo e(old('user_type', 'system_user') == 'system_user' ? 'checked' : ''); ?>>
                                <label for="system_user" 
                                       class="flex items-center justify-between w-full p-4 border-2 border-gray-600 rounded-xl cursor-pointer transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-500/10 hover:border-blue-400">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center">
                                            <i class="fas fa-user-tie text-blue-400"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-white">Empleado</div>
                                            <div class="text-sm text-gray-400">Acceso limitado al sistema</div>
                                        </div>
                                    </div>
                                    <div class="w-5 h-5 border-2 border-gray-400 rounded-full peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-all duration-200"></div>
                                </label>
                            </div>
                        </div>
                        <?php $__errorArgs = ['user_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Información de Contacto (solo para empleados) -->
                    <div id="contact-fields" class="space-y-4">
                        <!-- Teléfono -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                                <i class="fas fa-phone mr-2 text-purple-400"></i>
                                Teléfono
                            </label>
                            <input type="tel" id="phone" name="phone" value="<?php echo e(old('phone')); ?>"
                                   class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200"
                                   placeholder="Ej: +1 234 567 8900">
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-300 mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-purple-400"></i>
                                Dirección
                            </label>
                            <textarea id="address" name="address" rows="2"
                                      class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200 resize-none"
                                      placeholder="Ej: Av. Principal #123, Ciudad"><?php echo e(old('address')); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div class="flex items-center justify-between p-4 bg-gray-700/30 rounded-xl border border-gray-600/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center">
                            <i class="fas fa-power-off text-green-400"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-white">Estado del Usuario</div>
                            <div class="text-sm text-gray-400">Activar o desactivar el acceso del usuario</div>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-12 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-6 peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                        <span class="ml-3 text-sm font-medium text-gray-300">Activo</span>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-700/50">
                    <button type="submit" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-purple-500/25 transition-all duration-200 flex items-center justify-center gap-2 font-semibold group flex-1">
                        <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                        Crear Usuario
                    </button>
                    <a href="<?php echo e(route('admin.usuarios.index')); ?>" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-gray-500/25 transition-all duration-200 flex items-center justify-center gap-2 font-semibold">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
    const contactFields = document.getElementById('contact-fields');

    function toggleFields() {
        const selectedType = document.querySelector('input[name="user_type"]:checked').value;
        
        if (selectedType === 'panel_admin') {
            // Ocultar campos de contacto para administradores
            contactFields.style.opacity = '0.5';
            contactFields.style.pointerEvents = 'none';
            
            // Limpiar campos de contacto
            document.getElementById('phone').value = '';
            document.getElementById('address').value = '';
        } else {
            // Mostrar campos de contacto para empleados
            contactFields.style.opacity = '1';
            contactFields.style.pointerEvents = 'auto';
        }
    }

    // Escuchar cambios en el tipo de usuario
    userTypeRadios.forEach(radio => {
        radio.addEventListener('change', toggleFields);
    });

    // Ejecutar al cargar la página
    toggleFields();
});
</script>

<style>
/* Estilos para los radio buttons personalizados */
input[type="radio"]:checked + label {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}

input[type="radio"]:checked + label#panel_admin {
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

input[type="radio"]:checked + label#system_user {
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Desole\resources\views/admin/usuarios/create.blade.php ENDPATH**/ ?>