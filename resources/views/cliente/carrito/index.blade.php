@extends('cliente.layout.cliente')

@section('title', 'Carrito de Compras - DÉSOLÉ')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-5xl mx-auto bg-zinc-800 rounded-2xl shadow-xl overflow-hidden border border-zinc-700">
        <div class="p-8">
            <h1 class="text-4xl font-bold text-green-400 mb-8 text-center">
                🛒 Mi Carrito
            </h1>

            <div id="carrito-detallado" class="space-y-6">
                <!-- El contenido del carrito se cargará dinámicamente aquí -->
            </div>
        </div>
    </div>
</div>

<style>
.cart-item {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid #3f3f46;
    border-radius: 0.75rem;
    background: rgba(39, 39, 42, 0.6);
    transition: transform 0.2s ease;
}
.cart-item:hover { transform: translateY(-3px); }
.cart-item-image {
    width: 90px; height: 90px; object-fit: cover;
    border-radius: 0.5rem; border: 1px solid #3f3f46;
}
.cart-item-info { flex: 1; min-width: 200px; }
.cart-item-title { font-size: 1.125rem; font-weight: 600; color: white; }
.cart-item-price { color: #65cf72; font-weight: 500; }
.cart-item-stock { color: #a1a1aa; font-size: 0.875rem; }
.cart-item-controls { display: flex; align-items: center; gap: 0.5rem; }
.btn-cantidad {
    padding: 0.4rem 0.8rem;
    background: #3f3f46;
    color: white;
    border: 1px solid #52525b;
    border-radius: 0.375rem;
    transition: all 0.2s;
}
.btn-cantidad:hover:not(:disabled) { background: #52525b; }
.cantidad-input {
    width: 60px;
    text-align: center;
    background: #3f3f46;
    color: white;
    border: 1px solid #52525b;
    border-radius: 0.375rem;
    padding: 0.25rem;
}
.btn-eliminar { padding: 0.5rem; color: #ef4444; transition: all 0.2s; }
.btn-eliminar:hover { color: #dc2626; }
.cart-summary { border-top: 1px solid #3f3f46; padding-top: 1.5rem; margin-top: 1.5rem; }
.cart-total {
    display: flex;
    justify-content: space-between;
    font-size: 1.25rem;
    font-weight: 600;
    color: white;
    margin-bottom: 1rem;
}
.cart-total-amount { color: #65cf72; }
.btn-procesar {
    width: 100%;
    padding: 1rem;
    background: #65cf72;
    color: white;
    font-weight: 600;
    text-align: center;
    border-radius: 0.5rem;
    transition: all 0.2s;
}
.btn-procesar:hover { background: #4fa85a; }
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
.btn-seguir-comprando:hover { background: #52525b; }
.empty-cart { text-align: center; padding: 3rem; }
.empty-cart i { font-size: 4rem; color: #52525b; margin-bottom: 1rem; }
</style>

<script>
/* ==============================
   SISTEMA DE CARRITO FUNCIONAL
   ============================== */

window.carrito = {
    obtener() {
        return JSON.parse(localStorage.getItem('carrito')) || [];
    },
    guardar(items) {
        localStorage.setItem('carrito', JSON.stringify(items));
    },
    eliminar(productoId) {
        const items = this.obtener().filter(p => p.id !== productoId);
        this.guardar(items);
        actualizarVistaCarrito();
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
    const items = window.carrito.obtener();

    if (items.length === 0) {
        carritoContainer.innerHTML = `
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <p class="text-gray-400 text-lg mb-4">Tu carrito está vacío</p>
                <a href="{{ route('cliente.menu') }}" class="btn-seguir-comprando">
                    <i class="fas fa-arrow-left mr-2"></i> Seguir Comprando
                </a>
            </div>
        `;
        return;
    }

    const total = items.reduce((sum, item) => sum + (item.precio * item.cantidad), 0);

    carritoContainer.innerHTML = `
        ${items.map(item => `
            <div class="cart-item" data-producto-id="${item.id}">
                <img src="${item.imagen ? '/storage/' + item.imagen : '{{ asset('assets/placeholder.png') }}'}"
                     alt="${item.nombre}"
                     class="cart-item-image">

                <div class="cart-item-info">
                    <h3 class="cart-item-title">${item.nombre}</h3>
                    <p class="cart-item-price">$${item.precio.toFixed(2)} c/u</p>
                    <p class="cart-item-stock">Stock disponible: ${item.stock}</p>
                </div>

                <div class="cart-item-controls">
                    <button class="btn-cantidad" onclick="window.carrito.actualizarCantidad(${item.id}, ${item.cantidad - 1})"
                            ${item.cantidad <= 1 ? 'disabled' : ''}>
                        <i class="fas fa-minus"></i>
                    </button>

                    <input type="number"
                           value="${item.cantidad}"
                           min="1"
                           max="${item.stock}"
                           class="cantidad-input"
                           onchange="window.carrito.actualizarCantidad(${item.id}, parseInt(this.value))">

                    <button class="btn-cantidad" onclick="window.carrito.actualizarCantidad(${item.id}, ${item.cantidad + 1})"
                            ${item.cantidad >= item.stock ? 'disabled' : ''}>
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <button class="btn-eliminar" onclick="window.carrito.eliminar(${item.id})">
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
                Finalizar Compra
            </button>

            <a href="{{ route('cliente.menu') }}" class="btn-seguir-comprando">
                <i class="fas fa-arrow-left mr-2"></i> Seguir Comprando
            </a>
        </div>
    `;
}

function procesarPedido() {
    console.log('🔵 Iniciando procesarPedido()');

    const items = window.carrito.obtener();
    console.log('🔵 Items en carrito:', items);

    if (items.length === 0) {
        console.warn('⚠️ Carrito vacío');
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Carrito Vacío',
                text: 'Agrega algunos productos antes de procesar el pedido.'
            });
        } else {
            alert('Tu carrito está vacío');
        }
        return;
    }

    console.log('📤 Enviando fetch a: {{ route('cliente.carrito.api.finalizar') }}');

    fetch("{{ route('api.finalizar') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ carrito: items })
    })
    .then(res => {
        console.log('📥 Respuesta recibida, status:', res.status);
        console.log('📥 Content-Type:', res.headers.get('content-type'));
        return res.text().then(text => {
            console.log('📥 Body (texto):', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('❌ Error al parsear JSON:', e);
                throw new Error('Respuesta no es JSON válido: ' + text.substring(0, 100));
            }
        });
    })
    .then(data => {
        console.log('✅ Datos parseados:', data);
        if (data.success) {
            console.log('✅ Éxito! Pedido creado:', data.pedido_id);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Pedido realizado!',
                    text: 'Tu pedido se ha creado exitosamente.',
                    confirmButtonColor: '#65cf72'
                }).then(() => {
                    window.carrito.limpiar();
                    window.location.href = "{{ route('cliente.pedidos.index') }}";
                });
            } else {
                alert('¡Pedido realizado exitosamente!');
                window.carrito.limpiar();
                window.location.href = "{{ route('cliente.pedidos.index') }}";
            }
        } else {
            console.error('❌ Error en la respuesta:', data.message);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'No se pudo procesar tu pedido.'
                });
            } else {
                alert('Error: ' + (data.message || 'No se pudo procesar tu pedido.'));
            }
        }
    })
    .catch(error => {
        console.error('❌ Error en fetch:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error del servidor',
                text: error.message || 'Inténtalo nuevamente más tarde.'
            });
        } else {
            alert('Error del servidor: ' + error.message);
        }
    });
}
</script>
@endsection
