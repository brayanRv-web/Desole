@extends('admin.layout')

@section('content')
@php
    // Calcular estado del local
    $horarioHoy = $horarios->firstWhere('dia_semana', strtolower(now()->isoFormat('dddd')));
    $estaAbierto = $horarioHoy && $horarioHoy->activo && $horarioHoy->estaAbierto();
@endphp

<div class="flex justify-between items-center mb-8">
    <h2 class="text-3xl font-bold text-green-400 border-b border-green-700 pb-3">
        <i class="fas fa-tachometer-alt mr-2"></i>Panel Principal
    </h2>
    <div class="text-sm text-gray-400">
        <i class="fas fa-calendar-alt mr-1"></i> {{ now()->format('d/m/Y') }}
        <span class="ml-2 text-green-400">• Año 2025</span>
    </div>
</div>

<!-- Estadísticas rápidas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">
    <!-- Tarjeta: Estado Actual -->
    <div class="bg-gray-800 p-5 rounded-xl shadow-md border border-blue-600/60 hover:shadow-blue-500/20 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Estado Actual</p>
                <h3 class="text-2xl font-bold text-white mt-1">
                    {{ $estaAbierto ? 'Abierto' : 'Cerrado' }}
                </h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-600/20 flex items-center justify-center">
                <i class="fas fa-store text-blue-400 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-700">
            <span class="text-{{ $estaAbierto ? 'green' : 'red' }}-400 text-sm flex items-center">
                <i class="fas fa-{{ $estaAbierto ? 'check' : 'times' }}-circle mr-1"></i> 
                {{ $estaAbierto ? 'Disponible' : 'No disponible' }}
            </span>
            @if($horarioHoy)
            <div class="text-gray-400 text-xs mt-1">
                {{ $horarioHoy->horario_formateado }}
            </div>
            @endif
        </div>
    </div>

    <!-- Tarjeta: Total Productos -->
    <div class="bg-gray-800 p-5 rounded-xl shadow-md border border-green-600/60 hover:shadow-green-500/20 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Total Productos</p>
                <h3 class="text-2xl font-bold text-white mt-1">{{ $totalProductos }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-600/20 flex items-center justify-center">
                <i class="fas fa-box text-green-400 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-700">
            @if($productosActivos > 0)
            <span class="text-green-400 text-sm flex items-center">
                <i class="fas fa-check-circle mr-1"></i> {{ $productosActivos }} activos
            </span>
            @else
            <span class="text-yellow-400 text-sm flex items-center">
                <i class="fas fa-info-circle mr-1"></i> Sin productos
            </span>
            @endif
        </div>
    </div>

    <!-- Tarjeta: Personal -->
    <div class="bg-gray-800 p-5 rounded-xl shadow-md border border-purple-600/60 hover:shadow-purple-500/20 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Personal</p>
                <h3 class="text-2xl font-bold text-white mt-1">{{ $totalUsuarios }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-600/20 flex items-center justify-center">
                <i class="fas fa-users text-purple-400 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-700">
            <div class="grid grid-cols-2 gap-1 text-xs">
                <div class="text-blue-400">
                    <i class="fas fa-user-tie mr-1"></i> {{ $totalEmpleados }}
                </div>
                <div class="text-red-400">
                    <i class="fas fa-shield-alt mr-1"></i> {{ $totalAdmins }}
                </div>
            </div>
            <div class="text-green-400 text-xs mt-1">
                <i class="fas fa-check-circle mr-1"></i> {{ $usuariosActivos }} activos
            </div>
        </div>
    </div>

    <!-- Tarjeta: Promociones Activas -->
    <div class="bg-gray-800 p-5 rounded-xl shadow-md border border-yellow-600/60 hover:shadow-yellow-500/20 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Promociones</p>
                <h3 class="text-2xl font-bold text-white mt-1">{{ $totalPromocionesValidas }}</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-600/20 flex items-center justify-center">
                <i class="fas fa-tags text-yellow-400 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-700">
            @if($totalPromociones > 0)
            <span class="text-green-400 text-sm flex items-center">
                <i class="fas fa-check-circle mr-1"></i> {{ $totalPromocionesValidas }} válidas
            </span>
            <span class="text-gray-400 text-xs block mt-1">
                {{ $totalPromociones }} totales
            </span>
            @else
            <span class="text-yellow-400 text-sm flex items-center">
                <i class="fas fa-info-circle mr-1"></i> Sin promociones
            </span>
            @endif
        </div>
    </div>

    <!-- Tarjeta: Horarios Activos -->
    <div class="bg-gray-800 p-5 rounded-xl shadow-md border border-indigo-600/60 hover:shadow-indigo-500/20 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Días Activos</p>
                <h3 class="text-2xl font-bold text-white mt-1">{{ $horarios->where('activo', true)->count() }}/7</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-indigo-600/20 flex items-center justify-center">
                <i class="fas fa-calendar-day text-indigo-400 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-700">
            <span class="text-green-400 text-sm flex items-center">
                <i class="fas fa-clock mr-1"></i> {{ now()->isoFormat('dddd') }}
            </span>
            <div class="text-gray-400 text-xs mt-1">
                Hoy: {{ $horarioHoy ? $horarioHoy->horario_formateado : 'Cerrado' }}
            </div>
        </div>
    </div>

    <!-- Tarjeta: Sistema -->
    <div class="bg-gray-800 p-5 rounded-xl shadow-md border border-gray-600/60 hover:shadow-gray-500/20 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-400 text-sm">Sistema</p>
                <h3 class="text-2xl font-bold text-white mt-1">Online</h3>
            </div>
            <div class="w-12 h-12 rounded-full bg-gray-600/20 flex items-center justify-center">
                <i class="fas fa-server text-gray-400 text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-700">
            <span class="text-green-400 text-sm flex items-center">
                <i class="fas fa-circle mr-1 text-xs"></i> Todo operativo
            </span>
            <div class="text-gray-400 text-xs mt-1">
                {{ now()->format('H:i:s') }}
            </div>
        </div>
    </div>
</div>

<!-- Sección de Acciones Rápidas -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-green-400 mb-4 flex items-center">
        <i class="fas fa-bolt mr-2"></i> Acciones Rápidas
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
        <a href="{{ route('admin.productos.create') }}" class="bg-gray-800 hover:bg-gray-750 p-4 rounded-lg border border-green-600/40 hover:border-green-500 transition-all duration-200 flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-green-600/20 flex items-center justify-center group-hover:bg-green-600/30 transition">
                <i class="fas fa-plus text-green-400"></i>
            </div>
            <div>
                <p class="font-medium text-white">Nuevo Producto</p>
                <p class="text-xs text-gray-400">Agregar al menú</p>
            </div>
        </a>
        
        <a href="{{ route('admin.usuarios.create') }}" class="bg-gray-800 hover:bg-gray-750 p-4 rounded-lg border border-purple-600/40 hover:border-purple-500 transition-all duration-200 flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-purple-600/20 flex items-center justify-center group-hover:bg-purple-600/30 transition">
                <i class="fas fa-user-plus text-purple-400"></i>
            </div>
            <div>
                <p class="font-medium text-white">Nuevo Personal</p>
                <p class="text-xs text-gray-400">Agregar usuario</p>
            </div>
        </a>
        
        <a href="{{ route('admin.promociones.create') }}" class="bg-gray-800 hover:bg-gray-750 p-4 rounded-lg border border-yellow-600/40 hover:border-yellow-500 transition-all duration-200 flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-yellow-600/20 flex items-center justify-center group-hover:bg-yellow-600/30 transition">
                <i class="fas fa-tag text-yellow-400"></i>
            </div>
            <div>
                <p class="font-medium text-white">Nueva Promoción</p>
                <p class="text-xs text-gray-400">Crear oferta</p>
            </div>
        </a>

        <a href="{{ route('admin.horarios.index') }}" class="bg-gray-800 hover:bg-gray-750 p-4 rounded-lg border border-indigo-600/40 hover:border-indigo-500 transition-all duration-200 flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-indigo-600/20 flex items-center justify-center group-hover:bg-indigo-600/30 transition">
                <i class="fas fa-clock text-indigo-400"></i>
            </div>
            <div>
                <p class="font-medium text-white">Horarios</p>
                <p class="text-xs text-gray-400">Configurar</p>
            </div>
        </a>
        
        <a href="{{ route('admin.usuarios.index') }}" class="bg-gray-800 hover:bg-gray-750 p-4 rounded-lg border border-blue-600/40 hover:border-blue-500 transition-all duration-200 flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-blue-600/20 flex items-center justify-center group-hover:bg-blue-600/30 transition">
                <i class="fas fa-cog text-blue-400"></i>
            </div>
            <div>
                <p class="font-medium text-white">Gestionar</p>
                <p class="text-xs text-gray-400">Administrar</p>
            </div>
        </a>
        
        <div class="bg-gray-800 p-4 rounded-lg border border-gray-600/40 flex items-center gap-3 opacity-70 cursor-not-allowed">
            <div class="w-10 h-10 rounded-full bg-gray-600/20 flex items-center justify-center">
                <i class="fas fa-chart-bar text-gray-400"></i>
            </div>
            <div>
                <p class="font-medium text-gray-400">Reportes</p>
                <p class="text-xs text-gray-500">Próximamente</p>
            </div>
        </div>
    </div>
</div>

<!-- Módulos Principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <!-- Módulo: Estado del Local -->
    <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-blue-600/60">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-xl text-blue-400 flex items-center">
                <i class="fas fa-store mr-2"></i> Estado del Local
            </h3>
            <span class="bg-{{ $estaAbierto ? 'green' : 'red' }}-600/20 text-{{ $estaAbierto ? 'green' : 'red' }}-400 text-xs px-2 py-1 rounded-full">
                {{ $estaAbierto ? 'Abierto' : 'Cerrado' }}
            </span>
        </div>
        
        <!-- Información del día actual -->
        <div class="bg-gray-750 rounded-lg p-4 mb-4">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-day text-blue-400"></i>
                    <span class="text-white font-semibold">{{ now()->isoFormat('dddd') }}</span>
                </div>
                <div class="text-{{ $estaAbierto ? 'green' : 'red' }}-400 text-sm">
                    <i class="fas fa-{{ $estaAbierto ? 'check' : 'times' }}-circle mr-1"></i>
                    {{ $estaAbierto ? 'Abierto ahora' : 'Cerrado ahora' }}
                </div>
            </div>
            
            @if($horarioHoy)
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs">Horario de hoy</p>
                    <p class="text-white font-semibold">{{ $horarioHoy->horario_formateado }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs">Estado</p>
                    <p class="text-{{ $horarioHoy->activo ? 'green' : 'red' }}-400 font-semibold">
                        {{ $horarioHoy->activo ? 'Activo' : 'Inactivo' }}
                    </p>
                </div>
            </div>
            @else
            <p class="text-yellow-400 text-sm">No hay horario configurado para hoy</p>
            @endif
        </div>

        <p class="text-gray-300 mb-4">Gestiona los horarios de atención y estado del local.</p>
        <div class="flex gap-3">
            <a href="{{ route('admin.horarios.index') }}" 
               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow hover:shadow-blue-500/30 transition text-center">
                Ver Horarios
            </a>
            <a href="{{ route('admin.horarios.index') }}" 
               class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-cog"></i>
            </a>
        </div>
    </div>

    <!-- Módulo: Productos -->
    <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-green-600/60">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-xl text-green-400 flex items-center">
                <i class="fas fa-box mr-2"></i> Productos
            </h3>
            @if($totalProductos > 0)
            <span class="bg-green-600/20 text-green-400 text-xs px-2 py-1 rounded-full">{{ $totalProductos }} registrados</span>
            @else
            <span class="bg-yellow-600/20 text-yellow-400 text-xs px-2 py-1 rounded-full">Sin productos</span>
            @endif
        </div>
        <p class="text-gray-300 mb-4">Administra los productos del menú de la cafetería.</p>
        
        @if($productosRecientes->count() > 0)
        <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-400 mb-2">Productos recientes:</h4>
            <div class="space-y-2">
                @foreach($productosRecientes->take(2) as $producto)
                <div class="flex items-center gap-3 p-2 bg-gray-750 rounded">
                    <div class="w-8 h-8 bg-green-600/20 rounded flex items-center justify-center">
                        <i class="fas fa-utensils text-green-400 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-white text-sm">{{ Str::limit($producto->nombre, 18) }}</p>
                        <p class="text-gray-400 text-xs">${{ number_format($producto->precio, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('admin.productos.index') }}" 
               class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow hover:shadow-green-500/30 transition text-center">
                Gestionar
            </a>
            <a href="{{ route('admin.productos.create') }}" 
               class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>

    <!-- Módulo: Personal -->
    <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-purple-600/60">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-xl text-purple-400 flex items-center">
                <i class="fas fa-users mr-2"></i> Personal
            </h3>
            @if($totalUsuarios > 0)
            <span class="bg-purple-600/20 text-purple-400 text-xs px-2 py-1 rounded-full">{{ $totalUsuarios }} registrados</span>
            @else
            <span class="bg-yellow-600/20 text-yellow-400 text-xs px-2 py-1 rounded-full">Sin personal</span>
            @endif
        </div>
        <p class="text-gray-300 mb-4">Administra administradores y empleados del sistema.</p>
        
        <!-- Estadísticas de personal -->
        @if($totalUsuarios > 0)
        <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-400 mb-2">Resumen:</h4>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="flex items-center gap-2 p-2 bg-gray-750 rounded">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-gray-300">Admins: {{ $totalAdmins }}</span>
                </div>
                <div class="flex items-center gap-2 p-2 bg-gray-750 rounded">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span class="text-gray-300">Empleados: {{ $totalEmpleados }}</span>
                </div>
                <div class="flex items-center gap-2 p-2 bg-gray-750 rounded">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-gray-300">Activos: {{ $usuariosActivos }}</span>
                </div>
                <div class="flex items-center gap-2 p-2 bg-gray-750 rounded">
                    <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                    <span class="text-gray-300">Inactivos: {{ $totalUsuarios - $usuariosActivos }}</span>
                </div>
            </div>
        </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('admin.usuarios.index') }}" 
               class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg shadow hover:shadow-purple-500/30 transition text-center">
                Gestionar
            </a>
            <a href="{{ route('admin.usuarios.create') }}" 
               class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>

    <!-- Módulo: Promociones -->
    <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-yellow-600/60">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-xl text-yellow-400 flex items-center">
                <i class="fas fa-tags mr-2"></i> Promociones
            </h3>
            @if($totalPromociones > 0)
            <span class="bg-yellow-600/20 text-yellow-400 text-xs px-2 py-1 rounded-full">{{ $totalPromocionesValidas }}/{{ $totalPromociones }} válidas</span>
            @else
            <span class="bg-yellow-600/20 text-yellow-400 text-xs px-2 py-1 rounded-full">Sin promociones</span>
            @endif
        </div>
        <p class="text-gray-300 mb-4">Gestiona descuentos y ofertas especiales del menú.</p>
        
        <!-- Estadísticas de promociones -->
        @if($totalPromociones > 0)
        <div class="mb-4">
            <h4 class="text-sm font-medium text-gray-400 mb-2">Estado:</h4>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div class="flex items-center gap-2 p-2 bg-gray-750 rounded">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-gray-300">Válidas: {{ $totalPromocionesValidas }}</span>
                </div>
                <div class="flex items-center gap-2 p-2 bg-gray-750 rounded">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    <span class="text-gray-300">Activadas: {{ $promocionesActivadas }}</span>
                </div>
                <div class="flex items-center gap-2 p-2 bg-gray-750 rounded">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-gray-300">Problemas: {{ $promocionesConProblemas }}</span>
                </div>
                <div class="flex items-center gap-2 p-2 bg-gray-750 rounded">
                    <div class="w-3 h-3 bg-gray-500 rounded-full"></div>
                    <span class="text-gray-300">Total: {{ $totalPromociones }}</span>
                </div>
            </div>
        </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('admin.promociones.index') }}" 
               class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg shadow hover:shadow-yellow-500/30 transition text-center">
                Gestionar
            </a>
            <a href="{{ route('admin.promociones.create') }}" 
               class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-plus"></i>
            </a>
        </div>
    </div>

    <!-- Módulo: Pedidos (En desarrollo) -->
    <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-gray-600/60 opacity-80">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-xl text-gray-400 flex items-center">
                <i class="fas fa-clipboard-list mr-2"></i> Pedidos
            </h3>
            <span class="bg-gray-600/20 text-gray-400 text-xs px-2 py-1 rounded-full">En desarrollo</span>
        </div>
        <p class="text-gray-400 mb-5">Módulo de pedidos en desarrollo. Próximamente podrás gestionar los pedidos de clientes.</p>
        <div class="flex gap-3">
            <div class="flex-1 bg-gray-700 text-gray-400 px-4 py-2 rounded-lg text-center cursor-not-allowed">
                No disponible
            </div>
            <div class="bg-gray-700 text-gray-400 px-4 py-2 rounded-lg cursor-not-allowed">
                <i class="fas fa-tools"></i>
            </div>
        </div>
    </div>

    <!-- Módulo: Reportes (En desarrollo) -->
    <div class="bg-gray-800 p-6 rounded-xl shadow-md border border-gray-600/60 opacity-80">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-xl text-gray-400 flex items-center">
                <i class="fas fa-chart-bar mr-2"></i> Reportes
            </h3>
            <span class="bg-gray-600/20 text-gray-400 text-xs px-2 py-1 rounded-full">En desarrollo</span>
        </div>
        <p class="text-gray-400 mb-5">Módulo de reportes en desarrollo. Próximamente tendrás análisis de ventas y estadísticas.</p>
        <div class="flex gap-3">
            <div class="flex-1 bg-gray-700 text-gray-400 px-4 py-2 rounded-lg text-center cursor-not-allowed">
                No disponible
            </div>
            <div class="bg-gray-700 text-gray-400 px-4 py-2 rounded-lg cursor-not-allowed">
                <i class="fas fa-tools"></i>
            </div>
        </div>
    </div>
