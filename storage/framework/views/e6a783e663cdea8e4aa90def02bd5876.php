<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo e(route('admin.productos.index')); ?>" 
           class="bg-gray-700 hover:bg-gray-600 text-white p-3 rounded-xl transition-all duration-200 group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h2 class="text-3xl font-bold text-green-400 flex items-center gap-3">
                <i class="fas fa-plus-circle text-2xl"></i>
                Crear Nuevo Producto
            </h2>
            <p class="text-gray-400 mt-1">Agrega un nuevo producto al catálogo de tu cafetería</p>
        </div>
    </div>

    
    <?php if($errors->any()): ?>
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl mb-6">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-exclamation-triangle text-lg"></i>
                <h3 class="font-semibold">Por favor corrige los siguientes errores:</h3>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-gray-800/50 border border-green-700/30 rounded-2xl shadow-xl overflow-hidden">
        <form action="<?php echo e(route('admin.productos.store')); ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-8" id="productForm">
            <?php echo csrf_field(); ?>

            <!-- Información Básica -->
            <div class="bg-gray-700/30 rounded-xl p-6 border border-gray-600/50">
                <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    Información Básica
                </h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <div class="space-y-2">
                        <label for="nombre" class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                            <i class="fas fa-tag text-xs"></i>
                            Nombre del Producto *
                        </label>
                        <input type="text" name="nombre" id="nombre"
                               value="<?php echo e(old('nombre')); ?>"
                               placeholder="Ej: Cappuccino Especial"
                               required
                               class="w-full border border-gray-600 bg-gray-700/50 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 placeholder-gray-500">
                        <p class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fas fa-lightbulb text-xs"></i>
                            Usa un nombre claro y descriptivo
                        </p>
                    </div>

                    
                    <div class="space-y-2">
                        <label for="categoria_id" class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                            <i class="fas fa-folder text-xs"></i>
                            Categoría *
                        </label>
                        <select name="categoria_id" id="categoria_id" required
                                class="w-full border border-gray-600 bg-gray-700/50 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            <option value="" class="text-gray-500">-- Selecciona una categoría --</option>
                            <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($categoria->id); ?>" <?php echo e(old('categoria_id') == $categoria->id ? 'selected' : ''); ?> class="text-white">
                                    <?php echo e($categoria->nombre); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fas fa-lightbulb text-xs"></i>
                            Organiza tus productos por categorías
                        </p>
                    </div>

                    
                    <div class="space-y-2">
                        <label for="precio" class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                            <i class="fas fa-dollar-sign text-xs"></i>
                            Precio ($MXN) *
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">$</span>
                            <input type="number" step="0.01" name="precio" id="precio"
                                   value="<?php echo e(old('precio')); ?>"
                                   placeholder="0.00"
                                   min="0"
                                   required
                                   class="w-full border border-gray-600 bg-gray-700/50 text-white pl-10 pr-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                        </div>
                        <p class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fas fa-lightbulb text-xs"></i>
                            Precio de venta al público
                        </p>
                    </div>
                    
                    <div class="space-y-2">
                        <label for="stock" class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                            <i class="fas fa-boxes text-xs"></i>
                            Stock Disponible *
                        </label>
                        <input type="number" name="stock" id="stock"
                            value="<?php echo e(old('stock', 0)); ?>"
                            placeholder="0"
                            min="0"
                            required
                            class="w-full border border-gray-600 bg-gray-700/50 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                        <p class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fas fa-lightbulb text-xs"></i>
                            Cantidad disponible para la venta
                        </p>
                    </div>

                    
                    <div class="space-y-2">
                        <label for="estado" class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                            <i class="fas fa-circle text-xs"></i>
                            Estado *
                        </label>
                        <select name="estado" id="estado" required
                                class="w-full border border-gray-600 bg-gray-700/50 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            <option value="activo" <?php echo e(old('estado', 'activo') == 'activo' ? 'selected' : ''); ?> class="text-green-400">🟢 Activo - Disponible para venta</option>
                            <option value="inactivo" <?php echo e(old('estado') == 'inactivo' ? 'selected' : ''); ?> class="text-gray-400">⚪ Inactivo - No visible</option>
                            <option value="agotado" <?php echo e(old('estado') == 'agotado' ? 'selected' : ''); ?> class="text-red-400">🔴 Agotado - Visible pero sin stock</option>
                        </select>
                        <p class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fas fa-lightbulb text-xs"></i>
                            Controla la visibilidad del producto
                        </p>
                    </div>
                </div>
            </div>

            
            <div class="bg-gray-700/30 rounded-xl p-6 border border-gray-600/50">
                <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center gap-2">
                    <i class="fas fa-align-left"></i>
                    Descripción del Producto
                </h3>
                <div class="space-y-3">
                    <textarea name="descripcion" id="descripcion" rows="4"
                              placeholder="Describe las características, ingredientes, beneficios y cualquier detalle importante que los clientes deben conocer sobre este producto..."
                              class="w-full border border-gray-600 bg-gray-700/50 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-none placeholder-gray-500"><?php echo e(old('descripcion')); ?></textarea>
                    <div class="flex justify-between items-center">
                        <p class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fas fa-info-circle text-xs"></i>
                            Una buena descripción ayuda a aumentar las ventas
                        </p>
                        <span id="charCount" class="text-xs text-gray-400">0 caracteres</span>
                    </div>
                </div>
            </div>

            
            <div class="bg-gray-700/30 rounded-xl p-6 border border-gray-600/50">
                <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center gap-2">
                    <i class="fas fa-image"></i>
                    Imagen del Producto
                </h3>
                
                <div class="space-y-4">
                    <div class="border-2 border-dashed border-gray-600 rounded-xl p-8 text-center transition-all duration-200 hover:border-green-500/50 hover:bg-green-500/5 cursor-pointer"
                         onclick="document.getElementById('imagen').click()"
                         id="dropArea">
                        <input type="file" name="imagen" id="imagen"
                               accept="image/*"
                               class="hidden"
                               onchange="previewImage(this)">
                        <div class="space-y-3">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            <p class="text-gray-400 font-medium text-lg">Haz clic para subir una imagen</p>
                            <p class="text-gray-500 text-sm">o arrastra y suelta una imagen aquí</p>
                            <p class="text-gray-400 text-xs mt-2">Formatos: JPG, PNG, WEBP — Tamaño máximo: 2MB</p>
                        </div>
                    </div>
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="hidden p-6 bg-gray-700/50 rounded-xl border border-green-500/30">
                        <p class="text-sm text-green-400 mb-3 flex items-center gap-2">
                            <i class="fas fa-eye text-xs"></i>
                            Vista previa de la imagen:
                        </p>
                        <div class="flex items-center gap-4">
                            <img id="preview" class="w-32 h-32 object-cover rounded-xl shadow-lg">
                            <div class="flex-1">
                                <p class="text-white font-medium">Imagen seleccionada</p>
                                <p class="text-gray-400 text-sm mt-1">La imagen se mostrará así en el catálogo</p>
                                <button type="button" onclick="removeImage()" 
                                        class="mt-2 bg-red-600/20 hover:bg-red-600/40 border border-red-500/50 text-red-400 hover:text-red-300 px-3 py-1 rounded-lg text-sm transition-all duration-200 flex items-center gap-1">
                                    <i class="fas fa-times text-xs"></i>
                                    Eliminar imagen
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Image Requirements -->
                    <div class="bg-gray-800/50 rounded-xl p-4 border border-gray-600/30">
                        <h4 class="text-sm font-semibold text-green-300 mb-2 flex items-center gap-2">
                            <i class="fas fa-camera text-xs"></i>
                            Recomendaciones para la imagen:
                        </h4>
                        <ul class="text-xs text-gray-400 space-y-1">
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-green-400 text-xs"></i>
                                Formato cuadrado o rectangular
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-green-400 text-xs"></i>
                                Buena iluminación y enfoque
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-green-400 text-xs"></i>
                                Fondo limpio y profesional
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="fas fa-check text-green-400 text-xs"></i>
                                Tamaño recomendado: 800x800px
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            
            <div class="flex flex-col sm:flex-row gap-4 justify-between pt-6 border-t border-gray-700/50">
                <a href="<?php echo e(route('admin.productos.index')); ?>"
                   class="bg-gray-700 hover:bg-gray-600 text-white px-8 py-3 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 font-semibold group order-2 sm:order-1">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Volver a Productos
                </a>
                <div class="flex gap-3 order-1 sm:order-2">
                    <button type="button" onclick="resetForm()"
                            class="bg-gray-600 hover:bg-gray-500 text-white px-6 py-3 rounded-xl transition-all duration-200 flex items-center gap-2 font-semibold">
                        <i class="fas fa-undo"></i>
                        Limpiar Formulario
                    </button>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-green-500/25 transition-all duration-200 flex items-center gap-2 font-semibold group">
                        <i class="fas fa-plus-circle group-hover:scale-110 transition-transform"></i>
                        Crear Producto
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick Tips -->
    <div class="mt-8 bg-blue-500/10 border border-blue-500/30 rounded-2xl p-6">
        <h3 class="text-lg font-semibold text-blue-400 mb-3 flex items-center gap-2">
            <i class="fas fa-lightbulb"></i>
            Consejos para un buen producto
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-300">
            <div class="flex items-start gap-3">
                <i class="fas fa-check text-blue-400 mt-1"></i>
                <p>Usa nombres claros y descriptivos que los clientes entiendan fácilmente</p>
            </div>
            <div class="flex items-start gap-3">
                <i class="fas fa-check text-blue-400 mt-1"></i>
                <p>Incluye una descripción detallada con ingredientes y beneficios</p>
            </div>
            <div class="flex items-start gap-3">
                <i class="fas fa-check text-blue-400 mt-1"></i>
                <p>Selecciona una imagen atractiva y de alta calidad</p>
            </div>
            <div class="flex items-start gap-3">
                <i class="fas fa-check text-blue-400 mt-1"></i>
                <p>Verifica que el precio sea competitivo y acorde al mercado</p>
            </div>
        </div>
    </div>
