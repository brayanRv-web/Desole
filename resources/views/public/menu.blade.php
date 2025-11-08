@extends('layouts.public')

@section('title', 'Menú')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="text-center max-w-2xl mx-auto mb-12">
        <h1 class="text-4xl font-bold text-green-400 mb-4">Nuestro Menú</h1>
        <p class="text-gray-400">Explora nuestra deliciosa selección de platos y bebidas preparados con los mejores ingredientes.</p>
    </div>

    <!-- Filtros de Categoría -->
    <div class="mb-8">
        <x-public.components._category-filter 
            :categorias="$categorias"
            :categoriaActiva="request('categoria')" />
    </div>

    <!-- Lista de Productos -->
    @if($productos->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($productos as $producto)
                <x-public.components._product-card :producto="$producto" />
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-800/50 mb-4">
                <i class="fas fa-utensils text-2xl text-gray-500"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-300 mb-2">No hay productos disponibles</h3>
            <p class="text-gray-400">No se encontraron productos en esta categoría.</p>
        </div>
    @endif

    <!-- Paginación si es necesaria -->
    @if($productos->hasPages())
        <div class="mt-8">
            {{ $productos->links() }}
        </div>
    @endif

    <!-- Llamada a la acción -->
    @guest('cliente')
        <div class="mt-12 text-center bg-gray-800/50 border border-gray-700 rounded-xl p-8">
            <h3 class="text-2xl font-semibold text-white mb-4">¿Listo para ordenar?</h3>
            <p class="text-gray-400 mb-6">Inicia sesión para realizar tu pedido y disfrutar de nuestras deliciosas opciones.</p>
            <a href="{{ route('login') }}" 
               class="inline-flex items-center px-6 py-3 rounded-xl bg-green-600 hover:bg-green-500 text-white font-semibold transition-colors duration-200">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Iniciar Sesión para Ordenar
            </a>
        </div>
    @endguest
</div>
@endsection