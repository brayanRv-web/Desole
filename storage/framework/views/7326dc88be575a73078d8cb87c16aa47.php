<?php $__env->startSection('content'); ?>
<?php
    use Illuminate\Support\Str;

    // Función para normalizar días (elimina acentos y convierte a minúsculas)
    function normalizarDia($dia) {
        return strtolower(Str::ascii($dia));
    }

    $estadoColors = [
        true => 'bg-green-500/20 text-green-400 border-green-500',
        false => 'bg-gray-500/20 text-gray-400 border-gray-500',
    ];

    $estadoIcons = [
        true => 'fas fa-check-circle',
        false => 'fas fa-times-circle',
    ];

    $estadoTextos = [
        true => 'Activo',
        false => 'Inactivo'
    ];

    // Día actual (sin acentos)
    $diaHoy = normalizarDia(now()->locale('es')->isoFormat('dddd'));
    $horarioHoy = $horarios->firstWhere('dia_semana', $diaHoy);
    $estaAbierto = $horarioHoy && $horarioHoy->estaAbierto();
?>

<div class="flex flex-col space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-blue-400 flex items-center gap-3">
                <i class="fas fa-clock text-2xl"></i>
                Gestión de Horarios
            </h2>
            <p class="text-gray-400 mt-2">Administra los horarios de atención de la cafetería</p>
        </div>
        
        <div class="flex gap-3">
            <button type="submit" form="horariosForm"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-blue-500/25 transition-all duration-200 flex items-center gap-2 font-semibold group">
                <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                Guardar Cambios
            </button>
        </div>
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

    <!-- Info Panel -->
    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-400 mt-1"></i>
            <div class="flex-1">
                <h4 class="font-semibold text-blue-300 mb-2">Configuración de Horarios</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-200/80">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                        <span><strong>Horarios activos:</strong> Se mostrarán a los clientes</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                        <span><strong>Horarios inactivos:</strong> No estarán disponibles</span>
                    </div>
                </div>
                <div class="mt-3 text-xs text-blue-300">
                    <i class="fas fa-lightbulb mr-1"></i>
                    <strong>Tip:</strong> Puedes editar individualmente cada horario o usar el formulario masivo abajo
                </div>
            </div>
        </div>
    </div>

    <!-- Current Status -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-800/50 rounded-xl p-4 border border-blue-500/30">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-blue-600/20 flex items-center justify-center">
                    <i class="fas fa-calendar-day text-blue-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Días Activos</p>
                    <p class="text-2xl font-bold text-white"><?php echo e($horarios->where('activo', true)->count()); ?>/7</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800/50 rounded-xl p-4 border border-green-500/30">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-green-600/20 flex items-center justify-center">
                    <i class="fas fa-store text-green-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Estado Actual</p>
                    <p class="text-2xl font-bold <?php echo e($estaAbierto ? 'text-green-400' : 'text-red-400'); ?>">
                        <?php echo e($estaAbierto ? 'Abierto' : 'Cerrado'); ?>

                    </p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800/50 rounded-xl p-4 border border-purple-500/30">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-purple-600/20 flex items-center justify-center">
                    <i class="fas fa-clock text-purple-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Hoy</p>
                    <p class="text-lg font-bold text-white">
                        <?php echo e($horarioHoy ? $horarioHoy->horario_formateado : 'Cerrado'); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Horarios Table -->
    <form id="horariosForm" action="<?php echo e(route('admin.horarios.update-multiple')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="bg-gray-800/50 rounded-2xl border border-blue-700/30 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-700/40 to-blue-800/20 border-b border-blue-600/30">
                        <tr>
                            <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>Día
                            </th>
                            <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-door-open mr-2"></i>Apertura
                            </th>
                            <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-door-closed mr-2"></i>Cierre
                            </th>
                            <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-clock mr-2"></i>Horario
                            </th>
                            <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-toggle-on mr-2"></i>Estado
                            </th>

                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        <?php $__currentLoopData = $horarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $horario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $esHoy = $horario->dia_semana === $diaHoy;
                            $estaAbierto = $horario->estaAbierto();
                        ?>
                        <tr class="hover:bg-blue-900/20 transition-all duration-200 group <?php echo e($esHoy ? 'bg-blue-900/10 border-l-4 border-blue-500' : ''); ?>">
                            <!-- Día -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-600/20 border border-blue-600/30 flex items-center justify-center">
                                        <i class="fas fa-calendar-day text-blue-400 text-sm"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-white group-hover:text-blue-300 transition-colors">
                                            <?php echo e($horario->dia_semana_completo); ?>

                                        </div>
                                        <?php if($esHoy): ?>
                                            <div class="text-xs text-blue-400 mt-1 flex items-center gap-1">
                                                <i class="fas fa-star text-yellow-400"></i>
                                                <span>Hoy</span>
                                                <?php if($estaAbierto): ?>
                                                    <span class="text-green-400 ml-2">• Abierto ahora</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Apertura -->
                            <td class="px-6 py-4">
                                <input type="time" 
                                       name="horarios[<?php echo e($horario->id); ?>][apertura]" 
                                       value="<?php echo e($horario->apertura ? date('H:i', strtotime($horario->apertura)) : ''); ?>"
                                       class="bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                            </td>

                            <!-- Cierre -->
                            <td class="px-6 py-4">
                                <input type="time" 
                                       name="horarios[<?php echo e($horario->id); ?>][cierre]" 
                                       value="<?php echo e($horario->cierre ? date('H:i', strtotime($horario->cierre)) : ''); ?>"
                                       class="bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                            </td>

                            <!-- Horario Formateado -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-white font-medium">
                                    <?php echo e($horario->horario_formateado); ?>

                                </div>
                            </td>

                            <!-- Estado -->
                            <td class="px-6 py-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="horarios[<?php echo e($horario->id); ?>][activo]" 
                                           value="1" 
                                           <?php echo e($horario->activo ? 'checked' : ''); ?>

                                           class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-700 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:h-5 after:w-5 after:rounded-full after:transition-all peer-checked:after:translate-x-full"></div>
                                    <span class="ml-3 text-sm text-gray-300"><?php echo e($horario->activo ? 'Activo' : 'Inactivo'); ?></span>
                                </label>
                            </td>

                            <!-- Acciones -->

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/admin/horarios/index.blade.php ENDPATH**/ ?>