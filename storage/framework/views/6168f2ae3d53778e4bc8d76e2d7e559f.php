<?php $__env->startSection('content'); ?>
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-purple-400 flex items-center gap-2">
                <i class="fas fa-gift"></i> Programa de Fidelidad
            </h2>
            <p class="text-gray-400 mt-1">Gestiona puntos y recompensas para clientes</p>
        </div>
        <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-cog"></i> Configurar
        </button>
    </div>
</div>

<!-- Estadísticas de Fidelidad -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-800 p-4 rounded border border-purple-500/40">
        <p class="text-gray-400 text-sm">Puntos Totales</p>
        <p class="text-xl font-bold text-white"><?php echo e($clientes->sum('puntos_fidelidad')); ?></p>
    </div>
    <div class="bg-gray-800 p-4 rounded border border-yellow-500/40">
        <p class="text-gray-400 text-sm">Clientes con Puntos</p>
        <p class="text-xl font-bold text-white"><?php echo e($clientes->where('puntos_fidelidad', '>', 0)->count()); ?></p>
    </div>
    <div class="bg-gray-800 p-4 rounded border border-green-500/40">
        <p class="text-gray-400 text-sm">Canjes Pendientes</p>
        <p class="text-xl font-bold text-white"><?php echo e($clientes->where('puntos_fidelidad', '>=', 100)->count()); ?></p>
    </div>
    <div class="bg-gray-800 p-4 rounded border border-blue-500/40">
        <p class="text-gray-400 text-sm">Nivel Oro</p>
        <p class="text-xl font-bold text-white"><?php echo e($clientes->where('nivel_fidelidad', 3)->count()); ?></p>
    </div>
</div>

<!-- Sistema de Niveles -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Nivel Bronce -->
    <div class="bg-gray-800 p-6 rounded-xl border border-yellow-800/40">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-yellow-800/20 flex items-center justify-center">
                <i class="fas fa-medal text-yellow-600"></i>
            </div>
            <div>
                <h3 class="font-bold text-white text-lg">Bronce</h3>
                <p class="text-gray-400 text-sm">0-99 puntos</p>
            </div>
        </div>
        <ul class="text-gray-300 text-sm space-y-2 mb-4">
            <li class="flex items-center gap-2">
                <i class="fas fa-check text-green-400"></i>
                1 punto por cada $10 gastado
            </li>
            <li class="flex items-center gap-2">
                <i class="fas fa-check text-green-400"></i>
                Promociones básicas
            </li>
        </ul>
        <div class="text-center">
            <span class="text-yellow-400 font-bold"><?php echo e($clientes->where('nivel_fidelidad', 1)->count()); ?> clientes</span>
        </div>
    </div>

    <!-- Nivel Plata -->
    <div class="bg-gray-800 p-6 rounded-xl border border-gray-400/40">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-gray-400/20 flex items-center justify-center">
                <i class="fas fa-medal text-gray-300"></i>
            </div>
            <div>
                <h3 class="font-bold text-white text-lg">Plata</h3>
                <p class="text-gray-400 text-sm">100-299 puntos</p>
            </div>
        </div>
        <ul class="text-gray-300 text-sm space-y-2 mb-4">
            <li class="flex items-center gap-2">
                <i class="fas fa-check text-green-400"></i>
                1.5 puntos por cada $10 gastado
            </li>
            <li class="flex items-center gap-2">
                <i class="fas fa-check text-green-400"></i>
                Descuento 5% en pedidos
            </li>
            <li class="flex items-center gap-2">
                <i class="fas fa-check text-green-400"></i>
                Promociones exclusivas
            </li>
        </ul>
        <div class="text-center">
            <span class="text-gray-300 font-bold"><?php echo e($clientes->where('nivel_fidelidad', 2)->count()); ?> clientes</span>
        </div>
    </div>

    <!-- Nivel Oro -->
    <div class="bg-gray-800 p-6 rounded-xl border border-yellow-500/40">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-yellow-500/20 flex items-center justify-center">
                <i class="fas fa-crown text-yellow-400"></i>
            </div>
            <div>
                <h3 class="font-bold text-white text-lg">Oro</h3>
                <p class="text-gray-400 text-sm">300+ puntos</p>
            </div>
        </div>
        <ul class="text-gray-300 text-sm space-y-2 mb-4">
            <li class="flex items-center gap-2">
                <i class="fas fa-check text-green-400"></i>
                2 puntos por cada $10 gastado
            </li>
            <li class="flex items-center gap-2">
                <i class="fas fa-check text-green-400"></i>
                Descuento 10% en pedidos
            </li>
            <li class="flex items-center gap-2">
                <i class="fas fa-check text-green-400"></i>
                Café gratis cada 10 pedidos
            </li>
            <li class="flex items-center gap-2">
                <i class="fas fa-check text-green-400"></i>
                Acceso prioritario
            </li>
        </ul>
        <div class="text-center">
            <span class="text-yellow-400 font-bold"><?php echo e($clientes->where('nivel_fidelidad', 3)->count()); ?> clientes</span>
        </div>
    </div>