</div>

<script>
// Variable para controlar si se hizo reset
let formWasReset = false;

function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('imagen').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
}

function resetForm() {
    if (confirm('¿Estás seguro de que deseas limpiar todo el formulario? Se perderán todos los datos ingresados.')) {
        formWasReset = true;
        
        // Método 1: Reset nativo del formulario
        const form = document.getElementById('productForm');
        form.reset();
        
        // Método 2: Limpiar manualmente cada campo (más agresivo)
        const inputs = form.querySelectorAll('input[type="text"], input[type="number"], textarea');
        inputs.forEach(input => {
            input.value = '';
        });
        
        // Limpiar selects
        const selects = form.querySelectorAll('select');
        selects.forEach(select => {
            select.selectedIndex = 0;
        });
        
        // Limpiar archivos
        const fileInputs = form.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.value = '';
        });
        
        // Limpiar vista previa de imagen
        document.getElementById('imagePreview').classList.add('hidden');
        
        // Resetear contador
        updateCharCount();
        
        // Forzar un reload suave de la página después de un breve delay
        setTimeout(() => {
            // Recargar la página para limpiar los datos de old() de Laravel
            window.location.href = window.location.href;
        }, 100);
    }
}

// Character counter for description
const descripcionTextarea = document.getElementById('descripcion');
const charCount = document.getElementById('charCount');

