<?php $__env->startSection('content'); ?>
<?php
    $roleColors = [
        'admin' => 'bg-purple-500/20 text-purple-400 border-purple-500',
        'employee' => 'bg-blue-500/20 text-blue-400 border-blue-500',
        'panel_admin' => 'bg-red-500/20 text-red-400 border-red-500',
    ];

    $roleIcons = [
        'admin' => 'fas fa-crown',
        'employee' => 'fas fa-user-tie',
        'panel_admin' => 'fas fa-shield-alt',
    ];

    $statusColors = [
        true => 'bg-green-500/20 text-green-400 border-green-500',
        false => 'bg-gray-500/20 text-gray-400 border-gray-500',
    ];

    $statusIcons = [
        true => 'fas fa-check-circle',
        false => 'fas fa-pause-circle',
    ];
?>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-gray-900 rounded-2xl p-6 w-96 max-w-full shadow-lg">
        <h3 class="text-lg font-semibold text-white mb-4">Confirmar Eliminación</h3>
        <p class="text-gray-300 mb-6">¿Está seguro de que desea eliminar permanentemente este usuario? Esta acción no se puede deshacer.</p>
        <div class="flex justify-end gap-3">
            <button id="cancelDelete" class="px-4 py-2 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition">Cancelar</button>
            <button id="confirmDelete" class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white transition">Eliminar</button>
        </div>
    </div>
</div>


