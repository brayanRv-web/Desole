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
            <div class="cart-summary fixed top-4 right-4">
                <button class="cart-btn" id="cartBtn" type="button">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span class="cart-count">0</span>
                </button>
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
                        <button onclick="agregarAlCarrito({{ $producto->id }})"
                                class="w-full py-2 px-4 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center justify-center gap-2 {{ $producto->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $producto->stock <= 0 ? 'disabled' : '' }}>
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

/* Estilos para los botones de filtro */
.filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background-color: var(--color-bg-alt);
    color: var(--color-text);
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: 1px solid #333;
}

.filter-btn:hover {
    background-color: var(--color-primary);
    color: var(--color-bg);
    transform: translateY(-2px);
}

.filter-btn.active {
    background-color: var(--color-primary);
    color: var(--color-bg);
    border-color: var(--color-primary);
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
// Clase para manejar el carrito
class Carrito {
    constructor() {
        console.log('Inicializando clase Carrito');
        this.items = JSON.parse(localStorage.getItem('carrito')) || [];
        this.actualizarContador();
    }

    // Agregar producto al carrito
    agregar(producto) {
        const itemExistente = this.items.find(item => item.id === producto.id);
        
        if (itemExistente) {
            if (itemExistente.cantidad < producto.stock) {
                itemExistente.cantidad++;
                this.guardar();
                this.mostrarNotificacion('Cantidad actualizada en el carrito', 'success');
            } else {
                this.mostrarNotificacion('Stock máximo alcanzado', 'error');
                return;
            }
        } else {
            this.items.push({
                id: producto.id,
                nombre: producto.nombre,
                precio: producto.precio,
                cantidad: 1,
                stock: producto.stock
            });
            this.guardar();
            this.mostrarNotificacion('Producto agregado al carrito', 'success');
        }
        
        this.actualizarContador();
    }

    // Actualizar cantidad de un producto
    actualizarCantidad(productoId, nuevaCantidad) {
        const item = this.items.find(item => item.id === productoId);
        if (item) {
            if (nuevaCantidad > item.stock) {
                this.mostrarNotificacion('Stock máximo alcanzado', 'error');
                return false;
            } else if (nuevaCantidad <= 0) {
                this.eliminar(productoId);
                return true;
            }
            
            item.cantidad = nuevaCantidad;
            this.guardar();
            this.mostrarNotificacion('Cantidad actualizada', 'success');
            return true;
        }
        return false;
    }

    // Eliminar producto del carrito
    eliminar(productoId) {
        this.items = this.items.filter(item => item.id !== productoId);
        this.guardar();
        this.mostrarNotificacion('Producto eliminado del carrito', 'success');
        this.actualizarContador();
    }

    // Guardar carrito en localStorage
    guardar() {
        localStorage.setItem('carrito', JSON.stringify(this.items));
        this.actualizarContador();
        this.mostrarCarritoResumen();
    }

    // Actualizar contador del carrito
    actualizarContador() {
        const contador = this.items.reduce((total, item) => total + item.cantidad, 0);
        document.querySelector('.cart-count').textContent = contador;
    }

    // Mostrar notificación
    mostrarNotificacion(mensaje, tipo) {
        console.log('Mostrando notificación:', mensaje, tipo);
        
        // Contenedor principal de notificaciones
        let notificacionesContainer = document.getElementById('notificaciones-container');
        if (!notificacionesContainer) {
            notificacionesContainer = document.createElement('div');
            notificacionesContainer.id = 'notificaciones-container';
            notificacionesContainer.className = 'fixed top-4 right-4 z-50 flex flex-col gap-2';
            document.body.appendChild(notificacionesContainer);
        }

        // Crear elemento de notificación
        const notificacion = document.createElement('div');
        notificacion.className = `transform translate-x-full transition-all duration-300 ease-in-out`;
        notificacion.innerHTML = `
            <div class="flex items-center p-4 text-sm rounded-lg shadow-lg ${
                tipo === 'success' ? 'bg-green-800 text-green-400' : 'bg-red-800 text-red-400'
            }">
                <i class="fas ${tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${mensaje}</span>
            </div>`;

        // Agregar al contenedor
        notificacionesContainer.appendChild(notificacion);

        // Forzar un reflow para que la animación funcione
        notificacion.offsetHeight;

        // Animar entrada
        notificacion.style.transform = 'translateX(0)';

        // Eliminar después de 3 segundos
        setTimeout(() => {
            notificacion.style.transform = 'translateX(100%)';
            notificacion.style.opacity = '0';
            setTimeout(() => {
                notificacion.remove();
                if (notificacionesContainer.children.length === 0) {
                    notificacionesContainer.remove();
                }
            }, 300);
        }, 3000);
    }

    // Mostrar resumen del carrito
    mostrarCarritoResumen() {
        const total = this.items.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        
        // Crear o actualizar el modal del carrito
        let modalCarrito = document.getElementById('modal-carrito');
        if (!modalCarrito) {
            modalCarrito = document.createElement('div');
            modalCarrito.id = 'modal-carrito';
            modalCarrito.className = 'modal-carrito hidden fixed inset-0 bg-black bg-opacity-50 z-50';
            document.body.appendChild(modalCarrito);
        }

        modalCarrito.innerHTML = `
            <div class="modal-content bg-zinc-800 max-w-md mx-auto mt-20 rounded-lg shadow-xl">
                <div class="p-4 border-b border-zinc-700">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white">Carrito de Compras</h3>
                        <button onclick="cerrarModalCarrito()" class="text-gray-400 hover:text-white">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="p-4 max-h-96 overflow-y-auto">
                    ${this.items.length === 0 ? `
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-cart text-4xl text-gray-600 mb-4"></i>
                            <p class="text-gray-400">Tu carrito está vacío</p>
                        </div>
                    ` : `
                        ${this.items.map(item => `
                            <div class="flex items-center justify-between py-2 border-b border-zinc-700">
                                <div class="flex-1">
                                    <h4 class="text-white">${item.nombre}</h4>
                                    <p class="text-sm text-gray-400">$${item.precio} c/u</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button onclick="carrito.actualizarCantidad(${item.id}, ${item.cantidad - 1})" 
                                            class="px-2 py-1 bg-zinc-700 hover:bg-zinc-600 rounded">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="text-white">${item.cantidad}</span>
                                    <button onclick="carrito.actualizarCantidad(${item.id}, ${item.cantidad + 1})"
                                            class="px-2 py-1 bg-zinc-700 hover:bg-zinc-600 rounded"
                                            ${item.cantidad >= item.stock ? 'disabled' : ''}>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <button onclick="carrito.eliminar(${item.id})" 
                                            class="ml-2 px-2 py-1 text-red-400 hover:text-red-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `).join('')}
                        <div class="mt-4 pt-4 border-t border-zinc-700">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span class="text-white">Total:</span>
                                <span class="text-green-400">$${total.toFixed(2)}</span>
                            </div>
                        </div>
                    `}
                </div>
                ${this.items.length > 0 ? `
                    <div class="p-4 border-t border-zinc-700">
                        <button onclick="procesarPedido()" 
                                class="w-full py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold">
                            Procesar Pedido
                        </button>
                    </div>
                ` : ''}
            </div>
        `;
    }
}

// Inicializar carrito
const carrito = new Carrito();

// Función para agregar al carrito
function agregarAlCarrito(productoId) {
    // Mostrar mensaje de depuración
    console.log('Intentando agregar producto:', productoId);
    
    const productoElement = document.querySelector(`[data-producto-id="${productoId}"]`);
    console.log('Elemento producto encontrado:', productoElement);
    
    if (!productoElement) {
        console.error('No se encontró el elemento del producto');
        return;
    }

    const producto = {
        id: productoId,
        nombre: productoElement.dataset.nombre,
        precio: parseFloat(productoElement.dataset.precio),
        stock: parseInt(productoElement.dataset.stock)
    };
    
    console.log('Datos del producto:', producto);
    carrito.agregar(producto);
}

// Función para mostrar el modal del carrito
function mostrarCarrito() {
    document.getElementById('modal-carrito').classList.remove('hidden');
}

// Función para cerrar el modal del carrito
function cerrarModalCarrito() {
    document.getElementById('modal-carrito').classList.add('hidden');
}

// Función para procesar el pedido
function procesarPedido() {
    // Aquí implementaremos la lógica para procesar el pedido
    alert('Implementaremos el procesamiento del pedido próximamente');
}

// Inicializar todos los event listeners cuando el DOM esté listo
// Inicializar carrito cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM Cargado - Inicializando carrito...');
    
    // Crear la instancia global del carrito
    window.carrito = new Carrito();
    
    // Configurar el botón del carrito
    const cartBtn = document.getElementById('cart-button');
    if (cartBtn) {
        cartBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            window.carrito.mostrarCarritoResumen();
            mostrarCarrito();
        });
        console.log('Event listener del carrito agregado');
    } else {
        console.error('No se encontró el botón del carrito');
    }

    // Configurar cierre del modal al hacer clic fuera
    document.addEventListener('click', (e) => {
        const modalCarrito = document.getElementById('modal-carrito');
        if (modalCarrito && !modalCarrito.classList.contains('hidden') && e.target.closest('.modal-content') === null) {
            cerrarModalCarrito();
        }
    });

    // Prevenir que los clics dentro del modal lo cierren
    document.addEventListener('click', (e) => {
        if (e.target.closest('.modal-content')) {
            e.stopPropagation();
        }
    });

    console.log('Inicialización completada');
});
</script>

