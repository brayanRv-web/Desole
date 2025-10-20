@extends('admin.layout')

@section('content')
<div class="flex flex-col space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-blue-400 flex items-center gap-3">
                <i class="fas fa-edit text-2xl"></i>
                Editar Horario
            </h2>
            <p class="text-gray-400 mt-2">Modificar horario para {{ $horario->dia_semana_completo }}</p>
        </div>
        
        <a href="{{ route('admin.horarios.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl transition-all duration-200 flex items-center gap-2 font-semibold group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Volver a Horarios
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

    <!-- Edit Form -->
    <div class="bg-gray-800/50 rounded-2xl border border-blue-700/30 shadow-xl overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.horarios.update', $horario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Day Info -->
                    <div class="bg-gray-700/30 rounded-xl p-6 border border-blue-500/20">
                        <h3 class="text-lg font-semibold text-blue-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-calendar-day"></i>
                            Información del Día
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-gray-400 text-sm">Día de la semana</label>
                                <p class="text-white text-lg font-semibold">{{ $horario->dia_semana_completo }}</p>
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm">Horario actual</label>
                                <p class="text-white text-lg">{{ $horario->horario_formateado }}</p>
                            </div>
                            <div>
                                <label class="text-gray-400 text-sm">Estado actual</label>
                                <p class="text-{{ $horario->activo ? 'green' : 'red' }}-400 font-semibold">
                                    {{ $horario->activo ? 'Activo' : 'Inactivo' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Time Settings -->
                    <div class="bg-gray-700/30 rounded-xl p-6 border border-blue-500/20">
                        <h3 class="text-lg font-semibold text-blue-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-clock"></i>
                            Configuración de Horario
                        </h3>
                        <div class="space-y-4">
                            <!-- Apertura -->
                            <div>
                                <label for="apertura" class="block text-gray-400 text-sm mb-2">
                                    <i class="fas fa-door-open mr-1"></i>Hora de Apertura
                                </label>
                                <input type="time" 
                                       id="apertura"
                                       name="apertura" 
                                       value="{{ $horario->apertura->format('H:i') }}"
                                       class="w-full bg-gray-600 border border-gray-500 rounded-lg px-4 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition text-lg"
                                       required>
                                @error('apertura')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Cierre -->
                            <div>
                                <label for="cierre" class="block text-gray-400 text-sm mb-2">
                                    <i class="fas fa-door-closed mr-1"></i>Hora de Cierre
                                </label>
                                <input type="time" 
                                       id="cierre"
                                       name="cierre" 
                                       value="{{ $horario->cierre->format('H:i') }}"
                                       class="w-full bg-gray-600 border border-gray-500 rounded-lg px-4 py-3 text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition text-lg"
                                       required>
                                @error('cierre')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="flex items-center justify-between p-4 bg-gray-600/30 rounded-lg">
                                <div>
                                    <label class="text-gray-300 font-medium">Estado del Horario</label>
                                    <p class="text-gray-400 text-sm">Activar o desactivar este horario</p>
                                </div>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="activo" 
                                           value="1" 
                                           {{ $horario->activo ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="relative w-14 h-7 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center mt-8 pt-6 border-t border-gray-700">
                    <div class="text-sm text-gray-400">
                        Los cambios se aplicarán inmediatamente al sistema
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.horarios.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg transition-all duration-200 font-semibold">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow hover:shadow-blue-500/30 transition-all duration-200 font-semibold flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview -->
    <div class="bg-gray-800/30 rounded-xl p-6 border border-green-500/20">
        <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center gap-2">
            <i class="fas fa-eye"></i>
            Vista Previa
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="text-center p-4 bg-gray-700/50 rounded-lg">
                <p class="text-gray-400 text-sm">Horario Actual</p>
                <p class="text-white text-xl font-semibold mt-2">{{ $horario->horario_formateado }}</p>
                <p class="text-{{ $horario->activo ? 'green' : 'red' }}-400 text-sm mt-1">
                    {{ $horario->activo ? 'Activo' : 'Inactivo' }}
                </p>
            </div>
            <div class="text-center p-4 bg-gray-700/50 rounded-lg">
                <p class="text-gray-400 text-sm">Estado Actual</p>
                <p class="text-{{ $horario->estaAbierto() ? 'green' : 'red' }}-400 text-xl font-semibold mt-2">
                    {{ $horario->estaAbierto() ? 'Abierto' : 'Cerrado' }}
                </p>
                <p class="text-gray-400 text-sm mt-1">
                    {{ $horario->estaAbierto() ? 'Disponible para clientes' : 'No disponible' }}
                </p>
            </div>
        </div>
    </div>
</div>

<style>
input:checked ~ .dot {
    transform: translateX(100%);
}
</style>
@endsection
