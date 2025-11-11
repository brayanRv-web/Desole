@extends('admin.layout')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.productos.index') }}" 
           class="bg-gray-700 hover:bg-gray-600 text-white p-3 rounded-xl transition-all duration-200 group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        </a>
        <div>
            <h2 class="text-3xl font-bold text-green-400 flex items-center gap-3">
                <i class="fas fa-edit text-2xl"></i>
                Editar Producto
            </h2>
            <p class="text-gray-400 mt-1">Actualiza la información del producto</p>
        </div>
    </div>

    {{-- Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl mb-6">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-exclamation-triangle text-lg"></i>
                <h3 class="font-semibold">Por favor corrige los siguientes errores:</h3>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/50 text-green-400 px-6 py-4 rounded-xl mb-6 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-400"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-gray-800/50 border border-green-700/30 rounded-2xl shadow-xl overflow-hidden">
        <form action="{{ route('admin.productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- Grid Layout for Form -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    {{-- Nombre --}}
                    <div class="space-y-2">
                        <label for="nombre" class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                            <i class="fas fa-tag text-xs"></i>
                            Nombre del Producto
                        </label>
                        <input type="text" name="nombre" id="nombre"
                               value="{{ old('nombre', $producto->nombre) }}"
                               placeholder="Ej: Cappuccino Especial"
                               required
                               class="w-full border border-gray-600 bg-gray-700/50 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 placeholder-gray-500">
                    </div>

                    {{-- Categoría --}}
                    <div class="space-y-2">
                        <label for="categoria_id" class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                            <i class="fas fa-folder text-xs"></i>
                            Categoría
                        </label>
                        <select name="categoria_id" id="categoria_id" required
                                class="w-full border border-gray-600 bg-gray-700/50 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            <option value="" class="text-gray-500">-- Selecciona una categoría --</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }} class="text-white">
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Precio --}}
                    <div class="space-y-2">
                        <label for="precio" class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                            <i class="fas fa-dollar-sign text-xs"></i>
                            Precio ($MXN)
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">$</span>
                            <input type="number" step="0.01" name="precio" id="precio"
                                   value="{{ old('precio', $producto->precio) }}"
                                   placeholder="0.00"
                                   required
                                   class="w-full border border-gray-600 bg-gray-700/50 text-white pl-10 pr-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                    {{-- En la columna izquierda, después del precio --}}
                    <div class="space-y-2">
                        <label for="stock" class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                            <i class="fas fa-boxes text-xs"></i>
                            Stock Disponible
                        </label>
                        <input type="number" name="stock" id="stock"
                            value="{{ old('stock', $producto->stock) }}"
                            placeholder="0"
                            min="0"
                            required
                            class="w-full border border-gray-600 bg-gray-700/50 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    {{-- Imagen --}}
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                            <i class="fas fa-image text-xs"></i>
                            Imagen del Producto
                        </label>

                        @if($producto->imagen)
                            <div class="mb-4 p-4 bg-gray-700/30 rounded-xl border border-gray-600/50">
                                <p class="text-sm text-gray-400 mb-3 flex items-center gap-2">
                                    <i class="fas fa-image text-xs"></i>
                                    Imagen actual:
                                </p>
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset('storage/' . $producto->imagen) }}"
                                         alt="Imagen actual de {{ $producto->nombre }}"
                                         class="w-20 h-20 object-cover rounded-lg border-2 border-green-500/50 shadow-lg">
                                    <div class="flex-1">
                                        <p class="text-white text-sm font-medium">{{ $producto->nombre }}</p>
                                        <p class="text-gray-400 text-xs">Haz clic en "Elegir archivo" para cambiar la imagen</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-4 p-4 bg-gray-700/30 rounded-xl border border-gray-600/50 border-dashed text-center">
                                <i class="fas fa-image text-3xl text-gray-500 mb-2"></i>
                                <p class="text-gray-400 text-sm">No hay imagen actual</p>
                            </div>
                        @endif

                        <div class="border-2 border-dashed border-gray-600 rounded-xl p-6 text-center transition-all duration-200 hover:border-green-500/50 hover:bg-green-500/5">
                            <input type="file" name="imagen" id="imagen"
                                   accept="image/*"
                                   class="hidden"
                                   onchange="previewImage(this)">
                            <label for="imagen" class="cursor-pointer">
                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                <p class="text-gray-400 text-sm mb-1">Haz clic para subir una nueva imagen</p>
                                <p class="text-gray-500 text-xs">JPG, PNG, WEBP — máximo 2MB</p>
                            </label>
                        </div>
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="hidden mt-4 p-4 bg-gray-700/30 rounded-xl border border-green-500/30">
                            <p class="text-sm text-green-400 mb-2 flex items-center gap-2">
                                <i class="fas fa-eye text-xs"></i>
                                Vista previa:
                            </p>
                            <img id="preview" class="w-32 h-32 object-cover rounded-lg mx-auto shadow-lg">
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div class="space-y-2">
                        <label for="estado" class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                            <i class="fas fa-circle text-xs"></i>
                            Estado del Producto
                        </label>
                        <select name="estado" id="estado" required
                                class="w-full border border-gray-600 bg-gray-700/50 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            <option value="activo" {{ old('status', $producto->status) == 'activo' ? 'selected' : '' }} class="text-white">
                                🟢 Activo
                            </option>
                            <option value="inactivo" {{ old('status', $producto->status) == 'inactivo' ? 'selected' : '' }} class="text-white">
                                ⚪ Inactivo
                            </option>
                            <option value="agotado" {{ old('status', $producto->status) == 'agotado' ? 'selected' : '' }} class="text-white">
                                🔴 Agotado
                            </option>
                        </select>
                        <p class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fas fa-info-circle text-xs"></i>
                            El estado determina la visibilidad del producto para los clientes
                        </p>
                    </div>
                                    </div>
            </div>

            {{-- Descripción --}}
            <div class="space-y-2">
                <label for="descripcion" class="block text-sm font-semibold text-green-300 flex items-center gap-2">
                    <i class="fas fa-align-left text-xs"></i>
                    Descripción
                </label>
                <textarea name="descripcion" id="descripcion" rows="4"
                          placeholder="Describe las características, ingredientes y beneficios del producto..."
                          class="w-full border border-gray-600 bg-gray-700/50 text-white px-4 py-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 resize-none placeholder-gray-500">{{ old('descripcion', $producto->descripcion) }}</textarea>
                <p class="text-xs text-gray-400 flex items-center gap-1">
                    <i class="fas fa-info-circle text-xs"></i>
                    Esta descripción ayudará a los clientes a conocer mejor el producto
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 justify-between pt-6 border-t border-gray-700/50">
                <a href="{{ route('admin.productos.index') }}"
                   class="bg-gray-700 hover:bg-gray-600 text-white px-8 py-3 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 font-semibold group order-2 sm:order-1">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Volver a Productos
                </a>
                <div class="flex gap-3 order-1 sm:order-2">

                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-green-500/25 transition-all duration-200 flex items-center gap-2 font-semibold group">
                        <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                        Actualizar Producto
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
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
    } else {
        previewContainer.classList.add('hidden');
    }
}

// Character counter for description
const descripcionTextarea = document.getElementById('descripcion');
if (descripcionTextarea) {
    // Create counter element
    const counter = document.createElement('div');
    counter.className = 'text-xs text-gray-400 text-right mt-1';
    descripcionTextarea.parentNode.appendChild(counter);
    
    function updateCounter() {
        const length = descripcionTextarea.value.length;
        counter.textContent = `${length} caracteres`;
        
        if (length > 500) {
            counter.classList.add('text-red-400');
            counter.classList.remove('text-gray-400');
        } else {
            counter.classList.remove('text-red-400');
            counter.classList.add('text-gray-400');
        }
    }
    
    descripcionTextarea.addEventListener('input', updateCounter);
    updateCounter(); // Initial count
}
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

/* Smooth transitions for all interactive elements */
input, select, textarea, button, a {
    transition: all 0.2s ease-in-out;
}
</style>
@endsection