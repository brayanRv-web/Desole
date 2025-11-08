@extends('cliente.layout.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Mi Carrito</h1>

    @if(empty($carrito))
        <div class="bg-gray-100 rounded-lg p-6 text-center">
            <p class="text-gray-600">Tu carrito está vacío</p>
            <a href="{{ route('cliente.menu') }}" class="mt-4 inline-block bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark">
                Ver Menú
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            <!-- Lista de productos en el carrito -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="space-y-4">
                    @foreach($carrito as $id => $item)
                        <div class="flex items-center justify-between border-b pb-4" id="cart-item-{{ $id }}">
                            <div class="flex items-center space-x-4">
                                @if($item['imagen'])
                                    <img src="{{ asset('storage/' . $item['imagen']) }}" alt="{{ $item['nombre'] }}" class="w-20 h-20 object-cover rounded">
                                @else
                                    <div class="w-20 h-20 bg-gray-200 rounded"></div>
                                @endif
                                <div>
                                    <h3 class="font-semibold">{{ $item['nombre'] }}</h3>
                                    <p class="text-gray-600">${{ number_format($item['precio'], 2) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center border rounded">
                                    <button 
                                        class="px-3 py-1 border-r hover:bg-gray-100"
                                        onclick="actualizarCantidad({{ $id }}, {{ $item['cantidad'] - 1 }})"
                                    >-</button>
                                    <span class="px-4 py-1">{{ $item['cantidad'] }}</span>
                                    <button 
                                        class="px-3 py-1 border-l hover:bg-gray-100"
                                        onclick="actualizarCantidad({{ $id }}, {{ $item['cantidad'] + 1 }})"
                                    >+</button>
                                </div>
                                <span class="font-semibold">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</span>
                                <button 
                                    onclick="eliminarDelCarrito({{ $id }})"
                                    class="text-red-500 hover:text-red-700"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total y botones -->
                <div class="mt-6 border-t pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold">Total:</span>
                        <span class="text-2xl font-bold">${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <button 
                            onclick="vaciarCarrito()"
                            class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600"
                        >
                            Vaciar Carrito
                        </button>
                        <button 
                            onclick="confirmarPedido()"
                            class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark"
                        >
                            Confirmar Pedido
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function actualizarCantidad(productoId, nuevaCantidad) {
    axios.post('{{ route("cliente.carrito.actualizar") }}', {
        producto_id: productoId,
        cantidad: nuevaCantidad
    })
    .then(response => {
        if (response.data.success) {
            // Actualizar la interfaz
            actualizarInterfazCarrito(response.data);
            
            if (nuevaCantidad === 0) {
                // Si la cantidad es 0, eliminar el elemento del DOM
                document.getElementById('cart-item-' + productoId).remove();
            }
            
            // Mostrar mensaje de éxito
            Toast.fire({
                icon: 'success',
                title: response.data.message
            });
        }
    })
    .catch(error => {
        Toast.fire({
            icon: 'error',
            title: error.response.data.message || 'Error al actualizar el carrito'
        });
    });
}

function eliminarDelCarrito(productoId) {
    axios.post('{{ route("cliente.carrito.eliminar") }}', {
        producto_id: productoId
    })
    .then(response => {
        if (response.data.success) {
            // Eliminar el elemento del DOM
            document.getElementById('cart-item-' + productoId).remove();
            
            // Actualizar la interfaz
            actualizarInterfazCarrito(response.data);
            
            // Mostrar mensaje de éxito
            Toast.fire({
                icon: 'success',
                title: response.data.message
            });
            
            // Si el carrito está vacío, recargar la página
            if (response.data.carrito_count === 0) {
                location.reload();
            }
        }
    })
    .catch(error => {
        Toast.fire({
            icon: 'error',
            title: 'Error al eliminar el producto'
        });
    });
}

function vaciarCarrito() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminarán todos los productos del carrito",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, vaciar carrito',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post('{{ route("cliente.carrito.vaciar") }}')
                .then(response => {
                    if (response.data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al vaciar el carrito'
                    });
                });
        }
    });
}

function confirmarPedido() {
    Swal.fire({
        title: '¿Confirmar pedido?',
        text: "Se procesará tu pedido",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post('{{ route("cliente.pedido.confirmar") }}')
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            title: '¡Pedido Confirmado!',
                            text: response.data.message,
                            icon: 'success',
                            confirmButtonText: 'Ver mis pedidos'
                        }).then(() => {
                            window.location.href = response.data.redirect_url;
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response.data.message || 'Error al procesar el pedido'
                    });
                });
        }
    });
}

function actualizarInterfazCarrito(data) {
    // Actualizar contador del carrito en el navbar
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        cartCount.textContent = data.carrito_count;
        
        // Ocultar si está vacío
        if (data.carrito_count === 0) {
            cartCount.classList.add('hidden');
        } else {
            cartCount.classList.remove('hidden');
        }
    }
    
    // Actualizar total si existe el elemento
    const cartTotal = document.querySelector('[data-cart-total]');
    if (cartTotal && data.carrito_total !== undefined) {
        cartTotal.textContent = '$' + parseFloat(data.carrito_total).toFixed(2);
    }
}
</script>
@endpush