<div class="flex flex-col space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-purple-400 flex items-center gap-3">
                <i class="fas fa-users text-2xl"></i>
                Gestión Completa de Usuarios
            </h2>
            <p class="text-gray-400 mt-2">Administra todos los usuarios del sistema y administradores del panel</p>
        </div>
        
        <a href="<?php echo e(route('admin.usuarios.create')); ?>" 
           class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-purple-500/25 transition-all duration-200 flex items-center gap-2 font-semibold group">
            <i class="fas fa-user-plus group-hover:scale-110 transition-transform"></i>
            Nuevo Usuario
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-500/10 border border-green-500/50 text-green-400 px-6 py-4 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle text-green-400"></i>
            <span class="font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-400"></i>
            <span class="font-medium"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <!-- Users Counter -->
    <div class="bg-gray-800/30 border border-purple-700/20 rounded-xl p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Users -->
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-purple-600/20 flex items-center justify-center">
                    <i class="fas fa-users text-purple-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Total Usuarios</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($allUsers->count()); ?></p>
                </div>
            </div>

            <!-- Active Users -->
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-green-600/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Usuarios Activos</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($allUsers->where('is_active', true)->count()); ?></p>
                </div>
            </div>

            <!-- Panel Admins -->
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-red-600/20 flex items-center justify-center">
                    <i class="fas fa-shield-alt text-red-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Admins Panel</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($allUsers->where('user_type', 'panel_admin')->count()); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-gray-800/50 rounded-2xl border border-purple-700/30 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-700/40 to-purple-800/20 border-b border-purple-600/30">
                    <tr>
                        <th class="px-6 py-4 text-left text-purple-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Usuario
                        </th>
                        <th class="px-6 py-4 text-left text-purple-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </th>
                        <th class="px-6 py-4 text-left text-purple-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-user-tag mr-2"></i>Tipo
                        </th>
                        <th class="px-6 py-4 text-left text-purple-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-circle mr-2"></i>Estado
                        </th>
                        <th class="px-6 py-4 text-left text-purple-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Registro
                        </th>
                        <th class="px-6 py-4 text-center text-purple-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2"></i>Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    <?php $__empty_1 = true; $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $userType = $user instanceof App\Models\Admin ? 'panel_admin' : 'system_user';
                        $isCurrentUser = auth('admin')->check() && $user->email === auth('admin')->user()->email;
                    ?>
                    <tr class="hover:bg-purple-900/20 transition-all duration-200 group">
                        <!-- User Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-600 to-blue-600 border border-purple-400/30 flex items-center justify-center text-white font-semibold text-sm">
                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                </div>
                                <div>
                                    <div class="font-semibold text-white group-hover:text-purple-300 transition-colors">
                                        <?php echo e($user->name); ?>

                                        <?php if($isCurrentUser): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-300 border border-yellow-400/30 ml-2">
                                                <i class="fas fa-star mr-1 text-xs"></i>
                                                Tú
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($userType === 'system_user' && $user->phone): ?>
                                        <div class="text-xs text-gray-400 flex items-center gap-1">
                                            <i class="fas fa-phone"></i>
                                            <?php echo e($user->phone); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-300"><?php echo e($user->email); ?></div>
                        </td>

                        <!-- Type -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($userType === 'panel_admin' ? 'bg-red-500/20 text-red-400 border border-red-500' : 'bg-blue-500/20 text-blue-400 border border-blue-500'); ?>">
                                <i class="<?php echo e($userType === 'panel_admin' ? 'fa-shield-alt' : 'fa-user-tie'); ?> fas mr-1 text-xs"></i>
                                <?php echo e($userType === 'panel_admin' ? 'Administrador' : 'Empleado'); ?>

                            </span>
                        </td>

                        <!-- Status with Toggle -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php if($isCurrentUser): ?>
                                <!-- Estado fijo para el usuario actual -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border <?php echo e($user->is_active ? 'bg-green-500/20 text-green-400 border-green-500' : 'bg-gray-500/20 text-gray-400 border-gray-500'); ?>">
                                    <i class="<?php echo e($user->is_active ? 'fas fa-check-circle' : 'fas fa-pause-circle'); ?> mr-1 text-xs"></i>
                                    <?php echo e($user->is_active ? 'Activo' : 'Inactivo'); ?>

                                </span>
                                <div class="text-xs text-gray-500 mt-1">No puedes modificar tu estado</div>
                            <?php else: ?>
                                <!-- Toggle para otros usuarios -->
                                <button type="button" 
                                        onclick="toggleUserStatus(this)"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border transition-all duration-200 cursor-pointer <?php echo e($user->is_active ? 'bg-green-500/20 text-green-400 border-green-500 hover:bg-green-500/30' : 'bg-gray-500/20 text-gray-400 border-gray-500 hover:bg-gray-500/30'); ?>"
                                        data-user-id="<?php echo e($user->id); ?>"
                                        data-current-status="<?php echo e($user->is_active ? 'active' : 'inactive'); ?>"
                                        title="Click para cambiar estado">
                                    <i class="<?php echo e($user->is_active ? 'fas fa-check-circle' : 'fas fa-pause-circle'); ?> mr-1 text-xs"></i>
                                    <?php echo e($user->is_active ? 'Activo' : 'Inactivo'); ?>

                                </button>
                            <?php endif; ?>
                        </td>

                        <!-- Registration Date -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-400">
                                <?php echo e($user->created_at->format('d/m/Y')); ?>

                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Edit Button -->
                                <a href="<?php echo e(route('admin.usuarios.edit', $user->id)); ?>" 
                                   class="bg-blue-600/20 hover:bg-blue-600/40 border border-blue-500/50 hover:border-blue-400 text-blue-400 hover:text-blue-300 p-2 rounded-xl transition-all duration-200 group/edit tooltip"
                                   title="Editar usuario">
                                    <i class="fas fa-edit group-hover/edit:scale-110 transition-transform"></i>
                                </a>

                                <!-- Delete Button -->
                                <?php if(!$isCurrentUser): ?>
                                <form id="deleteForm-user-<?php echo e($user->id); ?>" action="<?php echo e(route('admin.usuarios.destroy', $user->id)); ?>" method="POST" class="deleteForm">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="deleteBtn bg-red-600/20 hover:bg-red-600/40 border border-red-500/50 hover:border-red-400 text-red-400 hover:text-red-300 p-2 rounded-xl transition-all duration-200" data-name="<?php echo e($user->name); ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php else: ?>
                                <button class="bg-gray-600/20 border border-gray-500/50 text-gray-500 p-2 rounded-xl cursor-not-allowed tooltip"
                                        title="No puedes eliminarte a ti mismo">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-4 text-gray-600"></i>
                                <h3 class="text-lg font-semibold text-gray-400 mb-2">No hay usuarios registrados</h3>
                                <p class="text-sm text-gray-500 mb-4">Comienza agregando el primer usuario al sistema</p>
                                <a href="<?php echo e(route('admin.usuarios.create')); ?>" 
                                   class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                                    <i class="fas fa-user-plus"></i>
                                    Crear Primer Usuario
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Simple Counter -->
        <?php if($allUsers->count() > 0): ?>
        <div class="px-6 py-4 border-t border-gray-700/50 bg-gray-800/30">
            <div class="text-center text-sm text-gray-400">
                Mostrando <span class="text-purple-400 font-semibold"><?php echo e($allUsers->count()); ?></span> usuarios en total
                (<?php echo e($allUsers->where('user_type', 'panel_admin')->count()); ?> administradores)
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <!-- Divider Between Admin Users and Clients -->
    <div class="mt-16 mb-6">
        <h2 class="text-3xl font-bold text-blue-400 flex items-center gap-3">
            <i class="fas fa-users text-2xl"></i>
            Clientes Registrados
        </h2>
        <p class="text-gray-400 mt-2">Clientes registrados en el sistema</p>
    </div>
    
            <!-- Clients Counter -->
        <div class="bg-gray-800/30 border border-blue-700/20 rounded-xl p-4 mt-10">
            <h2 class="text-xl font-semibold text-blue-300 mb-4 flex items-center gap-2">

            </h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <!-- Total Clientes -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-blue-600/20 flex items-center justify-center">
                        <i class="fas fa-user-friends text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Clientes</p>
                        <p class="text-2xl font-bold text-white"><?php echo e($clientes->count()); ?></p>
                    </div>
                </div>

                <!-- Clientes Hoy -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-green-600/20 flex items-center justify-center">
                        <i class="fas fa-calendar-day text-green-400"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Registrados Hoy</p>
                        <p class="text-2xl font-bold text-white">
                            <?php echo e($clientes->where('created_at', '>=', now()->startOfDay())->count()); ?>

                        </p>
                    </div>
                </div>

                <!-- Clientes Esta Semana -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-yellow-600/20 flex items-center justify-center">
                        <i class="fas fa-calendar-week text-yellow-400"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Esta Semana</p>
                        <p class="text-2xl font-bold text-white">
                            <?php echo e($clientes->where('created_at', '>=', now()->startOfWeek())->count()); ?>

                        </p>
                    </div>
                </div>

                <!-- Clientes Este Mes -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-purple-600/20 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-purple-400"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Este Mes</p>
                        <p class="text-2xl font-bold text-white">
                            <?php echo e($clientes->where('created_at', '>=', now()->startOfMonth())->count()); ?>

                        </p>
                    </div>
                </div>

            </div>
        </div>

    <div class="bg-gray-800/50 rounded-2xl border border-blue-700/30 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-700/40 to-blue-800/20 border-b border-blue-600/30">
                    <tr>
                        <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Nombre
                        </th>
                        <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </th>
                        <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-phone mr-2"></i>Teléfono
                        </th>
                        <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Registro
                        </th>
                        <th class="px-6 py-4 text-center text-blue-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2"></i>Acciones
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-700/50">
                    <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-blue-900/20 transition-all duration-200 group">

                        <!-- Nombre -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">

                                <!-- ICONO -->
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-cyan-600 
                                            border border-blue-400/30 flex items-center justify-center 
                                            text-white font-semibold">
                                    <?php echo e(strtoupper(substr($cliente->nombre, 0, 1))); ?>

                                </div>

                                <span class="font-semibold text-white group-hover:text-blue-300 transition-colors">
                                    <?php echo e($cliente->nombre); ?>

                                </span>
                            </div>
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                            <?php echo e($cliente->email); ?>

                        </td>

                        <!-- Teléfono -->
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                            <?php echo e($cliente->telefono ?? 'No proporcionado'); ?>

                        </td>

                        <!-- Fecha -->
                        <td class="px-6 py-4 whitespace-nowrap text-gray-400">
                            <?php echo e($cliente->created_at->format('d/m/Y')); ?>

                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">

                                <!-- Eliminar -->
                                <form id="deleteForm-client-<?php echo e($cliente->id); ?>" action="<?php echo e(route('admin.clientes.destroy', $cliente->id)); ?>" method="POST" class="deleteForm">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="deleteBtn bg-red-600/20 hover:bg-red-600/40 border border-red-500/50 hover:border-red-400 text-red-400 hover:text-red-300 p-2 rounded-xl transition-all duration-200" data-name="<?php echo e($cliente->nombre); ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4 text-gray-600"></i>
                            <h3 class="text-lg font-semibold text-gray-400 mb-2">No hay clientes registrados</h3>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-gray-700/50 bg-gray-800/30">
            <?php echo e($clientes->links()); ?>

        </div>
    </div>

