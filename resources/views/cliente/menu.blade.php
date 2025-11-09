@extends('cliente.layout.cliente')

@section('title', 'Menú - DÉSOLÉ')

@section('content')
<div class="dashboard-container">
    <div class="menu-container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="relative mb-8">
            <div class="text-center max-w-2xl mx-auto">
                <h1 class="text-4xl font-bold text-green-400 mb-4">Nuestro Menú</h1>
                <p class="text-gray-400">Explora nuestra deliciosa selección de platos y bebidas preparados con los mejores ingredientes.</p>
            </div>
        </div>

        <!-- Filtros de Categoría -->
        <div class="categoria-filters mb-8">
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('cliente.menu') }}" 
                   class="filter-btn {{ !request('categoria') ? 'active' : '' }}">
                    <i class="fas fa-utensils"></i> Todos
                </a>
                @foreach($categorias as $categoria)
                    <a href="{{ route('cliente.menu', ['categoria' => $categoria->id]) }}" 
                       class="filter-btn {{ request('categoria') == $categoria->id ? 'active' : '' }}">
                        <i class="{{ $categoria->icono ?? 'fas fa-tag' }}"></i> {{ $categoria->nombre }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Grid de Productos -->
        <div class="productos-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($productos as $producto)
                <div class="producto-card bg-zinc-800 rounded-xl overflow-hidden shadow-lg transition-transform hover:scale-105"
                     data-producto-id="{{ $producto->id }}"
                     data-nombre="{{ $producto->nombre }}"
                     data-precio="{{ $producto->precio }}"
                     data-stock="{{ $producto->stock }}">
                    <div class="producto-image h-48 relative">
                        @if($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                 alt="{{ $producto->nombre }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="no-image w-full h-full flex items-center justify-center bg-zinc-700">
                                <i class="fas fa-image text-4xl text-zinc-500"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-green-400 mb-2">{{ $producto->nombre }}</h3>
                        <p class="text-gray-400 text-sm mb-4">{{ $producto->descripcion }}</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xl font-bold text-white">${{ number_format($producto->precio, 2) }}</span>
                            <span class="text-sm text-gray-400">Stock: {{ $producto->stock }}</span>
                        </div>
                        <button class="w-full py-2 px-4 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center justify-center gap-2 {{ $producto->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $producto->stock <= 0 ? 'disabled' : '' }}
                                data-agregar-producto>
                            <i class="fas fa-cart-plus"></i>
                            <span>{{ $producto->stock > 0 ? 'Agregar al carrito' : 'Sin stock' }}</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if($productos->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-coffee text-6xl text-gray-600 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-400 mb-2">No hay productos disponibles</h3>
                <p class="text-gray-500">Vuelve pronto para ver nuestro menú</p>
            </div>
        @endif
    </div>
</div>

<style>
.productos-grid {
    margin-top: 1rem;
}

.producto-card {
    background: var(--color-bg-alt);
    border: 1px solid #333;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.producto-card:hover {
    border-color: var(--color-primary);
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(101, 207, 114, 0.2);
}

.filter-btn {
    @apply px-4 py-2 rounded-lg bg-zinc-800 text-gray-300 hover:bg-green-600 hover:text-white transition-all duration-300 flex items-center gap-2 border border-zinc-700;
}

.filter-btn.active {
    @apply bg-green-600 text-white border-green-500;
}
</style>

<script>
// Escuchar el evento DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    // Configurar los botones de agregar al carrito
    document.querySelectorAll('[data-producto-id]').forEach(productoElement => {
        const btnAgregar = productoElement.querySelector('[data-agregar-producto]');
        
        if (btnAgregar && !productoElement.dataset.configurado) {
            btnAgregar.addEventListener('click', (e) => {
                e.preventDefault();
                
                const producto = {
                    id: parseInt(productoElement.dataset.productoId),
                    nombre: productoElement.dataset.nombre,
                    precio: parseFloat(productoElement.dataset.precio),
                    stock: parseInt(productoElement.dataset.stock)
                };
                
                if (window.carrito) {
                    window.carrito.agregar(producto);
                }
            });
            
            // Marcar como configurado para evitar duplicar event listeners
            productoElement.dataset.configurado = 'true';
        }
    });
});
</script>
@endsection