</div>

<!-- Resumen del Sistema -->
<div class="mt-8 bg-gray-800 p-6 rounded-xl shadow-md border border-green-600/60">
    <h3 class="font-bold text-xl text-green-400 mb-4 flex items-center">
        <i class="fas fa-history mr-2"></i> Resumen del Sistema
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Estado del Local -->
        <div class="flex items-center gap-4 p-4 bg-gray-750 rounded-lg">
            <div class="w-12 h-12 rounded-full bg-blue-600/20 flex items-center justify-center">
                <i class="fas fa-store text-blue-400"></i>
            </div>
            <div class="flex-1">
                <p class="text-white font-medium">Estado del Local</p>
                <p class="text-{{ $estaAbierto ? 'green' : 'red' }}-400 text-sm">
                    {{ $estaAbierto ? 'Abierto' : 'Cerrado' }} - {{ $horarioHoy ? $horarioHoy->horario_formateado : 'Sin horario' }}
                </p>
            </div>
        </div>

        <!-- Productos -->
        <div class="flex items-center gap-4 p-4 bg-gray-750 rounded-lg">
            <div class="w-12 h-12 rounded-full bg-green-600/20 flex items-center justify-center">
                <i class="fas fa-box text-green-400"></i>
            </div>
            <div class="flex-1">
                <p class="text-white font-medium">Productos</p>
                <p class="text-gray-400 text-sm">{{ $totalProductos }} registrados, {{ $productosActivos }} activos</p>
            </div>
        </div>

        <!-- Personal -->
        <div class="flex items-center gap-4 p-4 bg-gray-750 rounded-lg">
            <div class="w-12 h-12 rounded-full bg-purple-600/20 flex items-center justify-center">
                <i class="fas fa-users text-purple-400"></i>
            </div>
            <div class="flex-1">
                <p class="text-white font-medium">Personal</p>
                <p class="text-gray-400 text-sm">{{ $totalUsuarios }} usuarios, {{ $usuariosActivos }} activos</p>
            </div>
        </div>

        <!-- Promociones -->
        <div class="flex items-center gap-4 p-4 bg-gray-750 rounded-lg">
            <div class="w-12 h-12 rounded-full bg-yellow-600/20 flex items-center justify-center">
                <i class="fas fa-tags text-yellow-400"></i>
            </div>
            <div class="flex-1">
                <p class="text-white font-medium">Promociones</p>
                <p class="text-gray-400 text-sm">{{ $totalPromocionesValidas }} válidas de {{ $totalPromociones }} totales</p>
            </div>
        </div>
    </div>
</div>
@endsection