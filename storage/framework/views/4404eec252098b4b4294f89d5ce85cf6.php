<?php $__env->startSection('content'); ?>
<div class="flex flex-col space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-green-400 flex items-center gap-3">
                <i class="fas fa-plus-circle text-2xl"></i>
                Crear Nueva Promoción
            </h2>
            <p class="text-gray-400 mt-2">Configura una nueva promoción con productos activos</p>
        </div>
        
        <a href="<?php echo e(route('admin.promociones.index')); ?>" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl shadow-lg transition-all duration-200 flex items-center gap-2 font-semibold group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Volver a Promociones
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if($errors->any()): ?>
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-exclamation-circle text-red-400"></i>
                <span class="font-medium">Por favor corrige los siguientes errores:</span>
            </div>
            <ul class="list-disc list-inside text-sm">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Create Form -->
    <div class="bg-gray-800/50 rounded-2xl border border-green-700/30 shadow-xl p-6">
        <form action="<?php echo e(route('admin.promociones.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Promotion Name -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-tag mr-2 text-green-400"></i>
                            Nombre de la Promoción *
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="<?php echo e(old('nombre')); ?>"
                               class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all duration-200"
                               placeholder="Ej: Descuento de Verano 2025"
                               required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-align-left mr-2 text-green-400"></i>
                            Descripción
                        </label>
                        <textarea 
                            id="descripcion" 
                            name="descripcion" 
                            rows="3"
                            class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all duration-200"
                            placeholder="Describe los detalles de la promoción, condiciones especiales, etc..."><?php echo e(old('descripcion')); ?></textarea>
                    </div>

                    <!-- Discount Section - MEJORADO -->
                    <div class="bg-gray-700/30 border border-gray-600/50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-percentage"></i>
                            Configuración del Descuento
                        </h3>
                        
                        <!-- Discount Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-3">
                                <i class="fas fa-cogs mr-2 text-green-400"></i>
                                Tipo de Descuento *
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative">
                                    <input type="radio" 
                                           name="tipo_descuento" 
                                           value="porcentaje" 
                                           <?php echo e(old('tipo_descuento') == 'porcentaje' ? 'checked' : ''); ?>

                                           class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-600 rounded-xl cursor-pointer transition-all duration-200
                                                peer-checked:border-green-500 peer-checked:bg-green-500/10
                                                hover:border-gray-500">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-500 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-white">Porcentaje</div>
                                                <div class="text-xs text-gray-400">Ej: 20% de descuento</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative">
                                    <input type="radio" 
                                           name="tipo_descuento" 
                                           value="monto_fijo" 
                                           <?php echo e(old('tipo_descuento') == 'monto_fijo' ? 'checked' : ''); ?>

                                           class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-600 rounded-xl cursor-pointer transition-all duration-200
                                                peer-checked:border-green-500 peer-checked:bg-green-500/10
                                                hover:border-gray-500">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-500 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-white">Monto Fijo</div>
                                                <div class="text-xs text-gray-400">Ej: $5.00 de descuento</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Discount Value -->
                        <div>
                            <label for="valor_descuento" class="block text-sm font-medium text-gray-300 mb-2">
                                <i class="fas fa-dollar-sign mr-2 text-green-400"></i>
                                Valor del Descuento *
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       step="0.01" 
                                       min="0"
                                       id="valor_descuento" 
                                       name="valor_descuento" 
                                       value="<?php echo e(old('valor_descuento')); ?>"
                                       class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all duration-200 pr-20"
                                       placeholder="0.00"
                                       required>
                                <div id="discount-suffix" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 font-medium">
                                    %
                                </div>
                            </div>
                            <p id="discount-example" class="text-xs text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                <span id="example-text">Ejemplo: 20% = $80.00 de $100.00</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Dates Section -->
                    <div class="bg-gray-700/30 border border-gray-600/50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-calendar-alt"></i>
                            Período de Vigencia
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-300 mb-2">
                                    <i class="fas fa-play-circle mr-2 text-green-400"></i>
                                    Fecha de Inicio *
                                </label>
                                <input type="datetime-local" 
                                       id="fecha_inicio" 
                                       name="fecha_inicio" 
                                       value="<?php echo e(old('fecha_inicio')); ?>"
                                       class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all duration-200"
                                       required>
                            </div>

                            <div>
                                <label for="fecha_fin" class="block text-sm font-medium text-gray-300 mb-2">
                                    <i class="fas fa-stop-circle mr-2 text-green-400"></i>
                                    Fecha de Fin *
                                </label>
                                <input type="datetime-local" 
                                       id="fecha_fin" 
                                       name="fecha_fin" 
                                       value="<?php echo e(old('fecha_fin')); ?>"
                                       class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all duration-200"
                                       required>
                            </div>
                        </div>

                        <!-- ✅ BOTONES DE PRUEBA RÁPIDA PARA 2025 -->
                        <div class="mt-4 flex gap-2 flex-wrap">
                            <button type="button" id="test2min" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition-all duration-200 font-semibold flex items-center gap-2">
                                <i class="fas fa-bolt"></i> Prueba 2 Minutos
                            </button>
                            <button type="button" id="test5min" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-all duration-200 font-semibold flex items-center gap-2">
                                <i class="fas fa-clock"></i> Prueba 5 Minutos
                            </button>
                            <button type="button" id="test1hour" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-all duration-200 font-semibold flex items-center gap-2">
                                <i class="fas fa-hourglass"></i> Prueba 1 Hora
                            </button>
                        </div>

                        <div class="mt-3 p-3 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                            <div class="flex items-center gap-2 text-sm text-blue-300">
                                <i class="fas fa-info-circle"></i>
                                <span>Todas las fechas se configuran automáticamente para el año <strong>2025</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Products Selection -->
                    <div class="bg-gray-700/30 border border-gray-600/50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-boxes"></i>
                            Productos Incluidos
                        </h3>
                        
                        <?php if($productos->count() > 0): ?>
                        <div class="max-h-60 overflow-y-auto">
                            <div class="space-y-2">
                                <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-600/30 transition-colors cursor-pointer border border-transparent hover:border-gray-500/50">
                                    <input type="checkbox" 
                                           name="productos[]" 
                                           value="<?php echo e($producto->id); ?>"
                                           data-price="<?php echo e($producto->precio); ?>"
                                           <?php echo e(in_array($producto->id, old('productos', [])) ? 'checked' : ''); ?>

                                           class="rounded border-gray-600 bg-gray-700 text-green-500 focus:ring-green-500 focus:ring-2 transform scale-110">
                                    <div class="flex-1">
                                        <div class="text-white font-medium flex items-center gap-2">
                                            <?php echo e($producto->nombre); ?>

                                            <?php if($producto->imagen): ?>
                                                <img src="<?php echo e(asset('storage/' . $producto->imagen)); ?>" 
                                                     alt="<?php echo e($producto->nombre); ?>"
                                                     class="w-6 h-6 rounded object-cover">
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-sm text-gray-400 flex items-center gap-4 mt-1">
                                            <span><i class="fas fa-dollar-sign text-xs"></i> <?php echo e(number_format($producto->precio, 2)); ?></span>
                                            <span class="text-xs px-2 py-1 rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                                                Activo
                                            </span>
                                        </div>
                                    </div>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i>
                            Selecciona uno o más productos activos para la promoción
                        </p>
                        <?php else: ?>
                        <div class="text-center py-6">
                            <i class="fas fa-box-open text-4xl text-gray-500 mb-3"></i>
                            <p class="text-gray-400 mb-3">No hay productos activos disponibles</p>
                            <a href="<?php echo e(route('admin.productos.create')); ?>" 
                               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors inline-flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                                Crear Producto
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-700/50">
                <a href="<?php echo e(route('admin.promociones.index')); ?>" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-xl transition-all duration-200 font-semibold flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-green-500/25 transition-all duration-200 font-semibold flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    Crear Promoción
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    const discountSuffix = document.getElementById('discount-suffix');
    const exampleText = document.getElementById('example-text');
    const valorDescuento = document.getElementById('valor_descuento');
    
    // ✅ CORREGIDO: Obtener fecha/hora actual en formato correcto para datetime-local (AÑO 2025)
    function getCurrentDateTime() {
        const now = new Date();
        // ✅ FORZAR AÑO 2025 para pruebas
        now.setFullYear(2025);
        const timezoneOffset = now.getTimezoneOffset() * 60000;
        const localTime = new Date(now.getTime() - timezoneOffset);
        return localTime.toISOString().slice(0, 16);
    }
    
    // ✅ CORREGIDO: Establecer fecha/hora mínima como la actual (AÑO 2025)
    const currentDateTime = getCurrentDateTime();
    fechaInicio.min = currentDateTime;
    
    // Si no hay valor previo, establecer la fecha/hora actual como valor por defecto (AÑO 2025)
    if (!fechaInicio.value) {
        fechaInicio.value = currentDateTime;
    }
    
    // Establecer fecha fin mínima igual a fecha inicio (AÑO 2025)
    if (!fechaFin.value) {
        const minFinDate = new Date(fechaInicio.value);
        minFinDate.setMinutes(minFinDate.getMinutes() + 1); // ✅ REDUCIDO A 1 MINUTO MÍNIMO
        fechaFin.min = new Date(minFinDate.getTime() - (minFinDate.getTimezoneOffset() * 60000))
                      .toISOString().slice(0, 16);
        fechaFin.value = new Date(minFinDate.getTime() + (2 * 60 * 1000) - (minFinDate.getTimezoneOffset() * 60000))
                        .toISOString().slice(0, 16); // ✅ Por defecto: 2 minutos después
    }
    
    // ✅ CORREGIDO: Actualizar fecha fin cuando cambia fecha inicio (AÑO 2025)
    fechaInicio.addEventListener('change', function() {
        if (this.value) {
            const minFinDate = new Date(this.value);
            minFinDate.setMinutes(minFinDate.getMinutes() + 1); // ✅ REDUCIDO A 1 MINUTO MÍNIMO
            
            fechaFin.min = new Date(minFinDate.getTime() - (minFinDate.getTimezoneOffset() * 60000))
                          .toISOString().slice(0, 16);
            
            // Si la fecha fin actual es anterior a la nueva mínima, actualizarla
            if (fechaFin.value && new Date(fechaFin.value) < minFinDate) {
                fechaFin.value = new Date(minFinDate.getTime() + (2 * 60 * 1000) - (minFinDate.getTimezoneOffset() * 60000))
                                .toISOString().slice(0, 16);
            }
        }
    });

    // ✅ CORREGIDO: Validación adicional para fecha fin (AÑO 2025)
    fechaFin.addEventListener('change', function() {
        if (fechaInicio.value && this.value) {
            const inicio = new Date(fechaInicio.value);
            const fin = new Date(this.value);
            
            if (fin <= inicio) {
                // Si la fecha fin es anterior o igual a la inicio, mostrar error y ajustar
                const minFinDate = new Date(inicio.getTime() + (1 * 60 * 1000)); // ✅ 1 MINUTO MÍNIMO
                this.value = new Date(minFinDate.getTime() - (minFinDate.getTimezoneOffset() * 60000))
                            .toISOString().slice(0, 16);
                
                // Mostrar alerta temporal
                showTempAlert('La fecha de fin debe ser al menos 1 minuto después de la fecha de inicio', 'warning');
            }
        }
    });

    // ✅ FUNCIONES DE PRUEBA RÁPIDA PARA AÑO 2025
    function setTestDates(minutes) {
        const now = new Date();
        // ✅ FORZAR AÑO 2025
        now.setFullYear(2025);
        const timezoneOffset = now.getTimezoneOffset() * 60000;
        
        const startTime = new Date(now.getTime() - timezoneOffset).toISOString().slice(0, 16);
        const endTime = new Date(now.getTime() + (minutes * 60 * 1000) - timezoneOffset).toISOString().slice(0, 16);
        
        fechaInicio.value = startTime;
        fechaFin.value = endTime;
        
        showTempAlert(`✅ Fechas configuradas para prueba de ${minutes} minuto${minutes > 1 ? 's' : ''} (Año 2025)`, 'info');
    }
    
    // Event listeners para botones de prueba
    document.getElementById('test2min').addEventListener('click', () => setTestDates(2));
    document.getElementById('test5min').addEventListener('click', () => setTestDates(5));
    document.getElementById('test1hour').addEventListener('click', () => setTestDates(60));

    // Función para mostrar alertas temporales
    function showTempAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-xl shadow-lg transition-all duration-300 ${
            type === 'warning' ? 'bg-yellow-500/20 border border-yellow-500/50 text-yellow-400' : 
            type === 'error' ? 'bg-red-500/20 border border-red-500/50 text-red-400' :
            'bg-green-500/20 border border-green-500/50 text-green-400'
        }`;
        alertDiv.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas fa-${type === 'warning' ? 'exclamation-triangle' : type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            alertDiv.style.transform = 'translateX(100%)';
            setTimeout(() => alertDiv.remove(), 300);
        }, 4000);
    }

    // Update discount suffix and example based on discount type
    function updateDiscountDisplay() {
        const selectedType = document.querySelector('input[name="tipo_descuento"]:checked');
        let value = parseFloat(valorDescuento.value) || 0;
        
        if (selectedType) {
            if (selectedType.value === 'porcentaje') {
                discountSuffix.textContent = '%';
                
                // Enforce max 100 for percentage
                if (value > 100) {
                    value = 100;
                    valorDescuento.value = 100;
                    showTempAlert('El porcentaje no puede ser mayor a 100%', 'warning');
                }
                
                // Calculate total price of selected products
                let totalSelectedPrice = 0;
                const checkedProducts = document.querySelectorAll('input[name="productos[]"]:checked');
                checkedProducts.forEach(cb => {
                    totalSelectedPrice += parseFloat(cb.getAttribute('data-price')) || 0;
                });
                
                // Use 100 as default if no products selected for example purposes
                const basePrice = totalSelectedPrice > 0 ? totalSelectedPrice : 100;
                const basePriceText = totalSelectedPrice > 0 ? 'Total seleccionados' : 'Ejemplo base';

                const discountAmount = (basePrice * value / 100);
                const finalPrice = basePrice - discountAmount;
                exampleText.innerHTML = `<strong>${basePriceText}: $${basePrice.toFixed(2)}</strong> <br> Descuento: $${discountAmount.toFixed(2)} <br> <strong>Final: $${finalPrice.toFixed(2)}</strong>`;
            } else {
                discountSuffix.textContent = '$';
                
                // Validate fixed amount against min product price
                const checkedProducts = document.querySelectorAll('input[name="productos[]"]:checked');
                let totalSelectedPrice = 0;
                
                if (checkedProducts.length > 0) {
                    let minPrice = Infinity;
                    checkedProducts.forEach(cb => {
                        const price = parseFloat(cb.getAttribute('data-price')) || 0;
                        if (price < minPrice) minPrice = price;
                        totalSelectedPrice += price;
                    });
                    
                    if (minPrice !== Infinity && value > minPrice) {
                        showTempAlert(`El descuento no puede ser mayor al precio del producto más barato ($${minPrice.toFixed(2)})`, 'warning');
                        value = minPrice;
                        valorDescuento.value = minPrice.toFixed(2);
                    }
                }

                // Use 100 as default if no products selected
                const basePrice = totalSelectedPrice > 0 ? totalSelectedPrice : 100;
                const basePriceText = totalSelectedPrice > 0 ? 'Total seleccionados' : 'Ejemplo base';
                
                // For fixed amount, the discount is applied PER PRODUCT usually, or TOTAL? 
                // Context: "Monto Fijo" usually means fixed amount off per unit or total?
                // In many systems "Fixed Amount" on a promotion with multiple products means "Fixed Amount off EACH product".
                // However, if it's a cart rule it might be total. 
                // Given the validation "cannot be greater than min price", it implies it's per product or at least checked against individual prices.
                // Let's assume it's per product for the example calculation if we are showing a sum, OR we show the impact on the total.
                // If I buy 3 products and discount is $10 fixed. Is it $10 off total or $10 off each?
                // Laravel Promocion model usually implies logic. 
                // Let's assume it is applied to the *unit* price based on the validation logic I just wrote (value > minPrice).
                // So if I select 3 products, the total discount is value * 3.
                
                const totalDiscount = totalSelectedPrice > 0 ? (value * checkedProducts.length) : value;
                const finalPrice = Math.max(0, basePrice - totalDiscount);
                
                exampleText.innerHTML = `<strong>${basePriceText}: $${basePrice.toFixed(2)}</strong> <br> Descuento Total: $${totalDiscount.toFixed(2)} ($${value.toFixed(2)} x ${checkedProducts.length || 1} prod) <br> <strong>Final: $${finalPrice.toFixed(2)}</strong>`;
            }
        }
    }

    // Listen for discount type changes
    document.querySelectorAll('input[name="tipo_descuento"]').forEach(radio => {
        radio.addEventListener('change', updateDiscountDisplay);
    });

    // Listen for discount value changes
    valorDescuento.addEventListener('input', updateDiscountDisplay);

    // Listen for product selection changes
    document.querySelectorAll('input[name="productos[]"]').forEach(cb => {
        cb.addEventListener('change', updateDiscountDisplay);
    });

    // Initialize display
    updateDiscountDisplay();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/admin/promociones/create.blade.php ENDPATH**/ ?>