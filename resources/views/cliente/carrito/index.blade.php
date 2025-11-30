@extends('cliente.layout.cliente')

@section('title', 'Carrito de Compras - DÉSOLÉ')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-600 mb-2">
                🛒 Mi Carrito
            </h1>
            <p class="text-gray-400">Revisa tus productos antes de finalizar la compra</p>
        </div>

        <div class="bg-zinc-800/80 backdrop-blur-md rounded-2xl border border-zinc-700/50 overflow-hidden shadow-xl">
            <div class="p-6 md:p-8">
                <div id="carrito-detallado" class="space-y-6">
                    <!-- El contenido del carrito se cargará dinámicamente aquí -->
                    <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                        <i class="fas fa-spinner fa-spin text-3xl mb-3"></i>
                        <p>Cargando carrito...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/* ==============================
   SISTEMA DE CARRITO FUNCIONAL
   ============================== */

window.carritoView = {
    obtener() {
        return JSON.parse(localStorage.getItem('carrito')) || [];
    },
    guardar(items) {
        localStorage.setItem('carrito', JSON.stringify(items));
    },
    eliminar(productoId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "El producto será eliminado del carrito",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#3f3f46',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: '#27272a',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                const items = this.obtener().filter(p => p.id !== productoId);
                this.guardar(items);
                actualizarVistaCarrito();
                
                // Notificación discreta de éxito
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#27272a',
                    color: '#fff'
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Producto eliminado'
                });
            }
        });
    },
    actualizarCantidad(productoId, nuevaCantidad) {
        const items = this.obtener().map(p => {
            if (p.id === productoId) {
                p.cantidad = Math.min(Math.max(1, nuevaCantidad), p.stock);
            }
            return p;
        });
        this.guardar(items);
        actualizarVistaCarrito();
    },
    limpiar() {
        localStorage.removeItem('carrito');
        actualizarVistaCarrito();
    }
};

document.addEventListener('DOMContentLoaded', actualizarVistaCarrito);

function actualizarVistaCarrito() {
    const carritoContainer = document.getElementById('carrito-detallado');
    const items = window.carritoView.obtener();

    if (items.length === 0) {
        carritoContainer.innerHTML = `
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-zinc-700/50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-cart text-4xl text-gray-500"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Tu carrito está vacío</h2>
                <p class="text-gray-400 mb-8">¡Agrega algunos productos deliciosos para empezar!</p>
                <a href="{{ route('cliente.menu') }}" class="inline-flex items-center px-6 py-3 bg-zinc-700 hover:bg-zinc-600 text-white rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Ir al Menú
                </a>
            </div>
        `;
        return;
    }

    const total = items.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);

    const baseUrl = (window.APP_URL || '').replace(/\/$/, '');

    carritoContainer.innerHTML = `
        <div class="space-y-4">
            ${items.map(item => `
                <div class="flex flex-col md:flex-row items-center gap-4 bg-zinc-700/20 p-4 rounded-xl border border-zinc-700/30 hover:bg-zinc-700/40 transition-all duration-300 group" data-producto-id="${item.id}">
                    <!-- Imagen -->
                    <div class="relative flex-shrink-0 w-full md:w-auto flex justify-center md:justify-start">
                        <img src="${item.imagen ? (item.imagen.startsWith('http') ? item.imagen : baseUrl + '/' + item.imagen.replace(/^\//, '')) : baseUrl + '/assets/placeholder.svg'}"
                             alt="${item.nombre}"
                             class="w-24 h-24 object-cover rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Info -->
                    <div class="flex-1 text-center md:text-left w-full">
                        <h3 class="text-lg font-bold text-white mb-1">${item.nombre}</h3>
                        <p class="text-green-400 font-medium">$${item.precio.toFixed(2)} c/u</p>
                        <p class="text-gray-500 text-xs mt-1">Stock disponible: ${item.stock || 'N/A'}</p>
                    </div>

                    <!-- Controles -->
                    <div class="flex items-center gap-3 bg-zinc-800/50 p-1 rounded-lg border border-zinc-700/50">
                        <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white hover:bg-zinc-700 rounded-md transition-colors" 
                                onclick="window.carritoView.actualizarCantidad(${item.id}, ${item.cantidad - 1})"
                                ${item.cantidad <= 1 ? 'disabled class="opacity-50 cursor-not-allowed w-8 h-8 flex items-center justify-center text-gray-600"' : ''}>
                            <i class="fas fa-minus text-xs"></i>
                        </button>

                        <input type="number"
                               value="${item.cantidad}"
                               min="1"
                               max="${item.stock || 99}"
                               class="w-12 text-center bg-transparent text-white font-bold focus:outline-none border-none p-0"
                               onchange="window.carritoView.actualizarCantidad(${item.id}, parseInt(this.value))">

                        <button class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white hover:bg-zinc-700 rounded-md transition-colors" 
                                onclick="window.carritoView.actualizarCantidad(${item.id}, ${item.cantidad + 1})"
                                ${item.cantidad >= (item.stock || 99) ? 'disabled class="opacity-50 cursor-not-allowed w-8 h-8 flex items-center justify-center text-gray-600"' : ''}>
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                    </div>

                    <!-- Subtotal & Eliminar -->
                    <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 border-zinc-700/50 pt-4 md:pt-0 mt-2 md:mt-0">
                        <div class="text-right md:min-w-[100px]">
                            <p class="text-xs text-gray-500 mb-1">Subtotal</p>
                            <p class="text-xl font-bold text-white">$${(item.precio * item.cantidad).toFixed(2)}</p>
                        </div>
                        
                        <button class="w-10 h-10 flex items-center justify-center text-red-400 hover:text-red-300 hover:bg-red-400/10 rounded-lg transition-all duration-300" 
                                onclick="window.carritoView.eliminar(${item.id})"
                                title="Eliminar producto">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            `).join('')}
        </div>

        <!-- Resumen Total -->
        <div class="mt-8 pt-8 border-t border-zinc-700/50">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <a href="${baseUrl}/cliente/menu" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2 group">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Seguir Comprando
                </a>

                <div class="flex flex-col md:flex-row items-center gap-6 w-full md:w-auto">
                    <div class="text-center md:text-right">
                        <p class="text-gray-400 text-sm">Total a Pagar</p>
                        <p class="text-3xl font-bold text-green-400">$${total.toFixed(2)}</p>
                    </div>

                    <a href="${baseUrl}/cliente/carrito/checkout" class="w-full md:w-auto bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg shadow-green-500/20 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 group">
                        <span>Finalizar Compra</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    `;
}

// Nota: La función procesarPedido ya no se usa aquí porque el botón redirige a checkout,
// pero la mantenemos por si acaso se necesita lógica futura o compatibilidad.
function procesarPedido() {
    // Redirigir a checkout
    window.location.href = "{{ route('cliente.carrito.checkout') }}";
}
</script>
@endsection