<style>
/* Estilos para el carrito y notificaciones */
.cart-btn {
    @apply relative bg-green-600 hover:bg-green-700 text-white p-3 rounded-full transition-all duration-300 ease-in-out;
    width: 48px;
    height: 48px;
}

.cart-count {
    @apply absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center;
    min-width: 20px;
    height: 20px;
    padding: 2px 6px;
}

.filter-btn {
    @apply px-4 py-2 rounded-lg bg-zinc-800 text-gray-300 hover:bg-green-600 hover:text-white transition-all duration-300 flex items-center gap-2 border border-zinc-700;
}

.filter-btn.active {
    @apply bg-green-600 text-white border-green-500;
}

/* Estilos para las notificaciones */
#notificaciones-container {
    @apply fixed top-4 right-4 z-50 flex flex-col gap-2;
}

#notificaciones-container > div {
    @apply shadow-lg rounded-lg overflow-hidden;
    min-width: 300px;
    max-width: 400px;
}

/* Modal del carrito */
.modal-carrito {
    @apply fixed inset-0 flex items-center justify-center z-[100];
    background-color: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(4px);
}

.modal-content {
    @apply bg-zinc-800 rounded-xl shadow-2xl w-full mx-4 transform transition-all duration-300 ease-out;
    max-width: 500px;
    max-height: 80vh;
    animation: modalSlideIn 0.3s ease-out;
}

