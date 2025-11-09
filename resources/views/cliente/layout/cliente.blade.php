<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DÉSOLÉ - Cafetería Nocturna')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/desole.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="icon" href="{{ asset('assets/favicon.ico') }}">
    <style>
    /* Estilos del carrito */
    .cart-btn {
        @apply relative bg-green-600 hover:bg-green-700 text-white p-3 rounded-full transition-all duration-300 ease-in-out;
        width: 48px;
        height: 48px;
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

    /* Estilos para el modal del carrito */
    .modal-carrito {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 50;
        display: flex;
        align-items: start;
        justify-content: center;
        padding-top: 2.5rem;
    }

    .modal-content {
        background: #27272a;
        border-radius: 0.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 32rem;
        width: 100%;
        margin: 0 auto;
        overflow: hidden;
    }

    .modal-header {
        padding: 1rem;
        border-bottom: 1px solid #3f3f46;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        padding: 1rem;
        max-height: 80vh;
        overflow-y: auto;
    }

    .cart-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #3f3f46;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .quantity-btn {
        padding: 0.25rem;
        border-radius: 0.25rem;
        background: #3f3f46;
        color: white;
        transition: background-color 0.2s;
    }

    .quantity-btn:hover:not(:disabled) {
        background: #52525b;
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Notificaciones */
    #notificaciones-container {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 50;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .notification-slide {
        animation: slideInRight 0.3s ease-out forwards;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $isProfilePage = true; // Esto hará que el navbar muestre las opciones de cliente
    @endphp
    
    <!-- Navbar Específico para Cliente -->
    @include('public.secciones._navbar', ['isProfilePage' => true])

    <main class="cliente-main">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/carrito.js') }}"></script>
    <script>
            window.Carrito = class {
            constructor() {
                console.log('Inicializando carrito');
                this.items = JSON.parse(localStorage.getItem('carrito')) || [];
                this.actualizarContador();
                this.setupEventListeners();
            }

            setupEventListeners() {
                const cartBtn = document.getElementById('cartBtn');
                if (cartBtn) {
                    cartBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.mostrarCarritoResumen();
                    });
                }
            }

            agregar(producto) {
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
                let container = document.getElementById('notificaciones-container');
                if (!container) {
                    container = document.createElement('div');
                    container.id = 'notificaciones-container';
                    document.body.appendChild(container);
                }

                const notificacion = document.createElement('div');
                notificacion.className = `rounded-lg shadow-lg overflow-hidden transform translate-x-full transition-all duration-300`;
                notificacion.style.backgroundColor = tipo === 'success' ? '#065f46' : '#991b1b';
                notificacion.innerHTML = `
                    <div class="p-4 text-white">
                        <div class="flex items-center">
                            <i class="fas ${tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                            <span>${mensaje}</span>
                        </div>
                    </div>
                `;
                
                container.appendChild(notificacion);
                
                // Forzar un reflow
                notificacion.offsetHeight;
                
                // Animar entrada
                notificacion.style.transform = 'translateX(0)';
                
                // Eliminar después de 3 segundos
                setTimeout(() => {
                    notificacion.style.transform = 'translateX(100%)';
                    notificacion.style.opacity = '0';
                    setTimeout(() => {
                        notificacion.remove();
                        if (container.children.length === 0) {
                            container.remove();
                        }
                    }, 300);
                }, 3000);
            }

            mostrarCarritoResumen() {
                let modalCarrito = document.getElementById('modal-carrito');
                if (!modalCarrito) {
                    modalCarrito = document.createElement('div');
                    modalCarrito.id = 'modal-carrito';
                    modalCarrito.className = 'modal-carrito';
                    document.body.appendChild(modalCarrito);
                }

                const total = this.items.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
                
                modalCarrito.innerHTML = `
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="text-xl font-bold text-white">Tu Carrito</h3>
                            <button onclick="window.carrito.cerrarModal()" class="text-gray-400 hover:text-white">
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
                                        <div class="flex-1">
                                            <h4 class="text-white font-medium">${item.nombre}</h4>
                                            <p class="text-sm text-gray-400">$${item.precio.toFixed(2)} c/u</p>
                                        </div>
                                        <div class="flex items-center gap-2">
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
                                                    class="ml-2 text-red-400 hover:text-red-300">
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
                                <button onclick="window.carrito.procesarPedido()" 
                                        class="w-full py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold transition-colors">
                                    Procesar Pedido
                                </button>
                            </div>
                        ` : ''}
                    </div>
                `;

                modalCarrito.classList.remove('hidden');

                // Configurar cierre al hacer clic fuera
                modalCarrito.addEventListener('click', (e) => {
                    if (e.target === modalCarrito) {
                        this.cerrarModal();
                    }
                });
            }

            cerrarModal() {
                const modalCarrito = document.getElementById('modal-carrito');
                if (modalCarrito) {
                    modalCarrito.classList.add('hidden');
                    setTimeout(() => modalCarrito.remove(), 300);
                }
            }

            procesarPedido() {
                // Implementar lógica de procesamiento de pedido
                alert('Implementaremos el procesamiento del pedido próximamente');
            }
        };
        

        // Inicializar carrito cuando el DOM esté listo (solo si no existe)
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Inicializando carrito...');
            if (!window.carrito) {
                window.carrito = new window.Carrito();
            }
        });

        // Función global para agregar al carrito
        window.agregarAlCarrito = function(productoId) {
            const productoElement = document.querySelector(`[data-producto-id="${productoId}"]`);
            if (!productoElement) return;

            const producto = {
                id: productoId,
                nombre: productoElement.dataset.nombre,
                precio: parseFloat(productoElement.dataset.precio),
                stock: parseInt(productoElement.dataset.stock)
            };
            
            window.carrito.agregar(producto);
        };
    </script>
    @stack('scripts')
</body>
</html>