</div>

<script>
function toggleUserStatus(button) {
    const userId = button.getAttribute('data-user-id');
    const currentStatus = button.getAttribute('data-current-status');
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    
    console.log('Toggle status:', { userId, currentStatus, newStatus });
    
    if (confirm(`¿Estás seguro de que deseas ${newStatus === 'active' ? 'activar' : 'desactivar'} este usuario?`)) {
        const url = "<?php echo e(route('admin.usuarios.updateStatus', ':id')); ?>".replace(':id', userId);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = url;
        
        let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) csrfToken = document.querySelector('input[name="_token"]')?.value;
        if (!csrfToken && window.Laravel?.csrfToken) csrfToken = window.Laravel.csrfToken;
        
        if (!csrfToken) {
            alert('Error: No se pudo encontrar el token de seguridad. Por favor recarga la página.');
            return;
        }
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'is_active';
        statusInput.value = newStatus === 'active' ? '1' : '0';
        
        form.appendChild(methodInput);
        form.appendChild(csrfInput);
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-dismiss alerts after 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert && alert.parentNode) {
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) alert.parentNode.removeChild(alert);
                }, 300);
            }
        }, 5000);
    });

    // ===== Modal de Confirmación de Eliminación =====
    const modal = document.getElementById('deleteModal');
    const confirmBtn = document.getElementById('confirmDelete');
    const cancelBtn = document.getElementById('cancelDelete');
    let activeForm = null;

    // Abrir modal al dar click en botón de eliminar
    document.querySelectorAll('.deleteBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            activeForm = btn.closest('form');
            modal.classList.remove('hidden');
        });
    });

    // Cancelar eliminación
    cancelBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        activeForm = null;
    });

    // Confirmar eliminación
    confirmBtn.addEventListener('click', () => {
        if (activeForm) activeForm.submit();
    });
});
</script>


<style>
.tooltip {
    position: relative;
}

.tooltip:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    margin-bottom: 5px;
}

.tooltip:hover::before {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.8);
    margin-bottom: -5px;
    z-index: 1000;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/admin/usuarios/index.blade.php ENDPATH**/ ?>