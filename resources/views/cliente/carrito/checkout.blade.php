@extends('cliente.layout.cliente')

@section('title', 'Finalizar Compra - DÉSOLÉ')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-green-400 to-emerald-600 mb-2">
                Finalizar Compra
            </h1>
            <p class="text-gray-400">Completa tu pedido seleccionando un método de pago</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Columna Izquierda: Resumen del Pedido y Tipo de Entrega -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Resumen del Pedido -->
                <div class="bg-zinc-800/80 backdrop-blur-md rounded-2xl border border-zinc-700/50 overflow-hidden shadow-xl">
                    <div class="p-6 border-b border-zinc-700/50">
                        <h2 class="text-xl text-white font-semibold flex items-center gap-2">
                            <i class="fas fa-shopping-bag text-green-400"></i> Resumen del Pedido
                        </h2>
                    </div>
                    
                    <div id="checkout-items" class="p-6 space-y-4 max-h-[500px] overflow-y-auto custom-scrollbar">
                        <!-- Items cargados vía JS -->
                        <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                            <i class="fas fa-spinner fa-spin text-3xl mb-3"></i>
                            <p>Cargando detalles...</p>
                        </div>
                    </div>

                    <div class="bg-zinc-900/50 p-6 border-t border-zinc-700/50">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-gray-400 text-sm mb-1">Total a pagar</p>
                                <p class="text-sm text-gray-500" id="checkout-count">0 productos</p>
                            </div>
                            <div class="text-right">
                                <span class="text-3xl font-bold text-green-400" id="checkout-total">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tipo de Entrega (Movido aquí) -->
                <div class="bg-zinc-800/80 backdrop-blur-md rounded-2xl border border-zinc-700/50 overflow-hidden shadow-xl">
                    <div class="p-6 border-b border-zinc-700/50">
                        <h2 class="text-xl text-white font-semibold flex items-center gap-2">
                            <i class="fas fa-truck text-yellow-400"></i> Tipo de Entrega
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Opción Envío a Domicilio -->
                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="tipo_entrega" value="domicilio" class="peer sr-only" checked>
                                <div class="p-4 rounded-xl border border-zinc-600 bg-zinc-700/30 hover:bg-zinc-700/50 transition-all duration-300 peer-checked:border-yellow-500 peer-checked:bg-gradient-to-r peer-checked:from-yellow-500/10 peer-checked:to-orange-500/10 peer-checked:shadow-[0_0_20px_rgba(234,179,8,0.15)] transform peer-checked:scale-[1.02]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-zinc-800 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300 peer-checked:bg-yellow-500 peer-checked:text-white">
                                            <i class="fas fa-motorcycle text-lg text-yellow-400 peer-checked:text-white transition-colors"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-white font-bold text-sm group-hover:text-yellow-400 transition-colors">A Domicilio</h3>
                                            <p class="text-gray-400 text-xs">Te lo llevamos</p>
                                        </div>
                                        <div class="w-5 h-5 rounded-full border-2 border-zinc-500 peer-checked:border-yellow-500 peer-checked:bg-yellow-500 flex items-center justify-center transition-all duration-300 shadow-inner">
                                            <i class="fas fa-check text-white text-[10px] opacity-0 peer-checked:opacity-100 transform scale-0 peer-checked:scale-100 transition-all"></i>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- Opción Recoger en Tienda -->
                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="tipo_entrega" value="tienda" class="peer sr-only">
                                <div class="p-4 rounded-xl border border-zinc-600 bg-zinc-700/30 hover:bg-zinc-700/50 transition-all duration-300 peer-checked:border-yellow-500 peer-checked:bg-gradient-to-r peer-checked:from-yellow-500/10 peer-checked:to-orange-500/10 peer-checked:shadow-[0_0_20px_rgba(234,179,8,0.15)] transform peer-checked:scale-[1.02]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-zinc-800 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300 peer-checked:bg-yellow-500 peer-checked:text-white">
                                            <i class="fas fa-store text-lg text-yellow-400 peer-checked:text-white transition-colors"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-white font-bold text-sm group-hover:text-yellow-400 transition-colors">Recoger</h3>
                                            <p class="text-gray-400 text-xs">Pasa por él</p>
                                        </div>
                                        <div class="w-5 h-5 rounded-full border-2 border-zinc-500 peer-checked:border-yellow-500 peer-checked:bg-yellow-500 flex items-center justify-center transition-all duration-300 shadow-inner">
                                            <i class="fas fa-check text-white text-[10px] opacity-0 peer-checked:opacity-100 transform scale-0 peer-checked:scale-100 transition-all"></i>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Formulario de Dirección (Oculto por defecto) -->
                        <div id="delivery-details" class="mt-6 pt-6 border-t border-zinc-700/50 animate-fade-in-down">
                            <h3 class="text-white font-semibold mb-4 flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-red-400"></i> Datos de Envío
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Dirección Completa</label>
                                    <input type="text" id="delivery_address" class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 transition-colors" placeholder="Calle, Número, Colonia, Referencias">
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Teléfono de Contacto</label>
                                    <input type="tel" id="delivery_phone" class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 transition-colors" placeholder="10 dígitos">
                                </div>
                                <div>
                                    <label class="block text-gray-400 text-xs mb-1">Instrucciones Adicionales (Opcional)</label>
                                    <textarea id="delivery_instructions" rows="2" class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-yellow-500 transition-colors" placeholder="Ej. Tocar el timbre, dejar en recepción..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Método de Pago (1/3 del ancho) -->
            <div class="lg:col-span-1">
                <div class="bg-zinc-800/80 backdrop-blur-md rounded-2xl border border-zinc-700/50 shadow-xl sticky top-24">
                    <div class="p-6 border-b border-zinc-700/50">
                        <h2 class="text-xl text-white font-semibold flex items-center gap-2">
                            <i class="fas fa-credit-card text-blue-400"></i> Método de Pago
                        </h2>
                    </div>

                    <div class="p-6">
                        <form id="checkoutForm" onsubmit="procesarCompra(event)">
                            <div class="space-y-4 mb-8">
                                <!-- Opción Efectivo -->
                                <label class="relative block cursor-pointer group">
                                    <input type="radio" name="metodo_pago" value="efectivo" class="peer sr-only" checked>
                                    <div class="p-4 rounded-xl border border-zinc-600 bg-zinc-700/30 hover:bg-zinc-700/50 transition-all duration-300 peer-checked:border-green-500 peer-checked:bg-gradient-to-r peer-checked:from-green-500/10 peer-checked:to-emerald-500/10 peer-checked:shadow-[0_0_20px_rgba(34,197,94,0.15)] transform peer-checked:scale-[1.02]">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-full bg-zinc-800 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300 peer-checked:bg-green-500 peer-checked:text-white">
                                                <i class="fas fa-money-bill-wave text-xl text-green-400 peer-checked:text-white transition-colors"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-white font-bold text-lg group-hover:text-green-400 transition-colors">Efectivo</h3>
                                                <p class="text-gray-400 text-sm">Pago al momento de la entrega</p>
                                            </div>
                                            <div class="w-6 h-6 rounded-full border-2 border-zinc-500 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center transition-all duration-300 shadow-inner">
                                                <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transform scale-0 peer-checked:scale-100 transition-all"></i>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Opción Tarjeta y Formulario -->
                                <div class="space-y-3">
                                    <label class="relative block cursor-pointer group">
                                        <input type="radio" name="metodo_pago" value="tarjeta" class="peer sr-only">
                                        <div class="p-4 rounded-xl border border-zinc-600 bg-zinc-700/30 hover:bg-zinc-700/50 transition-all duration-300 peer-checked:border-blue-500 peer-checked:bg-gradient-to-r peer-checked:from-blue-500/10 peer-checked:to-cyan-500/10 peer-checked:shadow-[0_0_20px_rgba(59,130,246,0.15)] transform peer-checked:scale-[1.02]">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-full bg-zinc-800 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300 peer-checked:bg-blue-500 peer-checked:text-white">
                                                    <i class="fas fa-credit-card text-xl text-blue-400 peer-checked:text-white transition-colors"></i>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-white font-bold text-lg group-hover:text-blue-400 transition-colors">Tarjeta</h3>
                                                    <p class="text-gray-400 text-sm">Crédito / Débito (POS)</p>
                                                </div>
                                                <div class="w-6 h-6 rounded-full border-2 border-zinc-500 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center transition-all duration-300 shadow-inner">
                                                    <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transform scale-0 peer-checked:scale-100 transition-all"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Formulario de Tarjeta (Integrado) -->
                                    <div id="card-details" class="hidden bg-zinc-800/50 p-4 rounded-xl border border-blue-500/30 animate-fade-in-down ml-2 border-l-4 border-l-blue-500">
                                        <h4 class="text-blue-400 text-sm font-semibold mb-3">Ingresa los datos de tu tarjeta</h4>
                                        <div class="space-y-3">
                                            <div>
                                                <input type="text" id="card_name" class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-blue-500 transition-colors" placeholder="Nombre del Titular">
                                            </div>
                                            <div class="relative">
                                                <input type="text" id="card_number" class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-blue-500 transition-colors" placeholder="0000 0000 0000 0000" maxlength="19">
                                                <i class="fab fa-cc-visa absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <input type="text" id="card_expiry" class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-blue-500 transition-colors" placeholder="MM/YY" maxlength="5">
                                                <input type="text" id="card_cvv" class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:border-blue-500 transition-colors" placeholder="CVV" maxlength="4">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-green-500/20 transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center gap-2 group">
                                    <span>Confirmar Pedido</span>
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    cargarResumenCarrito();
    setupPaymentListeners();
});

