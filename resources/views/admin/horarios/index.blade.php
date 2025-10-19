@extends('admin.layout')

@section('content')
@php
    $estadoColors = [
        true => 'bg-green-500/20 text-green-400 border-green-500',
        false => 'bg-gray-500/20 text-gray-400 border-gray-500',
    ];

    $estadoIcons = [
        true => 'fas fa-check-circle',
        false => 'fas fa-times-circle',
    ];

    $estadoTextos = [
        true => 'Activo',
        false => 'Inactivo'
    ];
@endphp

<div class="flex flex-col space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-blue-400 flex items-center gap-3">
                <i class="fas fa-clock text-2xl"></i>
                Gestión de Horarios
            </h2>
            <p class="text-gray-400 mt-2">Administra los horarios de atención de la cafetería</p>
        </div>
        
        <div class="flex gap-3">
            <button type="submit" form="horariosForm"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-blue-500/25 transition-all duration-200 flex items-center gap-2 font-semibold group">
                <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                Guardar Cambios
            </button>
        </div>
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

    <!-- Info Panel -->
    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-400 mt-1"></i>
            <div class="flex-1">
                <h4 class="font-semibold text-blue-300 mb-2">Configuración de Horarios</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-200/80">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                        <span><strong>Horarios activos:</strong> Se mostrarán a los clientes</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                        <span><strong>Horarios inactivos:</strong> No estarán disponibles</span>
                    </div>
                </div>
                <div class="mt-3 text-xs text-blue-300">
                    <i class="fas fa-lightbulb mr-1"></i>
                    <strong>Tip:</strong> Puedes editar individualmente cada horario o usar el formulario masivo abajo
                </div>
            </div>
        </div>
    </div>

    <!-- Current Status -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-800/50 rounded-xl p-4 border border-blue-500/30">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-blue-600/20 flex items-center justify-center">
                    <i class="fas fa-calendar-day text-blue-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Días Activos</p>
                    <p class="text-2xl font-bold text-white">{{ $horarios->where('activo', true)->count() }}/7</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800/50 rounded-xl p-4 border border-green-500/30">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-green-600/20 flex items-center justify-center">
                    <i class="fas fa-store text-green-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Estado Actual</p>
                    <p class="text-2xl font-bold text-white">
                        @php
                            $horarioHoy = $horarios->firstWhere('dia_semana', strtolower(now()->isoFormat('dddd')));
                            $estaAbierto = $horarioHoy && $horarioHoy->estaAbierto();
                        @endphp
                        {{ $estaAbierto ? 'Abierto' : 'Cerrado' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-gray-800/50 rounded-xl p-4 border border-purple-500/30">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-purple-600/20 flex items-center justify-center">
                    <i class="fas fa-clock text-purple-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Hoy</p>
                    <p class="text-lg font-bold text-white">
                        {{ $horarioHoy ? $horarioHoy->horario_formateado : 'Cerrado' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Horarios Table -->
    <form id="horariosForm" action="{{ route('admin.horarios.update-multiple') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-gray-800/50 rounded-2xl border border-blue-700/30 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-700/40 to-blue-800/20 border-b border-blue-600/30">
                        <tr>
                            <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>Día
                            </th>
                            <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-door-open mr-2"></i>Apertura
                            </th>
                            <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-door-closed mr-2"></i>Cierre
                            </th>
                            <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-clock mr-2"></i>Horario
                            </th>
                            <th class="px-6 py-4 text-left text-blue-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-toggle-on mr-2"></i>Estado
                            </th>
                            <th class="px-6 py-4 text-center text-blue-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @foreach($horarios as $horario)
                        @php
                            $esHoy = $horario->dia_semana === strtolower(now()->isoFormat('dddd'));
                            $estaAbierto = $horario->estaAbierto();
                        @endphp
                        <tr class="hover:bg-blue-900/20 transition-all duration-200 group {{ $esHoy ? 'bg-blue-900/10 border-l-4 border-blue-500' : '' }}">
                            <!-- Day -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-600/20 border border-blue-600/30 flex items-center justify-center">
                                        <i class="fas fa-calendar-day text-blue-400 text-sm"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-white group-hover:text-blue-300 transition-colors">
                                            {{ $horario->dia_semana_completo }}
                                        </div>
                                        @if($esHoy)
                                        <div class="text-xs text-blue-400 mt-1 flex items-center gap-1">
                                            <i class="fas fa-star text-yellow-400"></i>
                                            <span>Hoy</span>
                                            @if($estaAbierto)
                                            <span class="text-green-400 ml-2">• Abierto ahora</span>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Apertura -->
                            <td class="px-6 py-4">
                                <input type="time" 
                                       name="horarios[{{ $horario->id }}][apertura]" 
                                       value="{{ $horario->apertura->format('H:i') }}"
                                       class="bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                            </td>

                            <!-- Cierre -->
                            <td class="px-6 py-4">
                                <input type="time" 
                                       name="horarios[{{ $horario->id }}][cierre]" 
                                       value="{{ $horario->cierre->format('H:i') }}"
                                       class="bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                            </td>

                            <!-- Horario Formateado -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-white font-medium">
                                    {{ $horario->horario_formateado }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $horario->apertura->format('H:i') }} - {{ $horario->cierre->format('H:i') }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="horarios[{{ $horario->id }}][activo]" 
                                               value="1" 
                                               {{ $horario->activo ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="relative w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        <span class="ms-3 text-sm font-medium text-gray-300">
                                            {{ $horario->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </label>
                                    @if($esHoy && $horario->activo)
                                    <div class="text-xs {{ $estaAbierto ? 'text-green-400' : 'text-red-400' }} flex items-center gap-1">
                                        <i class="fas fa-circle text-xs"></i>
                                        {{ $estaAbierto ? 'Abierto ahora' : 'Cerrado ahora' }}
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.horarios.edit', $horario) }}" 
                                       class="bg-blue-600/20 hover:bg-blue-600/40 border border-blue-500/50 hover:border-blue-400 text-blue-400 hover:text-blue-300 p-2 rounded-xl transition-all duration-200 group/edit tooltip"
                                       title="Editar horario individualmente">
                                        <i class="fas fa-edit group-hover/edit:scale-110 transition-transform"></i>
                                    </a>

                                    <!-- Quick Status Toggle -->
                                    <form action="{{ route('admin.horarios.toggle-status', $horario) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="bg-{{ $horario->activo ? 'yellow' : 'green' }}-600/20 hover:bg-{{ $horario->activo ? 'yellow' : 'green' }}-600/40 border border-{{ $horario->activo ? 'yellow' : 'green' }}-500/50 hover:border-{{ $horario->activo ? 'yellow' : 'green' }}-400 text-{{ $horario->activo ? 'yellow' : 'green' }}-400 hover:text-{{ $horario->activo ? 'yellow' : 'green' }}-300 p-2 rounded-xl transition-all duration-200 group/toggle tooltip"
                                                title="{{ $horario->activo ? 'Desactivar horario' : 'Activar horario' }}">
                                            <i class="fas {{ $horario->activo ? 'fa-pause' : 'fa-play' }} group-hover/toggle:scale-110 transition-transform"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-700/50 bg-gray-800/30">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-400">
                        Configura los horarios de atención para cada día de la semana
                    </div>
                    <button type="submit" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 font-semibold">
                        <i class="fas fa-save"></i>
                        Guardar Todos los Cambios
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Current Time Info -->
    <div class="bg-gray-800/30 rounded-xl p-4 border border-green-500/20">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-600/20 flex items-center justify-center">
                    <i class="fas fa-clock text-green-400"></i>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Información del Sistema</p>
                    <p class="text-white">
                        Hora actual del servidor: 
                        <span class="font-mono text-green-400">{{ now()->format('H:i:s') }}</span>
                    </p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-gray-400 text-sm">Día actual</p>
                <p class="text-white font-semibold">{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
        </div>
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
    background: rgba(0, 0, 0, 0.9);
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    margin-bottom: 8px;
    max-width: 250px;
    word-wrap: break-word;
    white-space: normal;
    text-align: center;
}

.tooltip:hover::before {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.9);
    margin-bottom: -2px;
    z-index: 1000;
}

/* Custom toggle switch */
input:checked ~ .dot {
    transform: translateX(100%);
}
</style>
@endsection