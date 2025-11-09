@extends('cliente.layout.cliente')

@section('title', 'Confirmar Pedido - DÉSOLÉ')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Pasos del proceso -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="ml-2 text-green-400">Carrito</div>
                </div>
                <div class="flex-1 h-px bg-green-500"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="ml-2 text-green-400">Confirmar</div>
                </div>
                <div class="flex-1 h-px bg-zinc-600"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-zinc-600 text-zinc-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <div class="ml-2 text-zinc-400">Completado</div>
                </div>
            </div>
        </div>

        <!-- Formulario de confirmación -->
        <div class="bg-zinc-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <h1 class="text-3xl font-bold text-green-400 mb-6">Confirmar Pedido</h1>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Resumen del pedido -->
                    <div>
                        <h2 class="text-xl font-semibold text-white mb-4">Resumen del Pedido</h2>
                        <div id="resumen-items" class="space-y-4 mb-6">
                            <!-- Items se cargarán dinámicamente -->
                        </div>
                        <div class="border-t border-zinc-700 pt-4">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span class="text-white">Total</span>
                                <span id="total-pedido" class="text-green-400"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario -->
                    <div>
                        <h2 class="text-xl font-semibold text-white mb-4">Detalles de Entrega</h2>
                        <form id="form-confirmar" class="space-y-4">
                            <div>
                                <label class="block text-gray-400 mb-1">Nombre</label>
                                <input type="text" 
                                       id="nombre" 
                                       value="{{ $cliente->nombre }}" 
                                       class="w-full px-4 py-2 bg-zinc-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                       readonly>
                            </div>

                            <div>
                                <label class="block text-gray-400 mb-1">Teléfono</label>
                                <input type="tel" 
                                       id="telefono" 
                                       value="{{ $cliente->telefono }}" 
                                       class="w-full px-4 py-2 bg-zinc-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                       readonly>
                            </div>

                            <div>
                                <label class="block text-gray-400 mb-1">Dirección</label>
                                <input type="text" 
                                       id="direccion" 
                                       value="{{ $cliente->direccion }}" 
                                       class="w-full px-4 py-2 bg-zinc-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                       readonly>
                            </div>

                            <div>
                                <label class="block text-gray-400 mb-1">Notas o instrucciones especiales</label>
                                <textarea id="notas" 
                                          rows="3" 
                                          class="w-full px-4 py-2 bg-zinc-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                          placeholder="Instrucciones especiales para tu pedido..."></textarea>
                            </div>

                            <div class="pt-4">
                                <button type="submit" 
                                        id="btn-confirmar"
                                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg transition-colors">
                                    Confirmar Pedido
                                </button>
                                <a href="{{ route('cliente.carrito') }}" 
                                   class="block text-center mt-4 text-gray-400 hover:text-white transition-colors">
                                    Volver al Carrito
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const items = JSON.parse(localStorage.getItem('carrito')) || [];
    const resumenContainer = document.getElementById('resumen-items');
    const totalElement = document.getElementById('total-pedido');
    const form = document.getElementById('form-confirmar');
    let total = 0;

    // Mostrar items
    resumenContainer.innerHTML = items.map(item => {
        const subtotal = item.precio * item.cantidad;
        total += subtotal;
        return `
            <div class="flex items-center justify-between py-2 border-b border-zinc-700 last:border-0">
                <div class="flex items-center space-x-4">
                    <img src="${item.imagen || '{{ asset('assets/placeholder.png') }}'}" 
                         alt="${item.nombre}"
                         class="w-12 h-12 object-cover rounded">
                    <div>
                        <h3 class="text-white">${item.nombre}</h3>
                        <p class="text-sm text-gray-400">${item.cantidad}x $${item.precio.toFixed(2)}</p>
                    </div>
                </div>
                <div class="text-green-400 font-semibold">
                    $${subtotal.toFixed(2)}
                </div>
            </div>
        `;
    }).join('');

    totalElement.textContent = `$${total.toFixed(2)}`;

    // Manejar envío del formulario
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const btnConfirmar = document.getElementById('btn-confirmar');
        btnConfirmar.disabled = true;
        btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';

        try {
            const response = await fetch('{{ route("cliente.carrito.procesar") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    items: items.map(item => ({
                        id: item.id,
                        cantidad: item.cantidad
                    })),
                    notas: document.getElementById('notas').value
                })
            });

            const data = await response.json();

            if (data.success) {
                // Limpiar carrito
                localStorage.removeItem('carrito');
                
                // Mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: '¡Pedido Confirmado!',
                    text: 'Tu pedido ha sido recibido y está siendo procesado.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = '{{ route("cliente.pedidos") }}';
                });
            } else {
                throw new Error(data.message);
            }

        } catch (error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Hubo un error al procesar tu pedido. Por favor, intenta de nuevo.',
            });
            btnConfirmar.disabled = false;
            btnConfirmar.innerHTML = 'Confirmar Pedido';
        }
    });
});
</script>
@endsection