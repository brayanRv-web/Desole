<?php $__env->startSection('content'); ?>
<div class="bg-red-900 p-4 mb-4 rounded">
    <h3 class="text-white font-bold">DEBUG - Variables PHP:</h3>
    <pre class="text-white"><?php echo e(print_r($estadisticas, true)); ?></pre>
</div>

<!-- 4 ACCIONES PRINCIPALES - CLARAS Y SIMPLES -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <!-- 1. CLIENTES INACTIVOS -->
    <div class="bg-gray-800 p-6 rounded-xl border border-orange-500/40">
        <div class="flex items-center gap-3 mb-3">
            <i class="fas fa-user-clock text-orange-400 text-xl"></i>
            <h3 class="font-bold text-white text-lg">Clientes Inactivos</h3>
        </div>
        <p class="text-gray-400 text-sm mb-2">No han pedido en más de 1 mes</p>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-300">Total:</span>
            <span class="text-white font-bold"><?php echo e($estadisticas['clientesInactivos']); ?> clientes</span>
        </div>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-300">Aceptan mensajes:</span>
            <span class="text-green-400 font-bold"><?php echo e($estadisticas['inactivosConWhatsApp']); ?> clientes</span>
        </div>
        <button onclick="mostrarMensaje('inactivos')" 
                class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-envelope mr-2"></i>Ver Mensaje Sugerido
        </button>
    </div>

    <!-- 2. CUMPLEAÑOS -->
    <div class="bg-gray-800 p-6 rounded-xl border border-pink-500/40">
        <div class="flex items-center gap-3 mb-3">
            <i class="fas fa-birthday-cake text-pink-400 text-xl"></i>
            <h3 class="font-bold text-white text-lg">Cumpleaños</h3>
        </div>
        <p class="text-gray-400 text-sm mb-2">Clientes que cumplen años este mes</p>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-300">Total:</span>
            <span class="text-white font-bold"><?php echo e($estadisticas['cumpleanerosMes']); ?> clientes</span>
        </div>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-300">Aceptan felicitaciones:</span>
            <span class="text-green-400 font-bold"><?php echo e($estadisticas['cumpleanerosConWhatsApp']); ?> clientes</span>
        </div>
        <button onclick="mostrarMensaje('cumpleaneros')" 
                class="w-full bg-pink-600 hover:bg-pink-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-gift mr-2"></i>Ver Felicitación
        </button>
    </div>

    <!-- 3. CLIENTES FRECUENTES -->
    <div class="bg-gray-800 p-6 rounded-xl border border-blue-500/40">
        <div class="flex items-center gap-3 mb-3">
            <i class="fas fa-star text-blue-400 text-xl"></i>
            <h3 class="font-bold text-white text-lg">Clientes Frecuentes</h3>
        </div>
        <p class="text-gray-400 text-sm mb-2">Clientes con 5+ pedidos</p>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-300">Total:</span>
            <span class="text-white font-bold"><?php echo e($estadisticas['clientesFrecuentes']); ?> clientes</span>
        </div>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-300">Aceptan mensajes:</span>
            <span class="text-green-400 font-bold"><?php echo e($estadisticas['frecuentesConWhatsApp']); ?> clientes</span>
        </div>
        <button onclick="mostrarMensaje('frecuentes')" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-heart mr-2"></i>Ver Agradecimiento
        </button>
    </div>

    <!-- 4. PROMOCIONES -->
    <div class="bg-gray-800 p-6 rounded-xl border border-green-500/40">
        <div class="flex items-center gap-3 mb-3">
            <i class="fas fa-tag text-green-400 text-xl"></i>
            <h3 class="font-bold text-white text-lg">Promociones</h3>
        </div>
        <p class="text-gray-400 text-sm mb-2">Nuevos productos u ofertas</p>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-300">Clientes totales:</span>
            <span class="text-white font-bold"><?php echo e($estadisticas['totalClientes']); ?> clientes</span>
        </div>
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-300">Aceptan promociones:</span>
            <span class="text-green-400 font-bold"><?php echo e($estadisticas['clientesRecibirPromociones']); ?> clientes</span>
        </div>
        <button onclick="mostrarMensaje('promociones')" 
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg transition">
            <i class="fas fa-bullhorn mr-2"></i>Ver Mensaje Promocional
        </button>
    </div>
</div>

<!-- EXPLICACIÓN SIMPLE -->
<div class="bg-gray-800 p-6 rounded-xl border border-yellow-500/40">
    <h3 class="text-xl font-bold text-yellow-400 mb-3">¿Cómo funciona?</h3>
    <div class="text-gray-300 space-y-2">
        <p>1. <strong>Elige un grupo</strong> de clientes (arriba)</p>
        <p>2. <strong>Revisa el mensaje</strong> que aparecerá (sugerido automáticamente)</p>
        <p>3. <strong>Copia el mensaje</strong> y pégalo en WhatsApp Web</p>
        <p>4. <strong>Envía solo</strong> a clientes que ACEPTARON recibir mensajes</p>
    </div>
    <p class="text-green-400 text-sm mt-3">
        ✅ Respetamos siempre las preferencias de cada cliente
    </p>
</div>

<script>
// Pasar datos de PHP a JS SIN errores de editor
const conteosData = {
    inactivos: <?php echo $estadisticas['inactivosConWhatsApp'] ?? 0; ?>,
    cumpleaneros: <?php echo $estadisticas['cumpleanerosConWhatsApp'] ?? 0; ?>,
    frecuentes: <?php echo $estadisticas['frecuentesConWhatsApp'] ?? 0; ?>,
    promociones: <?php echo $estadisticas['clientesRecibirPromociones'] ?? 0; ?>
};

function mostrarMensaje(tipo) {
    const mensajes = {
        'inactivos': `¡Hola! 👋 Te extrañamos en Désolé. ¿Todo bien por ahí? Tenemos promociones especiales esperándote ☕✨\n\n¿Te gustaría que te reserve tu pedido favorito?`,
        'cumpleaneros': `¡Feliz cumpleaños! 🎂🎉 De todo el equipo Désolé, te regalamos un café gratis hoy. ¡Pasa a celebrar con nosotros! 🎁☕`,
        'frecuentes': `¡Hola! 😊 Gracias por ser un cliente tan especial. Por tu lealtad, hoy tienes 15% de descuento en tu pedido. ¡Te mereces lo mejor! 💚`,
        'promociones': `¡Novedad en Désolé! 🚀 Acabamos de lanzar nuestro nuevo Latte de Avellana. ¿Te gustaría probarlo? 😋☕`
    };

    const conteo = conteosData[tipo] || 0;
    
    if (conteo === 0) {
        alert('No hay clientes en este grupo que acepten mensajes.');
        return;
    }

    alert(`MENSAJE PARA ${conteo} CLIENTES:\n\n💬 "${mensajes[tipo]}"\n\n📋 Copia este mensaje y envíalo por WhatsApp a los clientes que SÍ aceptaron notificaciones.`);
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Desole\resources\views/admin/crm/campanas.blade.php ENDPATH**/ ?>