.modal-header {
    @apply p-6 border-b border-zinc-700 flex justify-between items-center bg-zinc-800/95 backdrop-blur sticky top-0 z-10;
}

.modal-header h3 {
    @apply text-2xl font-bold text-green-400;
}

.modal-header button {
    @apply w-8 h-8 flex items-center justify-center rounded-full hover:bg-zinc-700 transition-colors;
}

.modal-body {
    @apply p-6 overflow-y-auto;
    max-height: calc(80vh - 180px);
}

.cart-item {
    @apply flex items-center justify-between py-4 border-b border-zinc-700 last:border-0;
}

.cart-item-info {
    @apply flex-1 mr-4;
}

.cart-item-title {
    @apply text-white font-medium text-lg mb-1;
}

.cart-item-price {
    @apply text-sm text-green-400 font-medium;
}

.cart-item-controls {
    @apply flex items-center gap-3;
}

.quantity-btn {
    @apply w-8 h-8 rounded-lg bg-zinc-700 hover:bg-zinc-600 text-white transition-colors flex items-center justify-center;
}

.quantity-btn[disabled] {
    @apply opacity-50 cursor-not-allowed hover:bg-zinc-700;
}

.delete-btn {
    @apply w-8 h-8 rounded-lg flex items-center justify-center text-red-400 hover:bg-red-400/10 hover:text-red-300 transition-colors;
}

