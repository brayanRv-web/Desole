@props(['producto'])

<div class="bg-gray-800 rounded-xl overflow-hidden shadow-lg border border-gray-700 hover:border-green-500 transition-colors duration-200">
    <!-- Imagen del producto -->
    <div class="relative h-48 bg-gray-700">
        @if($producto->imagen)
            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                 alt="{{ $producto->nombre }}"
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-800">
                <i class="fas fa-utensils text-4xl text-gray-600"></i>
            </div>
        @endif
        
        <!-- Etiqueta de categoría -->
        <div class="absolute top-3 right-3">
            <span class="px-3 py-1 rounded-full text-sm bg-gray-900/80 text-gray-300">
                <i class="{{ $producto->categoria->icono ?? 'fas fa-tag' }} mr-1"></i>
                {{ $producto->categoria->nombre }}
            </span>
        </div>
    </div>

    <!-- Información del producto -->
    <div class="p-4">
        <h3 class="text-xl font-semibold text-white mb-2">{{ $producto->nombre }}</h3>
        <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $producto->descripcion }}</p>
        
        <div class="flex justify-between items-center">
            <!-- Precio -->
            <span class="text-green-400 text-lg font-bold">
                ${{ number_format($producto->precio, 2) }}
            </span>
            
            <!-- Stock -->
            <span class="text-sm {{ $producto->stock > 10 ? 'text-gray-400' : 'text-yellow-500' }}">
                <i class="fas fa-layer-group mr-1"></i>
                {{ $producto->stock > 10 ? 'Disponible' : 'Quedan ' . $producto->stock }}
            </span>
        </div>
    </div>

    @auth('cliente')
        <!-- Botón de ordenar (solo visible para clientes autenticados) -->
        <div class="px-4 pb-4">
            <button class="w-full py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg transition-colors duration-200">
                <i class="fas fa-shopping-cart mr-2"></i>
                Ordenar
            </button>
        </div>
    @endauth
</div>