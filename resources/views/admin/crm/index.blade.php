{{-- resources/views/admin/crm/index.blade.php --}}
@extends('admin.layout')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-green-400 flex items-center gap-2">
        <i class="fas fa-hands"></i> CRM - Gestión de Clientes
    </h2>
    <p class="text-gray-400 mt-1">Administra relaciones y fidelización de clientes</p>
</div>

<!-- Estadísticas Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gray-800 p-6 rounded-xl border border-blue-500/40">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Total Clientes</p>
                <p class="text-2xl font-bold text-white">{{ $estadisticas['totalClientes'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center">
                <i class="fas fa-users text-blue-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 p-6 rounded-xl border border-green-500/40">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Nuevos este Mes</p>
                <p class="text-2xl font-bold text-white">{{ $estadisticas['clientesNuevosMes'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center">
                <i class="fas fa-user-plus text-green-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 p-6 rounded-xl border border-purple-500/40">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Pedidos Recurrentes</p>
                <p class="text-2xl font-bold text-white">{{ $estadisticas['pedidosRecurrentes'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-500/20 flex items-center justify-center">
                <i class="fas fa-redo text-purple-400"></i>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 p-6 rounded-xl border border-yellow-500/40">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Clientes Frecuentes</p>
                <p class="text-2xl font-bold text-white">{{ $estadisticas['clientesFrecuentes'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-500/20 flex items-center justify-center">
                <i class="fas fa-star text-yellow-400"></i>
            </div>
        </div>
    </div>
</div>

<!-- Acciones Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <a href="{{ route('admin.crm.clientes') }}" 
       class="bg-gray-800 hover:bg-gray-750 p-6 rounded-xl border border-green-600/40 hover:border-green-500 transition-all duration-200 group">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-green-600/20 flex items-center justify-center group-hover:bg-green-600/30 transition">
                <i class="fas fa-list text-green-400"></i>
            </div>
            <div>
                <h3 class="font-bold text-white text-lg">Ver Todos los Clientes</h3>
                <p class="text-gray-400 text-sm">Lista completa y filtros</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.crm.campanas') }}" 
       class="bg-gray-800 hover:bg-gray-750 p-6 rounded-xl border border-blue-600/40 hover:border-blue-500 transition-all duration-200 group">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-600/20 flex items-center justify-center group-hover:bg-blue-600/30 transition">
                <i class="fas fa-envelope text-blue-400"></i>
            </div>
            <div>
                <h3 class="font-bold text-white text-lg">Campañas de Email</h3>
                <p class="text-gray-400 text-sm">Enviar promociones</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.crm.fidelidad') }}" 
       class="bg-gray-800 hover:bg-gray-750 p-6 rounded-xl border border-purple-600/40 hover:border-purple-500 transition-all duration-200 group">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-600/20 flex items-center justify-center group-hover:bg-purple-600/30 transition">
                <i class="fas fa-gift text-purple-400"></i>
            </div>
            <div>
                <h3 class="font-bold text-white text-lg">Programa de Fidelidad</h3>
                <p class="text-gray-400 text-sm">Gestionar puntos</p>
            </div>
        </div>
    </a>
</div>

<!-- Clientes Recientes (Placeholder) -->
<div class="bg-gray-800 p-6 rounded-xl border border-green-600/40">
    <h3 class="text-xl font-bold text-green-400 mb-4 flex items-center">
        <i class="fas fa-clock mr-2"></i> Clientes Recientes
    </h3>
    
    <div class="text-center py-8">
        <i class="fas fa-users text-4xl text-gray-600 mb-3"></i>
        <p class="text-gray-400">Los clientes aparecerán aquí cuando empieces a recibir pedidos</p>
        <p class="text-gray-500 text-sm mt-2">Este módulo se alimentará automáticamente del sistema de pedidos</p>
    </div>
</div>
@endsection