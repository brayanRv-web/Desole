@extends('admin.layout')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.productos.index') }}" 
               class="bg-gray-700 hover:bg-gray-600 text-white p-3 rounded-xl transition-all duration-200 group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            </a>
            <div>
                <h2 class="text-3xl font-bold text-green-400 flex items-center gap-3">
                    <i class="fas fa-eye text-2xl"></i>
                    Vista Previa del Producto
                </h2>
                <p class="text-gray-400 mt-1">Así verán los clientes tu producto</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 rounded-full text-sm font-medium 
                @if($producto->estado == 'activo') bg-green-500/20 text-green-400 border border-green-500/50
                @elseif($producto->estado == 'inactivo') bg-gray-500/20 text-gray-400 border border-gray-500/50
                @else bg-red-500/20 text-red-400 border border-red-500/50 @endif">
                <i class="fas fa-circle text-xs mr-1"></i>
                {{ ucfirst($producto->estado) }}
            </span>
            <a href="{{ route('admin.productos.edit', $producto) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-edit"></i>
                Editar
            </a>
        </div>
    </div>

    <!-- Product Preview Card -->
    <div class="bg-gray-800/50 border border-green-700/30 rounded-2xl shadow-xl overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
            <!-- Product Image Section -->
            <div class="space-y-6">
                <!-- Main Image -->
                <div class="bg-gray-900 rounded-2xl p-6 border border-gray-700/50">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" 
                             alt="{{ $producto->nombre }}"
                             class="w-full h-80 object-cover rounded-xl shadow-2xl">
                    @else
                        <div class="w-full h-80 bg-gray-800 rounded-xl flex items-center justify-center border-2 border-dashed border-gray-600">
                            <div class="text-center">
                                <i class="fas fa-image text-6xl text-gray-600 mb-3"></i>
                                <p class="text-gray-500 text-sm">Sin imagen</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Additional Info -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-900 rounded-xl p-4 text-center border border-gray-700/50">
                        <i class="fas fa-tags text-green-400 text-xl mb-2"></i>
                        <p class="text-gray-400 text-sm">Categoría</p>
                        <p class="text-white font-semibold">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>
                    </div>
                    <div class="bg-gray-900 rounded-xl p-4 text-center border border-gray-700/50">
                        <i class="fas fa-calendar text-blue-400 text-xl mb-2"></i>
                        <p class="text-gray-400 text-sm">Creado</p>
                        <p class="text-white font-semibold text-sm">{{ $producto->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="space-y-6">
                <!-- Product Header -->
                <div class="bg-gray-900 rounded-2xl p-6 border border-gray-700/50">
                    <h1 class="text-3xl font-bold text-white mb-2">{{ $producto->nombre }}</h1>
                    <div class="flex items-center gap-4 mb-4">
                        <span class="text-4xl font-bold text-green-400">
                            ${{ number_format($producto->precio, 2) }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($producto->estado == 'activo') bg-green-500/20 text-green-400
                            @elseif($producto->estado == 'inactivo') bg-gray-500/20 text-gray-400
                            @else bg-red-500/20 text-red-400 @endif">
                            {{ $producto->estado == 'activo' ? 'Disponible' : 
                               ($producto->estado == 'inactivo' ? 'No disponible' : 'Agotado') }}
                        </span>
                    </div>
                    
                    <!-- Action Buttons (Simulated Customer View) -->
                    <div class="flex gap-3 mt-6">
                        <button class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2 group">
                            <i class="fas fa-shopping-cart group-hover:scale-110 transition-transform"></i>
                            Agregar al Carrito
                        </button>
                        <button class="bg-gray-700 hover:bg-gray-600 text-white p-3 rounded-xl transition-all duration-200 group">
                            <i class="fas fa-heart group-hover:text-red-400 transition-colors"></i>
                        </button>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-gray-900 rounded-2xl p-6 border border-gray-700/50">
                    <h3 class="text-xl font-semibold text-green-400 mb-4 flex items-center gap-2">
                        <i class="fas fa-align-left"></i>
                        Descripción
                    </h3>
                    @if($producto->descripcion)
                        <div class="text-gray-300 leading-relaxed space-y-3">
                            @foreach(explode("\n", $producto->descripcion) as $paragraph)
                                @if(trim($paragraph))
                                    <p>{{ $paragraph }}</p>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-align-left text-4xl text-gray-600 mb-3"></i>
                            <p class="text-gray-500">Este producto no tiene descripción</p>
                        </div>
                    @endif
                </div>

                <!-- Product Features -->
                <div class="bg-gray-900 rounded-2xl p-6 border border-gray-700/50">
                    <h3 class="text-xl font-semibold text-green-400 mb-4 flex items-center gap-2">
                        <i class="fas fa-star"></i>
                        Características
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-3 bg-gray-800/50 rounded-lg">
                            <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center">
                                <i class="fas fa-check text-green-400"></i>
                            </div>
                            <div>
                                <p class="text-white font-medium">Producto Verificado</p>
                                <p class="text-gray-400 text-sm">Calidad garantizada</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-800/50 rounded-lg">
                            <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center">
                                <i class="fas fa-shipping-fast text-blue-400"></i>
                            </div>
                            <div>
                                <p class="text-white font-medium">Envío Disponible</p>
                                <p class="text-gray-400 text-sm">Entrega rápida</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Actions Footer -->
        <div class="border-t border-gray-700/50 bg-gray-900/30 px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-400">
                    <i class="fas fa-info-circle mr-2"></i>
                    Vista de previsualización - Los clientes verán el producto de esta manera
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.productos.edit', $producto) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl transition-all duration-200 flex items-center gap-2 font-semibold group">
                        <i class="fas fa-edit group-hover:scale-110 transition-transform"></i>
                        Editar Producto
                    </a>
                    <a href="{{ route('admin.productos.index') }}" 
                       class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-3 rounded-xl transition-all duration-200 flex items-center gap-2 font-semibold group">
                        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                        Volver a Productos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-gray-800/50 rounded-2xl p-6 border border-green-700/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center">
                    <i class="fas fa-eye text-green-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Vistas</p>
                    <p class="text-2xl font-bold text-white">1,247</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 rounded-2xl p-6 border border-blue-700/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">En Carritos</p>
                    <p class="text-2xl font-bold text-white">23</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-800/50 rounded-2xl p-6 border border-purple-700/30">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-purple-500/20 flex items-center justify-center">
                    <i class="fas fa-heart text-purple-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">En Favoritos</p>
                    <p class="text-2xl font-bold text-white">45</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Smooth transitions */
* {
    transition: all 0.2s ease-in-out;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #1f2937;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #4ade80;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #22c55e;
}

/* Hover effects */
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
}
</style>

<script>
// Add interactive effects
document.addEventListener('DOMContentLoaded', function() {
    // Add hover lift effect to cards
    const cards = document.querySelectorAll('.bg-gray-900');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('hover-lift');
        });
        card.addEventListener('mouseleave', function() {
            this.classList.remove('hover-lift');
        });
    });

    // Simulate button interactions
    const addToCartBtn = document.querySelector('button:contains("Agregar al Carrito")');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i> ¡Agregado!';
            this.classList.remove('bg-green-600', 'hover:bg-green-700');
            this.classList.add('bg-green-700');
            
            setTimeout(() => {
                this.innerHTML = originalText;
                this.classList.remove('bg-green-700');
                this.classList.add('bg-green-600', 'hover:bg-green-700');
            }, 2000);
        });
    }
});
</script>
@endsection