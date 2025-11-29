@extends('empleado.layout')

@section('content')
<div class="space-y-6">
    <!-- Encabezado -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-green-400">Detalle del Pedido #{{ $pedido->id }}</h2>
            <p class="text-gray-400">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
        </div>
        
        <div class="flex items-center space-x-4">
            <!-- Estado actual -->
            <span class="px-3 py-1 rounded-lg text-sm font-semibold
                {{ $pedido->status === 'pendiente' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                {{ $pedido->status === 'preparando' ? 'bg-blue-500/20 text-blue-400' : '' }}
                {{ $pedido->status === 'listo' ? 'bg-green-500/20 text-green-400' : '' }}
                {{ $pedido->status === 'entregado' ? 'bg-purple-500/20 text-purple-400' : '' }}
                {{ $pedido->status === 'cancelado' ? 'bg-red-500/20 text-red-400' : '' }}">
                {{ ucfirst($pedido->status) }}
            </span>
            
            <!-- Botón de actualizar estado -->
            <button 
                onclick="mostrarModalEstado()"
                class="px-4 py-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition-colors"
            >
                Actualizar Estado
            </button>

            <a href="{{ route('empleado.pedidos.index') }}" 
               class="text-gray-400 hover:text-gray-300">
                Volver a pedidos
            </a>
        </div>
    </div>

    <!-- Grid principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del pedido y productos -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Lista de productos -->
            <div class="bg-gray-800/50 rounded-2xl p-6 border border-green-700/20">
                <h2 class="text-lg font-semibold text-green-400 mb-4">Productos</h2>
                <div class="space-y-4">
                    @foreach($items as $item)
                        <div class="flex items-center justify-between p-4 bg-gray-800/30 rounded-lg border border-green-700/10">
                            <div class="flex items-center space-x-4">
                                @if(!empty($item['imagen']))
                                    <img src="{{ asset('storage/' . $item['imagen']) }}" 
                                         alt="{{ $item['nombre'] }}"
                                         class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-800 rounded flex items-center justify-center border border-green-700/10">
                                        <i class="fas fa-box text-gray-600"></i>
                                    </div>
                                @endif
                                
                                <div>
                                    <h3 class="font-semibold text-white">{{ $item['nombre'] }}</h3>
                                    <p class="text-sm text-gray-400">
                                        ${{ number_format($item['precio'], 2) }} x {{ $item['cantidad'] }}
                                    </p>
                                    @if($item['stock_actual'] <= 5)
                                        <p class="text-sm text-red-400 mt-1">
                                            ¡Stock bajo! ({{ $item['stock_actual'] }} disponibles)
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-white">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total -->
                <div class="mt-6 pt-6 border-t border-green-700/10">
                    <div class="flex justify-between items-center">
                        <span class="text-lg text-gray-400">Total:</span>
                        <span class="text-2xl font-bold text-green-400">${{ number_format($pedido->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Notas -->
            <div class="bg-gray-800/50 rounded-2xl p-6 border border-green-700/20">
                <h2 class="text-lg font-semibold text-green-400 mb-4">Notas del Pedido</h2>
                <textarea 
                    id="notas"
                    class="w-full bg-gray-800/30 border border-green-700/10 rounded-lg text-white px-4 py-3 focus:outline-none focus:border-green-500/50"
                    rows="3"
                    placeholder="Agregar notas al pedido..."
                >{{ $pedido->notas }}</textarea>
                <button 
                    onclick="guardarNotas()"
                    class="mt-2 px-4 py-2 bg-gray-800/30 text-gray-300 rounded-lg hover:bg-gray-800/50 transition-colors">
                    Guardar Notas
                </button>
            </div>
        </div>

        <!-- Información del cliente y timeline -->
        <div class="space-y-6">
            <!-- Información del cliente -->
            <div class="bg-gray-800/50 rounded-2xl p-6 border border-green-700/20">
                <h2 class="text-lg font-semibold text-green-400 mb-4">Información del Cliente</h2>
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-gray-400">Nombre</label>
                        <p class="font-semibold text-white">{{ $pedido->cliente_nombre }}</p>
                    </div>
                    
                    @if($pedido->cliente_telefono)
                        <div>
                            <label class="text-sm text-gray-400">Teléfono</label>
                            <p class="font-semibold text-white">{{ $pedido->cliente_telefono }}</p>
                        </div>
                    @endif

                    @if($pedido->direccion)
                        <div>
                            <label class="text-sm text-gray-400">Dirección</label>
                            <p class="font-semibold text-white">{{ $pedido->direccion }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tiempo estimado -->
            <div class="bg-gray-800/50 rounded-2xl p-6 border border-green-700/20">
                <h2 class="text-lg font-semibold text-green-400 mb-4">Tiempo Estimado</h2>
                <div class="flex items-center space-x-4">
                    <input type="number" 
                           id="tiempo_estimado"
                           value="{{ $pedido->tiempo_estimado }}"
                           min="1"
                           max="180"
                           class="w-24 bg-gray-800/30 border border-green-700/10 rounded-lg text-white px-3 py-2 focus:outline-none focus:border-green-500/50">
                    <span class="text-gray-400">minutos</span>
                </div>
                <button 
                    onclick="actualizarTiempoEstimado()"
                    class="mt-4 w-full px-4 py-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition-colors">
                    Actualizar Tiempo
                </button>
            </div>

            <!-- Estados permitidos -->
            <div class="bg-gray-800/50 rounded-2xl p-6 border border-green-700/20">
                <h2 class="text-lg font-semibold text-green-400 mb-4">Estados Disponibles</h2>
                <div class="space-y-2">
                    @foreach($estadosPermitidos as $estado)
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full
                                {{ $estado === 'preparando' ? 'bg-blue-400' : '' }}
                                {{ $estado === 'listo' ? 'bg-green-400' : '' }}
                                {{ $estado === 'entregado' ? 'bg-purple-400' : '' }}
                                {{ $estado === 'cancelado' ? 'bg-red-400' : '' }}">
                            </span>
                            <span class="text-gray-300">{{ ucfirst($estado) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de actualización de estado -->
<div id="modal-estado" 
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-800 rounded-2xl border border-green-700/20 shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-green-400 mb-4">Actualizar Estado del Pedido</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400">Nuevo Estado</label>
                    <select id="nuevo-estado" 
                            class="mt-1 block w-full bg-gray-800/30 border border-green-700/10 rounded-lg text-white px-3 py-2 focus:outline-none focus:border-green-500/50">
                        @foreach($estadosPermitidos as $estado)
                            <option value="{{ $estado }}">{{ ucfirst($estado) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400">Tiempo Estimado (min)</label>
                    <input type="number" 
                           id="nuevo-tiempo"
                           value="{{ $pedido->tiempo_estimado }}"
                           min="1"
                           max="180"
                           class="mt-1 block w-full bg-gray-800/30 border border-green-700/10 rounded-lg text-white px-3 py-2 focus:outline-none focus:border-green-500/50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400">Notas</label>
                    <textarea id="nuevo-notas"
                              rows="3"
                              class="mt-1 block w-full bg-gray-800/30 border border-green-700/10 rounded-lg text-white px-3 py-2 focus:outline-none focus:border-green-500/50"
                              placeholder="Agregar notas sobre el cambio de estado..."></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="cerrarModalEstado()"
                        class="px-4 py-2 border border-green-700/10 rounded-lg text-gray-400 hover:bg-gray-800/50">
                    Cancelar
                </button>
                <button onclick="actualizarEstado()"
                        class="px-4 py-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30">
                    Actualizar
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function mostrarModalEstado() {
    document.getElementById('modal-estado').classList.remove('hidden');
}

function cerrarModalEstado() {
    document.getElementById('modal-estado').classList.add('hidden');
}

function actualizarEstado() {
    const estado = document.getElementById('nuevo-estado').value;
    const tiempo = document.getElementById('nuevo-tiempo').value;
    const notas = document.getElementById('nuevo-notas').value;

    axios.patch('{{ route("empleado.pedidos.updateStatus", $pedido) }}', {
        status: estado,
        tiempo_estimado: tiempo,
        notas: notas
    })
    .then(response => {
        if (response.data.success) {
            window.location.reload();
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.response.data.message,
            background: '#1f2937',
            color: '#fff'
        });
    });
}

function actualizarTiempoEstimado() {
    const tiempo = document.getElementById('tiempo_estimado').value;
    
    axios.patch('{{ route("empleado.pedidos.updateStatus", $pedido) }}', {
        status: '{{ $pedido->status }}',
        tiempo_estimado: tiempo
    })
    .then(response => {
        if (response.data.success) {
            Toast.fire({
                icon: 'success',
                title: 'Tiempo estimado actualizado'
            });
        }
    })
    .catch(error => {
        Toast.fire({
            icon: 'error',
            title: error.response.data.message
        });
    });
}

function guardarNotas() {
    const notas = document.getElementById('notas').value;
    
    axios.patch('{{ route("empleado.pedidos.updateStatus", $pedido) }}', {
        status: '{{ $pedido->status }}',
        notas: notas
    })
    .then(response => {
        if (response.data.success) {
            Toast.fire({
                icon: 'success',
                title: 'Notas actualizadas'
            });
        }
    })
    .catch(error => {
        Toast.fire({
            icon: 'error',
            title: error.response.data.message
        });
    });
}
</script>
@endpush
