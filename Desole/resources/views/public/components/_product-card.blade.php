@props(['producto'])

<div class="bg-gray-800/50 rounded-xl border border-gray-700 overflow-hidden hover:border-green-500/30 transition-all duration-300 group">
    <!-- Imagen -->
    <div class="aspect-[4/3] overflow-hidden relative">
        @if($producto->imagen)
            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                 alt="{{ $producto->nombre }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
        @else
            <div class="w-full h-full bg-gray-700 flex items-center justify-center">
                <i class="fas fa-utensils text-4xl text-gray-500"></i>
            </div>
        @endif

        <!-- Badge de categoría -->
        @if($producto->categoria)
            <div class="absolute top-2 left-2">
                <span class="px-2 py-1 rounded-lg text-xs font-medium"
                      style="background-color: {{ $producto->categoria->color }}20; color: {{ $producto->categoria->color }};">
                    {{ $producto->categoria->nombre }}
                </span>
            </div>
        @endif
    </div>

    <!-- Contenido -->
    <div class="p-4">
        <h3 class="font-semibold text-lg text-white mb-1">{{ $producto->nombre }}</h3>
        
        @if($producto->descripcion)
            <p class="text-gray-400 text-sm mb-3 line-clamp-2">{{ $producto->descripcion }}</p>
        @endif

        <div class="flex items-center justify-between">
            <span class="text-green-400 font-semibold">${{ number_format($producto->precio, 2) }}</span>
            
            @if($producto->stock > 0)
                <span class="text-xs px-2 py-1 rounded-lg bg-green-500/20 text-green-400">
                    Disponible
                </span>
            @else
                <span class="text-xs px-2 py-1 rounded-lg bg-red-500/20 text-red-400">
                    Agotado
                </span>
            @endif
        </div>
    </div>
</div>