function updateCharCount() {
    if (descripcionTextarea && charCount) {
        const length = descripcionTextarea.value.length;
        charCount.textContent = `${length} caracteres`;
        
        if (length > 500) {
            charCount.classList.add('text-yellow-400');
            charCount.classList.remove('text-gray-400');
        } else {
            charCount.classList.remove('text-yellow-400');
            charCount.classList.add('text-gray-400');
        }
    }
}

if (descripcionTextarea) {
    descripcionTextarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
}

// Drag and drop functionality
const dropArea = document.getElementById('dropArea');

if (dropArea) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropArea.classList.add('border-green-500', 'bg-green-500/10');
    }

    function unhighlight() {
        dropArea.classList.remove('border-green-500', 'bg-green-500/10');
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            document.getElementById('imagen').files = files;
            previewImage(document.getElementById('imagen'));
        }
    }
}

// Form validation enhancement
document.getElementById('productForm').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();
    const precio = document.getElementById('precio').value;
    const categoria = document.getElementById('categoria_id').value;
    
    if (!nombre) {
        e.preventDefault();
        alert('Por favor ingresa un nombre para el producto');
        document.getElementById('nombre').focus();
        return;
    }
    
    if (!categoria) {
        e.preventDefault();
        alert('Por favor selecciona una categoría');
        document.getElementById('categoria_id').focus();
        return;
    }
    
    if (!precio || parseFloat(precio) <= 0) {
        e.preventDefault();
        alert('Por favor ingresa un precio válido mayor a 0');
        document.getElementById('precio').focus();
        return;
    }
});
</script>

<style>
/* Custom scrollbar for select */
select {
    scrollbar-width: thin;
    scrollbar-color: #4ade80 #1f2937;
}

select::-webkit-scrollbar {
    width: 6px;
}

select::-webkit-scrollbar-track {
    background: #1f2937;
    border-radius: 3px;
}

select::-webkit-scrollbar-thumb {
    background: #4ade80;
    border-radius: 3px;
}

select::-webkit-scrollbar-thumb:hover {
    background: #22c55e;
}

/* Smooth transitions */
input, select, textarea, button, a {
    transition: all 0.2s ease-in-out;
}

/* Focus styles */
input:focus, select:focus, textarea:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/admin/productos/create.blade.php ENDPATH**/ ?>