function setupPaymentListeners() {
    // Listeners para Método de Pago
    const paymentRadios = document.querySelectorAll('input[name="metodo_pago"]');
    const cardDetails = document.getElementById('card-details');
    
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (e.target.value === 'tarjeta') {
                cardDetails.classList.remove('hidden');
            } else {
                cardDetails.classList.add('hidden');
            }
        });
    });

    // Listeners para Tipo de Entrega
    const deliveryRadios = document.querySelectorAll('input[name="tipo_entrega"]');
    const deliveryDetails = document.getElementById('delivery-details');

    deliveryRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (e.target.value === 'domicilio') {
                deliveryDetails.classList.remove('hidden');
            } else {
                deliveryDetails.classList.add('hidden');
            }
        });
    });

    // Formateo simple de tarjeta
    const cardNumber = document.getElementById('card_number');
    cardNumber.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(.{4})/g, '$1 ').trim();
        e.target.value = value;
    });

    const cardExpiry = document.getElementById('card_expiry');
    cardExpiry.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });
}

function cargarResumenCarrito() {
    const items = JSON.parse(localStorage.getItem('carrito')) || [];
    const container = document.getElementById('checkout-items');
    
    if (items.length === 0) {
        window.location.href = "{{ route('cliente.carrito') }}";
        return;
    }

    let html = '';
    let total = 0;

    items.forEach(item => {
        const subtotal = item.precio * item.cantidad;
        total += subtotal;
        
        // Determinar imagen (manejo de fallback)
        const imgSrc = item.imagen 
            ? (item.imagen.startsWith('http') ? item.imagen : '/storage/' + item.imagen)
            : "{{ asset('assets/placeholder.svg') }}";

        html += `
            <div class="flex items-center gap-4 bg-zinc-700/20 p-4 rounded-xl border border-zinc-700/30 hover:bg-zinc-700/40 transition-colors">
                <div class="relative flex-shrink-0">
                    <img src="${imgSrc}" alt="${item.nombre}" class="w-20 h-20 object-cover rounded-lg shadow-md">
                    <span class="absolute -top-2 -right-2 bg-zinc-700 text-white text-xs font-bold w-6 h-6 flex items-center justify-center rounded-full border border-zinc-600 shadow-sm">
                        ${item.cantidad}
                    </span>
                </div>
                
                <div class="flex-1 min-w-0">
                    <h3 class="text-white font-medium truncate text-lg">${item.nombre}</h3>
                    <p class="text-gray-400 text-sm">Precio unitario: $${item.precio.toFixed(2)}</p>
                </div>
                
                <div class="text-right">
                    <span class="text-green-400 font-bold text-lg">$${subtotal.toFixed(2)}</span>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    document.getElementById('checkout-total').textContent = '$' + total.toFixed(2);
    document.getElementById('checkout-count').textContent = items.length + (items.length === 1 ? ' producto' : ' productos');
}

function procesarCompra(e) {
    e.preventDefault();
    
    const items = JSON.parse(localStorage.getItem('carrito')) || [];
    
    // Configuración de Toast (Alerta pequeña)
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#27272a',
        color: '#fff',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    if (items.length === 0) {
        Toast.fire({
            icon: 'warning',
            title: 'Carrito Vacío'
        }).then(() => {
            window.location.href = "{{ route('cliente.carrito') }}";
        });
        return;
    }

    const metodoPago = document.querySelector('input[name="metodo_pago"]:checked').value;
    const tipoEntrega = document.querySelector('input[name="tipo_entrega"]:checked').value;

    // Validación de Dirección (si es a domicilio)
    if (tipoEntrega === 'domicilio') {
        const address = document.getElementById('delivery_address').value.trim();
        const phone = document.getElementById('delivery_phone').value.trim();

        if (address.length < 5) {
            showError('Dirección incompleta');
            return;
        }
        if (phone.length < 10) {
            showError('Teléfono inválido (10 dígitos)');
            return;
        }
    }

    // Validación de tarjeta
    if (metodoPago === 'tarjeta') {
        const cardName = document.getElementById('card_name').value.trim();
        const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
        const cardExpiry = document.getElementById('card_expiry').value.trim();
        const cardCvv = document.getElementById('card_cvv').value.trim();

        // Validar Nombre
        if (cardName.length < 3) {
            showError('Nombre incompleto');
            return;
        }

        // Validar Número (16 dígitos)
        if (!/^\d{16}$/.test(cardNumber)) {
            showError('Número de tarjeta inválido');
            return;
        }

        // Validar Expiración (MM/YY y fecha futura)
        if (!validateExpiry(cardExpiry)) {
            showError('Fecha de expiración inválida');
            return;
        }

        // Validar CVV (3 o 4 dígitos)
        if (!/^\d{3,4}$/.test(cardCvv)) {
            showError('CVV inválido');
            return;
        }
    }

    function showError(message) {
        Toast.fire({
            icon: 'warning',
            title: message
        });
    }

    function validateExpiry(expiry) {
        if (!/^\d{2}\/\d{2}$/.test(expiry)) return false;
        
        const [month, year] = expiry.split('/').map(num => parseInt(num, 10));
        const now = new Date();
        const currentYear = now.getFullYear() % 100; // Últimos 2 dígitos
        const currentMonth = now.getMonth() + 1;

        if (month < 1 || month > 12) return false;
        
        // Si el año es menor al actual, o si es el mismo año pero el mes ya pasó
        if (year < currentYear || (year === currentYear && month < currentMonth)) {
            return false;
        }
        
        return true;
    }

    const btn = e.target.querySelector('button[type="submit"]');
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Procesando...';
    btn.classList.add('opacity-75', 'cursor-not-allowed');

    fetch("{{ route('api.carrito.finalizar') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ 
            carrito: items,
            metodo_pago: metodoPago,
            tipo_entrega: tipoEntrega,
            direccion_entrega: tipoEntrega === 'domicilio' ? {
                direccion: document.getElementById('delivery_address').value,
                telefono: document.getElementById('delivery_phone').value,
                instrucciones: document.getElementById('delivery_instructions').value
            } : null,
            // En un caso real, aquí enviaríamos un token de pago, no los datos crudos
            card_details: metodoPago === 'tarjeta' ? {
                last4: document.getElementById('card_number').value.slice(-4)
            } : null
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('carrito');
            
            Toast.fire({
                icon: 'success',
                title: '¡Pedido Confirmado!'
            }).then(() => {
                window.location.href = "{{ route('cliente.pedidos.index') }}";
            });
        } else {
            throw new Error(data.message || 'Error al procesar el pedido');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = originalContent;
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
        
        Toast.fire({
            icon: 'error',
            title: error.message || 'Error al procesar pedido'
        });
    });
}
</script>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 3px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 3px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>
@endsection
