@extends('cliente.layout.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Imagen del Producto -->
        <div>
            @if($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}" 
                     alt="{{ $producto->nombre }}"
                     class="w-full h-96 object-cover rounded-lg shadow-lg">
            @else
                <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                    <i class="fas fa-image text-gray-400 text-6xl"></i>
                </div>
            @endif
        </div>

        <!-- Detalles del Producto -->
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $producto->nombre }}</h1>
                <p class="text-gray-600">{{ $producto->descripcion }}</p>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-3xl font-bold">${{ number_format($producto->precio, 2) }}</span>
                <span class="text-gray-600">
                    Stock disponible: 
                    <span class="font-semibold {{ $producto->stock < 10 ? 'text-red-500' : 'text-green-500' }}">
                        {{ $producto->stock }}
                    </span>
                </span>
            </div>

            <!-- Formulario de Agregar al Carrito -->
            <div class="border-t border-b py-6 space-y-4">
                <div class="flex items-center space-x-4">
                    <label class="text-gray-700">Cantidad:</label>
                    <div class="flex items-center border rounded">
                        <button type="button" 
                                onclick="decrementarCantidad()"
                                class="px-3 py-1 border-r hover:bg-gray-100">-</button>
                        <input type="number" 
                               id="cantidad" 
                               value="1" 
                               min="1" 
                               max="{{ $producto->stock }}"
                               class="w-20 text-center py-1 focus:outline-none"
                               onchange="validarCantidad(this)">
                        <button type="button"
                                onclick="incrementarCantidad()"
                                class="px-3 py-1 border-l hover:bg-gray-100">+</button>
                    </div>
                </div>

                <button onclick="agregarAlCarrito()"
                        class="w-full bg-primary text-white py-3 rounded-lg hover:bg-primary-dark transition-colors">
                    Agregar al Carrito
                </button>
            </div>

            <!-- Información Adicional -->
            <div class="space-y-4">
                <div>
                    <h3 class="font-semibold mb-2">Categoría</h3>
                    <p class="text-gray-600">{{ $producto->categoria->nombre }}</p>
                </div>

                @if($producto->tiempo_preparacion)
                    <div>
                        <h3 class="font-semibold mb-2">Tiempo de Preparación</h3>
                        <p class="text-gray-600">{{ $producto->tiempo_preparacion }} minutos</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Productos Relacionados -->
    @if($productosRelacionados->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold mb-6">Productos Relacionados</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($productosRelacionados as $relacionado)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        @if($relacionado->imagen)
                            <img src="{{ asset('storage/' . $relacionado->imagen) }}" 
                                 alt="{{ $relacionado->nombre }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <h3 class="font-semibold">{{ $relacionado->nombre }}</h3>
                            <p class="text-gray-600 mt-1">${{ number_format($relacionado->precio, 2) }}</p>
                            <a href="{{ route('cliente.productos.show', $relacionado) }}"
                               class="mt-4 block text-center bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200 transition-colors">
                                Ver Detalles
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
let maxStock = {{ $producto->stock }};

function validarCantidad(input) {
    let value = parseInt(input.value);
    if (value < 1) input.value = 1;
    if (value > maxStock) input.value = maxStock;
}

function decrementarCantidad() {
    let input = document.getElementById('cantidad');
    let value = parseInt(input.value) - 1;
    if (value >= 1) input.value = value;
}

function incrementarCantidad() {
    let input = document.getElementById('cantidad');
    let value = parseInt(input.value) + 1;
    if (value <= maxStock) input.value = value;
}

function agregarAlCarrito() {
    let cantidad = parseInt(document.getElementById('cantidad').value);
    
    const producto = {
        id: {{ $producto->id }},
        nombre: "{{ $producto->nombre }}",
        precio: {{ $producto->precio }},
        stock: {{ $producto->stock }},
        @if($producto->imagen)
        imagen: "{{ asset('storage/' . $producto->imagen) }}",
        @endif
        cantidad: cantidad
    };

    // Agregar al carrito usando la clase Carrito
    if (window.carrito) {
        window.carrito.agregar(producto);

        Swal.fire({
            title: '¡Agregado!',
            text: 'El producto se agregó al carrito',
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#718096',
            confirmButtonText: 'Ver Carrito',
            cancelButtonText: 'Seguir Comprando'
        }).then((result) => {
            if (result.isConfirmed) {
                window.carrito.mostrarCarritoResumen();
            }
        });
    } else {
        console.error('El objeto carrito no está inicializado');
    }
}
</script>
@endpush