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
    background: #3f3f46;
    color: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    justify-content: center;
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
    color: #f87171;
    transition: all 0.2s;
}

.btn-eliminar:hover {
    color: #ef4444;
}

.empty-cart {
    text-align: center;
    padding: 2rem;
}

.empty-cart i {
    font-size: 4rem;
    color: #6b7280;
    margin-bottom: 1rem;
}

.cart-item {
    display: grid;
    grid-template-columns: auto 1fr auto auto;
    gap: 1rem;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #3f3f46;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 0.5rem;
}

.cart-item-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.cart-item-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: white;
}

.cart-item-price {
    color: #9ca3af;
}

.cart-item-stock {
    font-size: 0.875rem;
    color: #6b7280;
}

.cart-item-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.cart-summary {
    margin-top: 2rem;
    padding-top: 2rem;
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
    // Aquí implementaremos la lógica para procesar el pedido
    alert('Implementaremos el procesamiento del pedido próximamente');
}
</script>
@endsection
