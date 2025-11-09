@extends('cliente.layout.cliente')

@section('title', 'Carrito de Compras - DÉSOLÉ')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-zinc-800 rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <h1 class="text-3xl font-bold text-green-400 mb-6">Mi Carrito</h1>

            <div id="carrito-detallado" class="space-y-6">
                <!-- El contenido del carrito se cargará dinámicamente aquí -->
            </div>
        </div>
    </div>
</div>

<style>
.cantidad-input {
    width: 60px;
    text-align: center;
    background: #3f3f46;
    color: white;
    border: 1px solid #52525b;
    border-radius: 0.375rem;
    padding: 0.25rem;
}

.cantidad-input:focus {
    outline: none;
    border-color: #65cf72;
}

.btn-cantidad {
    padding: 0.25rem 0.75rem;
    background: #3f3f46;
    color: white;
    border: 1px solid #52525b;
    border-radius: 0.375rem;
    transition: all 0.2s;
}

.btn-cantidad:hover:not(:disabled) {
    background: #52525b;
}

.btn-cantidad:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-eliminar {
    padding: 0.5rem;
    color: #ef4444;
    transition: all 0.2s;
}

.btn-eliminar:hover {
    color: #dc2626;
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 1px solid #3f3f46;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    background: rgba(63, 63, 70, 0.3);
}

.cart-item:last-child {
    margin-bottom: 0;
}

.cart-item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 0.375rem;
    margin-right: 1rem;
}

.cart-item-info {
    flex: 1;
}

.cart-item-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
}

.cart-item-price {
    color: #65cf72;
    font-size: 0.875rem;
}

.cart-item-stock {
    color: #d4d4d8;
    font-size: 0.875rem;
}

.cart-item-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-left: 1rem;
}

.empty-cart {
    text-align: center;
    padding: 2rem;
}

.empty-cart i {
    font-size: 4rem;
    color: #52525b;
    margin-bottom: 1rem;
}

.cart-summary {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #3f3f46;
}

.cart-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 1.25rem;
    font-weight: 600;
    color: white;
    margin-bottom: 1rem;
}

.cart-total-amount {
    color: #65cf72;
}

.btn-procesar {
    display: block;
    width: 100%;
    padding: 1rem;
    background: #65cf72;
    color: white;
    text-align: center;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.2s;
}

.btn-procesar:hover {
    background: #4fa85a;
}

.btn-seguir-comprando {
    display: inline-block;
    margin-top: 1rem;
    padding: 0.75rem 1.5rem;
    background: #3f3f46;
    color: white;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-seguir-comprando:hover {
    background: #52525b;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    actualizarVistaCarrito();
});

function actualizarVistaCarrito() {
    const carritoContainer = document.getElementById('carrito-detallado');
    const items = JSON.parse(localStorage.getItem('carrito')) || [];
    
    if (items.length === 0) {
        carritoContainer.innerHTML = `
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p class="text-gray-400 text-lg">Tu carrito está vacío</p>
                <a href="{{ route('cliente.menu') }}" class="btn-seguir-comprando">
                    <i class="fas fa-arrow-left mr-2"></i>Seguir Comprando
                </a>
            </div>
        `;
        return;
    }

    const total = items.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
    
    carritoContainer.innerHTML = `
        <div class="space-y-4">
            ${items.map(item => `
                <div class="cart-item" data-producto-id="${item.id}">
                    <img src="${item.imagen || '{{ asset('assets/placeholder.png') }}'}" 
                         alt="${item.nombre}" 
                         class="cart-item-image">
                    
                    <div class="cart-item-info">
                        <h3 class="cart-item-title">${item.nombre}</h3>
                        <p class="cart-item-price">$${item.precio.toFixed(2)} c/u</p>
                        <p class="cart-item-stock">Stock disponible: ${item.stock}</p>
                    </div>
                    
                    <div class="cart-item-controls">
                        <button class="btn-cantidad" onclick="ajustarCantidad(${item.id}, ${item.cantidad - 1})"
                                ${item.cantidad <= 1 ? 'disabled' : ''}>
                            <i class="fas fa-minus"></i>
                        </button>
                        
                        <input type="number" 
                               value="${item.cantidad}" 
                               min="1" 
                               max="${item.stock}"
                               class="cantidad-input"
                               onchange="actualizarCantidad(${item.id}, this.value)">
                        
                        <button class="btn-cantidad" onclick="ajustarCantidad(${item.id}, ${item.cantidad + 1})"
                                ${item.cantidad >= item.stock ? 'disabled' : ''}>
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    
                    <button class="btn-eliminar" onclick="eliminarDelCarrito(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `).join('')}
            
            <div class="cart-summary">
                <div class="cart-total">
                    <span>Total</span>
                    <span class="cart-total-amount">$${total.toFixed(2)}</span>
                </div>
                
                <button onclick="procesarPedido()" class="btn-procesar">
                    Procesar Pedido
                </button>
                
                <a href="{{ route('cliente.menu') }}" class="btn-seguir-comprando mt-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Seguir Comprando
                </a>
            </div>
        </div>
    `;
}

function ajustarCantidad(productoId, nuevaCantidad) {
    if (window.carrito) {
        window.carrito.actualizarCantidad(productoId, nuevaCantidad);
        actualizarVistaCarrito();
    }
}

function actualizarCantidad(productoId, valor) {
    const cantidad = parseInt(valor);
    if (!isNaN(cantidad) && cantidad > 0) {
        ajustarCantidad(productoId, cantidad);
    }
}

function eliminarDelCarrito(productoId) {
    if (window.carrito) {
        window.carrito.eliminar(productoId);
        actualizarVistaCarrito();
    }
}

function procesarPedido() {
    const items = JSON.parse(localStorage.getItem('carrito')) || [];
    
    if (items.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Carrito Vacío',
            text: 'Agrega algunos productos antes de procesar el pedido'
        });
        return;
    }

    window.location.href = '{{ route("cliente.carrito.confirmar") }}';
}
</script>
@endsection