/* Animaciones */
@keyframes modalSlideIn {
    from {
        transform: scale(0.95) translateY(10px);
        opacity: 0;
    }
    to {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}

@keyframes modalSlideOut {
    from {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
    to {
        transform: scale(0.95) translateY(10px);
        opacity: 0;
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}

@keyframes slideInRight {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.modal-carrito {
    animation: fadeIn 0.2s ease-out;
}

.modal-carrito.hiding {
    animation: fadeOut 0.2s ease-out forwards;
}

.modal-carrito.hiding .modal-content {
    animation: modalSlideOut 0.2s ease-out forwards;
}

.notification-slide {
    animation: slideInRight 0.3s ease-out forwards;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Cargado - Inicializando carrito...');

    // Clase Carrito mejorada
    class Carrito {
        constructor() {
            console.log('Inicializando carrito');
            this.items = JSON.parse(localStorage.getItem('carrito')) || [];
            this.actualizarContador();
            this.setupEventListeners();
        }

        setupEventListeners() {
            // Configurar el botón del carrito
            const cartBtn = document.getElementById('cartBtn');
            if (cartBtn) {
                cartBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.mostrarCarritoResumen();
                });
                console.log('Event listener del carrito configurado');
            }
        }

        agregar(producto) {
            console.log('Agregando producto:', producto);
            const itemExistente = this.items.find(item => item.id === producto.id);
            
            if (itemExistente) {
                if (itemExistente.cantidad < producto.stock) {
                    itemExistente.cantidad++;
                    this.guardar();
                    this.mostrarNotificacion('Cantidad actualizada en el carrito', 'success');
                } else {
                    this.mostrarNotificacion('Stock máximo alcanzado', 'error');
                }
            } else {
                this.items.push({
                    id: producto.id,
                    nombre: producto.nombre,
                    precio: producto.precio,
                    cantidad: 1,
                    stock: producto.stock
                });
                this.guardar();
                this.mostrarNotificacion('Producto agregado al carrito', 'success');
            }
        }

        actualizarCantidad(productoId, nuevaCantidad) {
            const item = this.items.find(item => item.id === productoId);
            if (item) {
                if (nuevaCantidad > item.stock) {
                    this.mostrarNotificacion('Stock máximo alcanzado', 'error');
                    return;
                }
                if (nuevaCantidad <= 0) {
                    this.eliminar(productoId);
                    return;
                }
                item.cantidad = nuevaCantidad;
                this.guardar();
                this.mostrarCarritoResumen();
            }
        }

        eliminar(productoId) {
            this.items = this.items.filter(item => item.id !== productoId);
            this.guardar();
            this.mostrarNotificacion('Producto eliminado del carrito', 'success');
            this.mostrarCarritoResumen();
        }

        guardar() {
            localStorage.setItem('carrito', JSON.stringify(this.items));
            this.actualizarContador();
        }

        actualizarContador() {
            const contador = this.items.reduce((total, item) => total + item.cantidad, 0);
            const contadorElement = document.querySelector('.cart-count');
            if (contadorElement) {
                contadorElement.textContent = contador;
            }
        }

        mostrarNotificacion(mensaje, tipo) {
            const notificacion = document.createElement('div');
            notificacion.className = 'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300';
            notificacion.style.backgroundColor = tipo === 'success' ? '#065f46' : '#991b1b';
            notificacion.style.color = '#fff';
            notificacion.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${mensaje}</span>
                </div>
            `;
            
            document.body.appendChild(notificacion);
            
            // Animar entrada
            setTimeout(() => {
                notificacion.style.transform = 'translateX(0)';
                notificacion.style.opacity = '1';
            }, 100);

            // Eliminar después de 3 segundos
            setTimeout(() => {
                notificacion.style.transform = 'translateX(100%)';
                notificacion.style.opacity = '0';
                setTimeout(() => notificacion.remove(), 300);
            }, 3000);
        }

        mostrarCarritoResumen() {
            console.log('Mostrando carrito resumen');
            let modalCarrito = document.getElementById('modal-carrito');
            
            if (!modalCarrito) {
                modalCarrito = document.createElement('div');
                modalCarrito.id = 'modal-carrito';
                modalCarrito.className = 'modal-carrito hidden';
                document.body.appendChild(modalCarrito);
            }

            const total = this.items.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
            
            modalCarrito.innerHTML = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="text-xl font-bold text-white">Tu Carrito</h3>
                        <button onclick="cerrarModalCarrito()" class="text-gray-400 hover:text-white">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        ${this.items.length === 0 ? `
                            <div class="text-center py-8">
                                <i class="fas fa-shopping-cart text-4xl text-gray-600 mb-4"></i>
                                <p class="text-gray-400">Tu carrito está vacío</p>
                            </div>
                        ` : `
                            ${this.items.map(item => `
                                <div class="cart-item">
                                    <div class="cart-item-info">
                                        <h4 class="cart-item-title">${item.nombre}</h4>
                                        <p class="cart-item-price">$${item.precio.toFixed(2)} c/u</p>
                                    </div>
                                    <div class="cart-item-controls">
                                        <button onclick="window.carrito.actualizarCantidad(${item.id}, ${item.cantidad - 1})" 
                                                class="quantity-btn">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <span class="text-white px-2">${item.cantidad}</span>
                                        <button onclick="window.carrito.actualizarCantidad(${item.id}, ${item.cantidad + 1})"
                                                class="quantity-btn"
                                                ${item.cantidad >= item.stock ? 'disabled' : ''}>
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button onclick="window.carrito.eliminar(${item.id})" 
                                                class="delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `).join('')}
                            <div class="mt-4 pt-4 border-t border-zinc-700">
                                <div class="flex justify-between items-center text-lg font-bold">
                                    <span class="text-white">Total:</span>
                                    <span class="text-green-400">$${total.toFixed(2)}</span>
                                </div>
                            </div>
                        `}
                    </div>
                    ${this.items.length > 0 ? `
                        <div class="p-4 border-t border-zinc-700">
                            <button onclick="procesarPedido()" 
                                    class="w-full py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold transition-colors">
                                Procesar Pedido
                            </button>
                        </div>
                    ` : ''}
                </div>
            `;

            modalCarrito.classList.remove('hidden');
        }
    }

    // Inicializar carrito global
    window.carrito = new Carrito();

    // Función para procesar el pedido
    window.procesarPedido = function() {
        alert('Implementaremos el procesamiento del pedido próximamente');
    };

    // Función para cerrar el modal del carrito
    window.cerrarModalCarrito = function() {
        const modalCarrito = document.getElementById('modal-carrito');
        if (modalCarrito) {
            modalCarrito.classList.add('hiding');
            setTimeout(() => {
                modalCarrito.classList.remove('hiding');
                modalCarrito.classList.add('hidden');
            }, 200);
        }
    };

    // Delegación de eventos para cerrar el modal al hacer clic fuera
    document.addEventListener('click', function(e) {
        const modalCarrito = document.getElementById('modal-carrito');
        if (modalCarrito && !modalCarrito.classList.contains('hidden')) {
            const modalContent = e.target.closest('.modal-content');
            if (!modalContent && e.target !== document.getElementById('cartBtn')) {
                cerrarModalCarrito();
            }
        }
    });

    console.log('Inicialización del carrito completada');
});
</script>
@endsection