</div>

<!-- Tabla de Clientes con Puntos -->
<div class="bg-gray-800 rounded-xl border border-purple-600/40 overflow-hidden">
    <div class="p-6 border-b border-gray-700">
        <h3 class="text-xl font-bold text-purple-400">Clientes en el Programa</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-750">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nivel</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Puntos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Pedidos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-750 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-purple-600/20 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-purple-400"></i>
                            </div>
                            <div class="text-sm font-medium text-white"><?php echo e($cliente->nombre); ?></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($cliente->nivel_fidelidad == 3): ?>
                            <span class="bg-yellow-500/20 text-yellow-400 px-2 py-1 rounded-full text-xs">Oro</span>
                        <?php elseif($cliente->nivel_fidelidad == 2): ?>
                            <span class="bg-gray-400/20 text-gray-300 px-2 py-1 rounded-full text-xs">Plata</span>
                        <?php else: ?>
                            <span class="bg-yellow-800/20 text-yellow-600 px-2 py-1 rounded-full text-xs">Bronce</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-white"><?php echo e($cliente->puntos_fidelidad); ?> pts</div>
                        <div class="text-xs text-gray-400">
                            <?php if($cliente->puntos_fidelidad >= 100): ?>
                                <span class="text-green-400">Listo para canjear</span>
                            <?php else: ?>
                                <?php echo e(100 - $cliente->puntos_fidelidad); ?> pts para canje
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-white"><?php echo e($cliente->total_pedidos); ?> pedidos</div>
                        <div class="text-xs text-gray-400">$<?php echo e(number_format($cliente->total_gastado, 2)); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-xs transition">
                            <i class="fas fa-plus mr-1"></i>Puntos
                        </button>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs ml-2 transition">
                            <i class="fas fa-gift mr-1"></i>Canjear
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center">
                        <i class="fas fa-gift text-4xl text-gray-600 mb-3"></i>
                        <p class="text-gray-400">No hay clientes en el programa de fidelidad</p>
                        <p class="text-gray-500 text-sm mt-2">Los clientes ganarán puntos automáticamente con sus pedidos</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <?php if($clientes->hasPages()): ?>
    <div class="bg-gray-750 px-6 py-4 border-t border-gray-700">
        <?php echo e($clientes->links()); ?>

    </div>
    <?php endif; ?>
</div>

<!-- Recompensas Disponibles -->
<div class="bg-gray-800 p-6 rounded-xl border border-green-600/40 mt-6">
    <h3 class="text-xl font-bold text-green-400 mb-4">Recompensas Disponibles</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-gray-750 p-4 rounded border border-green-500/40">
            <div class="text-center">
                <div class="text-2xl font-bold text-green-400 mb-2">100 pts</div>
                <p class="text-white text-sm">Café Gratis</p>
                <p class="text-gray-400 text-xs">Cualquier bebida del menú</p>
            </div>
        </div>
        
        <div class="bg-gray-750 p-4 rounded border border-blue-500/40">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-400 mb-2">200 pts</div>
                <p class="text-white text-sm">Postre Gratis</p>
                <p class="text-gray-400 text-xs">+ Café incluido</p>
            </div>
        </div>
        
        <div class="bg-gray-750 p-4 rounded border border-purple-500/40">
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-400 mb-2">300 pts</div>
                <p class="text-white text-sm">Combo Especial</p>
                <p class="text-gray-400 text-xs">Café + Postre + Promoción</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Desole\resources\views/admin/crm/fidelidad.blade.php ENDPATH**/ ?>