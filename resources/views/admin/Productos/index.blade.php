@extends('admin.layout')

@section('content')
@php
    use Illuminate\Support\Str;

    $estadoColors = [
        'activo' => 'bg-green-500/20 text-green-400 border-green-500',
        'inactivo' => 'bg-gray-500/20 text-gray-400 border-gray-500',
        'agotado' => 'bg-red-500/20 text-red-400 border-red-500',
    ];

    $estadoIcons = [
        'activo' => 'fas fa-check-circle',
        'inactivo' => 'fas fa-pause-circle',
        'agotado' => 'fas fa-times-circle',
    ];
@endphp

<div class="flex flex-col space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-green-400 flex items-center gap-3">
                <i class="fas fa-box text-2xl"></i>
                Gestión de Productos
            </h2>
            <p class="text-gray-400 mt-2">Administra y organiza los productos de tu cafetería</p>
        </div>
        
        <a href="{{ route('admin.productos.create') }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-green-500/25 transition-all duration-200 flex items-center gap-2 font-semibold group">
            <i class="fas fa-plus-circle group-hover:scale-110 transition-transform"></i>
            Nuevo Producto
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/50 text-green-400 px-6 py-4 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle text-green-400"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-400"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Products Counter -->
    <div class="bg-gray-800/30 border border-green-700/20 rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-green-600/20 flex items-center justify-center">
                    <i class="fas fa-boxes text-green-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Total de Productos</p>
                    <p class="text-2xl font-bold text-white">{{ $productos->count() }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-gray-400 text-sm">Activos</p>
                <p class="text-lg font-semibold text-green-400">
                    {{ $productos->where('estado', 'activo')->count() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-gray-800/50 rounded-2xl border border-green-700/30 shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-green-700/40 to-green-800/20 border-b border-green-600/30">
                    <tr>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-cube mr-2"></i>Producto
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-tags mr-2"></i>Categoría
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-align-left mr-2"></i>Descripción
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-dollar-sign mr-2"></i>Precio
                        </th>
                        <th class="px-6 py-4 text-left text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-circle mr-2"></i>Estado
                        </th>
                        <th class="px-6 py-4 text-center text-green-300 font-semibold text-sm uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2"></i>Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700/50">
                    @forelse($productos as $producto)
                    <tr class="hover:bg-green-900/20 transition-all duration-200 group">
                        <!-- Product Name -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                @if($producto->imagen)
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                         alt="{{ $producto->nombre }}"
                                         class="w-10 h-10 rounded-lg object-cover border border-green-600/30">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-green-600/20 border border-green-600/30 flex items-center justify-center">
                                        <i class="fas fa-coffee text-green-400 text-sm"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-white group-hover:text-green-300 transition-colors">
                                        {{ $producto->nombre }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Category -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-700/50 text-gray-300 border border-gray-600/50">
                                <i class="fas fa-tag mr-1 text-xs"></i>
                                {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                            </span>
                        </td>

                        <!-- Description -->
                        <td class="px-6 py-4 max-w-xs">
                            <div class="text-sm text-gray-400 leading-relaxed">
                                {{ $producto->descripcion ? Str::limit($producto->descripcion, 80, '...') : 'Sin descripción' }}
                            </div>
                        </td>

                        <!-- Price -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold text-green-400">
                                ${{ number_format($producto->precio, 2) }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.productos.updateEstado', $producto) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <div class="relative inline-block text-left min-w-[140px]">
                                    <button type="button" class="inline-flex items-center justify-between w-full px-4 py-2 rounded-xl border text-sm font-medium transition-all duration-200 hover:shadow-lg {{ $estadoColors[$producto->estado] ?? 'bg-gray-500/20 text-gray-400 border-gray-500' }}">
                                        <div class="flex items-center gap-2">
                                            <i class="{{ $estadoIcons[$producto->estado] ?? 'fas fa-circle' }} text-xs"></i>
                                            <span>{{ ucfirst($producto->estado) }}</span>
                                        </div>
                                        <i class="fas fa-chevron-down text-xs ml-2"></i>
                                    </button>
                                    <select name="estado" onchange="this.form.submit()" 
                                            class="absolute top-0 left-0 opacity-0 w-full h-full cursor-pointer">
                                        <option value="activo" {{ $producto->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="inactivo" {{ $producto->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                        <option value="agotado" {{ $producto->estado == 'agotado' ? 'selected' : '' }}>Agotado</option>
                                    </select>
                                </div>
                            </form>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.productos.edit', $producto) }}" 
                                   class="bg-blue-600/20 hover:bg-blue-600/40 border border-blue-500/50 hover:border-blue-400 text-blue-400 hover:text-blue-300 p-2 rounded-xl transition-all duration-200 group/edit tooltip"
                                   title="Editar producto">
                                    <i class="fas fa-edit group-hover/edit:scale-110 transition-transform"></i>
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" 
                                      onsubmit="return confirm('¿Está seguro de que desea eliminar permanentemente este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600/20 hover:bg-red-600/40 border border-red-500/50 hover:border-red-400 text-red-400 hover:text-red-300 p-2 rounded-xl transition-all duration-200 group/delete tooltip"
                                            title="Eliminar producto">
                                        <i class="fas fa-trash group-hover/delete:scale-110 transition-transform"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-4 text-gray-600"></i>
                                <h3 class="text-lg font-semibold text-gray-400 mb-2">No hay productos registrados</h3>
                                <p class="text-sm text-gray-500 mb-4">Comienza agregando tu primer producto al menú</p>
                                <a href="{{ route('admin.productos.create') }}" 
                                   class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-all duration-200 flex items-center gap-2">
                                    <i class="fas fa-plus"></i>
                                    Crear Primer Producto
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Simple Counter (sin paginación) -->
        @if($productos->count() > 0)
        <div class="px-6 py-4 border-t border-gray-700/50 bg-gray-800/30">
            <div class="text-center text-sm text-gray-400">
                Mostrando <span class="text-green-400 font-semibold">{{ $productos->count() }}</span> productos en total
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.tooltip {
    position: relative;
}

.tooltip:hover::after {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    margin-bottom: 5px;
}

.tooltip:hover::before {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.8);
    margin-bottom: -5px;
    z-index: 1000;
}
</style>
@endsection