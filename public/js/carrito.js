class Carrito {
    constructor() {
        console.log('Inicializando carrito');
        this.items = JSON.parse(localStorage.getItem('carrito')) || [];
        this.actualizarContador();
        this.setupEventListeners();
    }

    setupEventListeners() {
        const cartBtn = document.getElementById('cart-toggle');
        if (cartBtn) {
            cartBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.mostrarCarritoResumen();
            });
            console.log('Event listener del carrito configurado');
        }

        // Agregar listener para cerrar modal al hacer clic fuera
        document.addEventListener('click', (e) => {
            const modalCarrito = document.getElementById('modal-carrito');
            if (modalCarrito && !modalCarrito.classList.contains('hidden') && !e.target.closest('.modal-content')) {
                this.cerrarModal();
            }
        });
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
        const contadorElement = document.querySelector('#cart-count');
        if (contadorElement) {
            contadorElement.textContent = contador;
        }
    }

    mostrarNotificacion(mensaje, tipo) {
        let container = document.getElementById('notificaciones-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notificaciones-container';
            container.className = 'fixed top-4 right-4 z-50 flex flex-col gap-2';
            document.body.appendChild(container);
        }

        const notificacion = document.createElement('div');
        notificacion.className = 'transform translate-x-full transition-all duration-300';
        notificacion.innerHTML = `
            <div class="flex items-center p-4 text-sm rounded-lg shadow-lg ${
                tipo === 'success' ? 'bg-green-800 text-green-400' : 'bg-red-800 text-red-400'
            }">
                <i class="fas ${tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${mensaje}</span>
            </div>`;

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
            modalCarrito.className = 'modal-carrito hidden fixed inset-0 bg-black bg-opacity-50 z-50';
            document.body.appendChild(modalCarrito);
        }

        const total = this.items.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);
        
        modalCarrito.innerHTML = `
            <div class="modal-content bg-zinc-800 max-w-md mx-auto mt-20 rounded-lg shadow-xl">
                <div class="p-4 border-b border-zinc-700">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white">Tu Carrito</h3>
                        <button onclick="carrito.cerrarModal()" class="text-gray-400 hover:text-white">
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
                                    <p class="text-sm text-gray-400">$${item.precio.toFixed(2)} c/u</p>
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
                        <a href="/carrito" class="w-full py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-bold text-center block">
                            Ver Carrito Detallado
                        </a>
                    </div>
                ` : ''}
            </div>
        `;

        modalCarrito.classList.remove('hidden');
    }

    cerrarModal() {
        const modalCarrito = document.getElementById('modal-carrito');
        if (modalCarrito) {
            modalCarrito.classList.add('hidden');
            setTimeout(() => {
                if (modalCarrito.classList.contains('hidden')) {
                    modalCarrito.remove();
                }
            }, 300);
        }
    }

    procesarPedido() {
        window.location.href = '/procesar-pedido';
    }
}

// Inicializar carrito cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    if (!window.carrito) {
        window.carrito = new Carrito();
    }
});

// Función global para agregar al carrito
window.agregarAlCarrito = function(productoId) {
    const productoElement = document.querySelector(`[data-producto-id="${productoId}"]`);
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
    
    window.carrito.agregar(producto);
};