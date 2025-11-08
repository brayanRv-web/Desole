@extends('cliente.layout.cliente')

@section('title', 'Menú - DÉSOLÉ')

@section('content')
<div class="dashboard-container">
    <!-- Header con título y filtros -->
    <div class="dashboard-header">
        <div class="welcome-card">
            <div class="welcome-info">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Nuestro Menú</h1>
                    <p class="welcome-text">Explora nuestra variedad de deliciosos platillos y bebidas</p>
                </div>
                <!-- Carrito resumen -->
                <div class="cart-summary">
                    <button class="cart-btn">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="cart-count">0</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <!-- Sidebar con filtros -->
        <div class="sidebar-section">
            <div class="actions-card">
                <div class="card-header-custom">
                    <h3><i class="fas fa-filter me-2"></i>Filtrar por Categoría</h3>
                </div>
                <div class="card-body-custom">
                    <div class="actions-grid">
                        <a href="{{ route('cliente.menu') }}" class="action-btn {{ !request('categoria') ? 'primary' : 'secondary' }}">
                            <i class="fas fa-utensils"></i>
                            <span>Todos los productos</span>
                        </a>
                        @foreach($categorias as $categoria)
                        <a href="{{ route('cliente.menu', ['categoria' => $categoria->id]) }}" 
                           class="action-btn {{ request('categoria') == $categoria->id ? 'primary' : 'secondary' }}">
                            <i class="{{ $categoria->icono ?? 'fas fa-tag' }}"></i>
                            <span>{{ $categoria->nombre }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            @if(isset($promociones) && $promociones->count() > 0)
            <div class="promotions-card">
                <div class="card-header-custom">
                    <h3><i class="fas fa-tags me-2"></i>Promociones Activas</h3>
                </div>
                <div class="card-body-custom">
                    <div class="promotions-list">
                        @foreach($promociones as $promocion)
                        <div class="promotion-item">
                            <div class="promotion-header">
                                <h4>{{ $promocion->nombre }}</h4>
                                <span class="discount-badge">{{ $promocion->valor_descuento }}% OFF</span>
                            </div>
                            <p class="promotion-desc">{{ $promocion->descripcion }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Contenido principal - Lista de productos -->
        <div class="main-section">
            <div class="productos-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($productos as $producto)
        <div class="producto-card">
            <div class="producto-image">
                @if($producto->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}" 
                         alt="{{ $producto->nombre }}"
                         class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-700 flex items-center justify-center">
                        <i class="fas fa-utensils text-4xl text-gray-500"></i>
                    </div>
                @endif
                @if($producto->categoria)
                    <div class="categoria-badge">
                        <i class="{{ $producto->categoria->icono ?? 'fas fa-tag' }}"></i>
                        {{ $producto->categoria->nombre }}
                    </div>
                @endif
            </div>

            <div class="producto-content">
                <div class="producto-header">
                    <h3 class="producto-nombre">{{ $producto->nombre }}</h3>
                    <span class="producto-precio">${{ number_format($producto->precio, 2) }}</span>
                </div>

                <p class="producto-descripcion">
                    {{ $producto->descripcion ?: 'Sin descripción' }}
                </p>

                <div class="producto-footer">
                    <span class="stock-badge {{ $producto->stock > 0 ? 'disponible' : 'agotado' }}">
                        @if($producto->stock > 0)
                            <i class="fas fa-check-circle"></i> {{ $producto->stock }} disponibles
                        @else
                            <i class="fas fa-times-circle"></i> Agotado
                        @endif
                    </span>

                    <button 
                        onclick="agregarAlCarrito({{ $producto->id }})"
                        @if($producto->stock <= 0) disabled @endif
                        class="btn-agregar {{ $producto->stock <= 0 ? 'disabled' : '' }}">
                        <i class="fas fa-cart-plus"></i>
                        Agregar al carrito
                    </button>
                </div>
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
    </div>
</div>

<style>
/* Estilos para los productos */
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

.producto-image {
    position: relative;
    background: var(--color-bg);
}

.categoria-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(0, 0, 0, 0.75);
    color: var(--color-primary);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    backdrop-filter: blur(4px);
}

.producto-content {
    padding: 1.5rem;
}

.producto-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    gap: 1rem;
}

.producto-nombre {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--color-text);
    margin: 0;
}

.producto-precio {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--color-primary);
}

.producto-descripcion {
    color: var(--color-text-muted);
    font-size: 0.9rem;
    line-height: 1.5;
    margin-bottom: 1.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.producto-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.stock-badge {
    font-size: 0.85rem;
    padding: 4px 8px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.stock-badge.disponible {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.stock-badge.agotado {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.btn-agregar {
    background: var(--color-primary);
    color: var(--color-bg);
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    cursor: pointer;
}

.btn-agregar:hover:not(.disabled) {
    background: #5ac968;
    transform: translateY(-2px);
}

.btn-agregar.disabled {
    background: #4a4a4a;
    cursor: not-allowed;
    opacity: 0.7;
}

/* Carrito en el header */
.cart-summary {
    display: flex;
    align-items: center;
}

.cart-btn {
    position: relative;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 48px;
    height: 48px;
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.cart-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

.cart-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    width: 20px;
    height: 20px;
    border-radius: 10px;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 1024px) {
    .dashboard-content {
        grid-template-columns: 1fr;
    }
    
    .sidebar-section {
        order: 2;
    }
    
    .main-section {
        order: 1;
    }
}

@media (max-width: 768px) {
    .productos-grid {
        grid-template-columns: 1fr;
    }
    
    .producto-footer {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-agregar {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
function agregarAlCarrito(productoId) {
    // Aquí irá la lógica para agregar al carrito
    console.log('Agregando producto:', productoId);
}
</script>
@endsection