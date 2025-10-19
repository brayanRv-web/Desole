@extends('admin.layout')

@section('content')
<div class="flex flex-col space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-green-400 flex items-center gap-3">
                <i class="fas fa-edit text-2xl"></i>
                Editar Promoción
            </h2>
            <p class="text-gray-400 mt-2">Modifica los detalles de la promoción</p>
        </div>
        
        <a href="{{ route('admin.promociones.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl shadow-lg transition-all duration-200 flex items-center gap-2 font-semibold group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            Volver a Promociones
        </a>
    </div>

    <!-- Alert Messages -->
    @if($errors->any())
        <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-6 py-4 rounded-xl">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-exclamation-circle text-red-400"></i>
                <span class="font-medium">Por favor corrige los siguientes errores:</span>
            </div>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Promotion Status Alert -->
    @if(!$promocion->esValida() && $promocion->activa)
    <div class="bg-yellow-500/10 border border-yellow-500/50 text-yellow-400 px-6 py-4 rounded-xl">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            <div class="flex-1">
                <span class="font-medium">Esta promoción tiene problemas de validez:</span>
                <ul class="text-sm mt-1 space-y-1">
                    @if(now() < $promocion->fecha_inicio)
                        <li>• La fecha de inicio está en el futuro</li>
                    @endif
                    @if(now() > $promocion->fecha_fin)
                        <li>• La fecha de fin ya pasó</li>
                    @endif
                    @if($promocion->productos_inactivos->count() > 0)
                        <li>• {{ $promocion->productos_inactivos->count() }} producto(s) están inactivos</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Form -->
    <div class="bg-gray-800/50 rounded-2xl border border-green-700/30 shadow-xl p-6">
        <!-- ✅ FORMULARIO PRINCIPAL CORREGIDO - USAR PUT -->
        <form action="{{ route('admin.promociones.update', $promocion) }}" method="POST" id="editPromocionForm">
            @csrf
            @method('PUT') <!-- ✅ IMPORTANTE: USAR PUT -->

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Promotion Name -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-tag mr-2 text-green-400"></i>
                            Nombre de la Promoción *
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="{{ old('nombre', $promocion->nombre) }}"
                               class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all duration-200"
                               placeholder="Ej: Descuento de Verano 2025"
                               required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-align-left mr-2 text-green-400"></i>
                            Descripción
                        </label>
                        <textarea 
                            id="descripcion" 
                            name="descripcion" 
                            rows="3"
                            class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all duration-200"
                            placeholder="Describe los detalles de la promoción, condiciones especiales, etc...">{{ old('descripcion', $promocion->descripcion) }}</textarea>
                    </div>

                    <!-- Discount Section -->
                    <div class="bg-gray-700/30 border border-gray-600/50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-percentage"></i>
                            Configuración del Descuento
                        </h3>
                        
                        <!-- Discount Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-3">
                                <i class="fas fa-cogs mr-2 text-green-400"></i>
                                Tipo de Descuento *
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative">
                                    <input type="radio" 
                                           name="tipo_descuento" 
                                           value="porcentaje" 
                                           {{ old('tipo_descuento', $promocion->tipo_descuento) == 'porcentaje' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-600 rounded-xl cursor-pointer transition-all duration-200
                                                peer-checked:border-green-500 peer-checked:bg-green-500/10
                                                hover:border-gray-500">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-500 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-white">Porcentaje</div>
                                                <div class="text-xs text-gray-400">Ej: 20% de descuento</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="relative">
                                    <input type="radio" 
                                           name="tipo_descuento" 
                                           value="monto_fijo" 
                                           {{ old('tipo_descuento', $promocion->tipo_descuento) == 'monto_fijo' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-600 rounded-xl cursor-pointer transition-all duration-200
                                                peer-checked:border-green-500 peer-checked:bg-green-500/10
                                                hover:border-gray-500">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full border-2 border-gray-500 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-white">Monto Fijo</div>
                                                <div class="text-xs text-gray-400">Ej: $5.00 de descuento</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Discount Value -->
                        <div>
                            <label for="valor_descuento" class="block text-sm font-medium text-gray-300 mb-2">
                                <i class="fas fa-dollar-sign mr-2 text-green-400"></i>
                                Valor del Descuento *
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       step="0.01" 
                                       min="0"
                                       id="valor_descuento" 
                                       name="valor_descuento" 
                                       value="{{ old('valor_descuento', $promocion->valor_descuento) }}"
                                       class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all duration-200 pr-20"
                                       placeholder="0.00"
                                       required>
                                <div id="discount-suffix" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 font-medium">
                                    {{ $promocion->tipo_descuento === 'porcentaje' ? '%' : '$' }}
                                </div>
                            </div>
                            <p id="discount-example" class="text-xs text-gray-400 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                <span id="example-text">
                                    @if($promocion->tipo_descuento === 'porcentaje')
                                        Ejemplo: {{ $promocion->valor_descuento }}% = ${{ number_format(100 * (1 - $promocion->valor_descuento/100), 2) }} de $100.00
                                    @else
                                        Ejemplo: ${{ number_format($promocion->valor_descuento, 2) }} = ${{ number_format(100 - $promocion->valor_descuento, 2) }} de $100.00
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Dates Section -->
                    <div class="bg-gray-700/30 border border-gray-600/50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-calendar-alt"></i>
                            Período de Vigencia
                        </h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-300 mb-2">
                                    <i class="fas fa-play-circle mr-2 text-green-400"></i>
                                    Fecha de Inicio *
                                </label>
                                <input type="datetime-local" 
                                       id="fecha_inicio" 
                                       name="fecha_inicio" 
                                       value="{{ old('fecha_inicio', $promocion->fecha_inicio->format('Y-m-d\TH:i')) }}"
                                       class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all duration-200"
                                       required>
                            </div>

                            <div>
                                <label for="fecha_fin" class="block text-sm font-medium text-gray-300 mb-2">
                                    <i class="fas fa-stop-circle mr-2 text-green-400"></i>
                                    Fecha de Fin *
                                </label>
                                <input type="datetime-local" 
                                       id="fecha_fin" 
                                       name="fecha_fin" 
                                       value="{{ old('fecha_fin', $promocion->fecha_fin->format('Y-m-d\TH:i')) }}"
                                       class="w-full bg-gray-700/50 border border-gray-600/50 rounded-xl px-4 py-3 text-white focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-all duration-200"
                                       required>
                            </div>
                        </div>

                        <!-- ✅ BOTONES DE PRUEBA RÁPIDA PARA 2025 -->
                        <div class="mt-4 flex gap-2 flex-wrap">
                            <button type="button" id="test2min" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition-all duration-200 font-semibold flex items-center gap-2">
                                <i class="fas fa-bolt"></i> Prueba 2 Minutos
                            </button>
                            <button type="button" id="test5min" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-all duration-200 font-semibold flex items-center gap-2">
                                <i class="fas fa-clock"></i> Prueba 5 Minutos
                            </button>
                            <button type="button" id="test1hour" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-all duration-200 font-semibold flex items-center gap-2">
                                <i class="fas fa-hourglass"></i> Prueba 1 Hora
                            </button>
                        </div>

                        <div class="mt-3 p-3 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                            <div class="flex items-center gap-2 text-sm text-blue-300">
                                <i class="fas fa-info-circle"></i>
                                <span>Vigencia actual: 
                                    <strong>{{ $promocion->fecha_inicio->format('d/m/Y H:i') }}</strong> 
                                    hasta 
                                    <strong>{{ $promocion->fecha_fin->format('d/m/Y H:i') }}</strong>
                                </span>
                            </div>
                            <div class="text-xs text-blue-400 mt-1">
                                <i class="fas fa-calendar-check"></i>
                                Año actual: 2025
                            </div>
                        </div>
                    </div>

                    <!-- Products Selection -->
                    <div class="bg-gray-700/30 border border-gray-600/50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-boxes"></i>
                            Productos Incluidos
                            <span class="text-sm text-gray-400 font-normal">({{ $promocion->productos->count() }} actualmente)</span>
                        </h3>
                        
                        @if($productos->count() > 0)
                        <div class="max-h-60 overflow-y-auto">
                            <div class="space-y-2">
                                @foreach($productos as $producto)
                                @php
                                    $isSelected = in_array($producto->id, old('productos', $productosSeleccionados));
                                    $isInactive = $producto->estado !== 'activo';
                                @endphp
                                <label class="flex items-center gap-3 p-3 rounded-lg transition-colors cursor-pointer border
                                            {{ $isSelected ? 'bg-green-500/10 border-green-500/30' : 'bg-transparent border-transparent hover:bg-gray-600/30 hover:border-gray-500/50' }}
                                            {{ $isInactive ? 'opacity-60' : '' }}">
                                    <input type="checkbox" 
                                           name="productos[]" 
                                           value="{{ $producto->id }}"
                                           {{ $isSelected ? 'checked' : '' }}
                                           {{ $isInactive ? 'disabled' : '' }}
                                           class="rounded border-gray-600 bg-gray-700 text-green-500 focus:ring-green-500 focus:ring-2 transform scale-110
                                                  {{ $isInactive ? 'cursor-not-allowed opacity-50' : '' }}">
                                    <div class="flex-1">
                                        <div class="text-white font-medium flex items-center gap-2">
                                            {{ $producto->nombre }}
                                            @if($producto->imagen)
                                                <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                                     alt="{{ $producto->nombre }}"
                                                     class="w-6 h-6 rounded object-cover">
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-400 flex items-center gap-4 mt-1">
                                            <span><i class="fas fa-dollar-sign text-xs"></i> {{ number_format($producto->precio, 2) }}</span>
                                            <span class="text-xs px-2 py-1 rounded-full 
                                                {{ $producto->estado === 'activo' ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 
                                                   ($producto->estado === 'inactivo' ? 'bg-gray-500/20 text-gray-400 border border-gray-500/30' : 
                                                   'bg-red-500/20 text-red-400 border border-red-500/30') }}">
                                                {{ ucfirst($producto->estado) }}
                                            </span>
                                        </div>
                                        @if($isInactive)
                                        <div class="text-xs text-yellow-400 mt-1 flex items-center gap-1">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Producto no disponible para promociones
                                        </div>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
                            <i class="fas fa-info-circle"></i>
                            Los productos inactivos aparecen atenuados y no se pueden seleccionar
                        </p>
                        @else
                        <div class="text-center py-6">
                            <i class="fas fa-box-open text-4xl text-gray-500 mb-3"></i>
                            <p class="text-gray-400 mb-3">No hay productos activos disponibles</p>
                            <a href="{{ route('admin.productos.create') }}" 
                               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition-colors inline-flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                                Crear Producto
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- Current Promotion Status -->
                    <div class="bg-gray-700/30 border border-gray-600/50 rounded-xl p-4">
                        <h3 class="text-lg font-semibold text-green-400 mb-4 flex items-center gap-2">
                            <i class="fas fa-chart-bar"></i>
                            Estado Actual
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="text-center p-3 rounded-lg bg-gray-600/30">
                                <div class="text-gray-400">Estado</div>
                                <div class="font-semibold text-white mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs 
                                                {{ $promocion->activa ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-gray-500/20 text-gray-400 border border-gray-500/30' }}">
                                        <i class="fas {{ $promocion->activa ? 'fa-play-circle' : 'fa-pause-circle' }} mr-1"></i>
                                        {{ $promocion->activa ? 'Activada' : 'Desactivada' }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-center p-3 rounded-lg bg-gray-600/30">
                                <div class="text-gray-400">Validez</div>
                                <div class="font-semibold text-white mt-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs 
                                                {{ $promocion->esValida() ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30' }}">
                                        <i class="fas {{ $promocion->esValida() ? 'fa-check-circle' : 'fa-exclamation-triangle' }} mr-1"></i>
                                        {{ $promocion->esValida() ? 'Válida' : 'Revisar' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 text-xs text-gray-400">
                            <i class="fas fa-clock mr-1"></i>
                            Creada: {{ $promocion->created_at->format('d/m/Y H:i') }}
                            @if($promocion->created_at != $promocion->updated_at)
                                <br><i class="fas fa-sync-alt mr-1"></i>
                                Actualizada: {{ $promocion->updated_at->format('d/m/Y H:i') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-700/50">
                <div class="flex gap-2">
                    <!-- ✅ FORMULARIO SEPARADO para toggle-status -->
                    <form action="{{ route('admin.promociones.toggle-status', $promocion) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="bg-{{ $promocion->activa ? 'yellow' : 'green' }}-600 hover:bg-{{ $promocion->activa ? 'yellow' : 'green' }}-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-semibold flex items-center gap-2">
                            <i class="fas {{ $promocion->activa ? 'fa-pause' : 'fa-play' }}"></i>
                            {{ $promocion->activa ? 'Desactivar' : 'Activar' }} Promoción
                        </button>
                    </form>

                    <!-- ✅ FORMULARIO SEPARADO para eliminar -->
                    <form action="{{ route('admin.promociones.destroy', $promocion) }}" method="POST" 
                          onsubmit="return confirm('¿Está seguro de que desea eliminar permanentemente esta promoción?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-xl transition-all duration-200 font-semibold flex items-center gap-2">
                            <i class="fas fa-trash"></i>
                            Eliminar
                        </button>
                    </form>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('admin.promociones.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-xl transition-all duration-200 font-semibold flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-green-500/25 transition-all duration-200 font-semibold flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Actualizar Promoción
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    const discountSuffix = document.getElementById('discount-suffix');
    const exampleText = document.getElementById('example-text');
    const valorDescuento = document.getElementById('valor_descuento');
    
    // ✅ CORREGIDO: Obtener fecha/hora actual en formato correcto para datetime-local (AÑO 2025)
    function getCurrentDateTime() {
        const now = new Date();
        // ✅ FORZAR AÑO 2025 para pruebas
        now.setFullYear(2025);
        const timezoneOffset = now.getTimezoneOffset() * 60000;
        const localTime = new Date(now.getTime() - timezoneOffset);
        return localTime.toISOString().slice(0, 16);
    }
    
    // Establecer fecha mínima como la actual para fecha_inicio (AÑO 2025)
    const currentDateTime = getCurrentDateTime();
    fechaInicio.min = currentDateTime;
    
    // Establecer fecha mínima para fecha_fin basada en fecha_inicio
    function updateFechaFinMin() {
        if (fechaInicio.value) {
            const minFinDate = new Date(fechaInicio.value);
            minFinDate.setMinutes(minFinDate.getMinutes() + 1); // ✅ REDUCIDO A 1 MINUTO MÍNIMO
            fechaFin.min = new Date(minFinDate.getTime() - (minFinDate.getTimezoneOffset() * 60000))
                          .toISOString().slice(0, 16);
        }
    }
    
    // Actualizar cuando cambia fecha_inicio
    fechaInicio.addEventListener('change', updateFechaFinMin);
    
    // Validación para fecha_fin
    fechaFin.addEventListener('change', function() {
        if (fechaInicio.value && this.value) {
            const inicio = new Date(fechaInicio.value);
            const fin = new Date(this.value);
            
            if (fin <= inicio) {
                const minFinDate = new Date(inicio.getTime() + (1 * 60 * 1000)); // ✅ 1 MINUTO MÍNIMO
                this.value = new Date(minFinDate.getTime() - (minFinDate.getTimezoneOffset() * 60000))
                            .toISOString().slice(0, 16);
                showTempAlert('La fecha de fin debe ser al menos 1 minuto después de la fecha de inicio', 'warning');
            }
        }
    });

    // ✅ FUNCIONES DE PRUEBA RÁPIDA PARA AÑO 2025
    function setTestDates(minutes) {
        const now = new Date();
        // ✅ FORZAR AÑO 2025
        now.setFullYear(2025);
        const timezoneOffset = now.getTimezoneOffset() * 60000;
        
        const startTime = new Date(now.getTime() - timezoneOffset).toISOString().slice(0, 16);
        const endTime = new Date(now.getTime() + (minutes * 60 * 1000) - timezoneOffset).toISOString().slice(0, 16);
        
        fechaInicio.value = startTime;
        fechaFin.value = endTime;
        
        showTempAlert(`✅ Fechas configuradas para prueba de ${minutes} minuto${minutes > 1 ? 's' : ''} (Año 2025)`, 'info');
    }
    
    // Event listeners para botones de prueba
    document.getElementById('test2min').addEventListener('click', () => setTestDates(2));
    document.getElementById('test5min').addEventListener('click', () => setTestDates(5));
    document.getElementById('test1hour').addEventListener('click', () => setTestDates(60));

    // Función para mostrar alertas temporales
    function showTempAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-xl shadow-lg transition-all duration-300 ${
            type === 'warning' ? 'bg-yellow-500/20 border border-yellow-500/50 text-yellow-400' : 
            type === 'error' ? 'bg-red-500/20 border border-red-500/50 text-red-400' :
            'bg-green-500/20 border border-green-500/50 text-green-400'
        }`;
        alertDiv.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas fa-${type === 'warning' ? 'exclamation-triangle' : type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            alertDiv.style.transform = 'translateX(100%)';
            setTimeout(() => alertDiv.remove(), 300);
        }, 4000);
    }

    // Update discount suffix and example based on discount type
    function updateDiscountDisplay() {
        const selectedType = document.querySelector('input[name="tipo_descuento"]:checked');
        const value = parseFloat(valorDescuento.value) || 0;
        
        if (selectedType) {
            if (selectedType.value === 'porcentaje') {
                discountSuffix.textContent = '%';
                const discountAmount = (100 * value / 100);
                const finalPrice = 100 - discountAmount;
                exampleText.textContent = `Ejemplo: ${value}% = $${finalPrice.toFixed(2)} de $100.00 (ahorro: $${discountAmount.toFixed(2)})`;
            } else {
                discountSuffix.textContent = '$';
                const finalPrice = Math.max(0, 100 - value);
                exampleText.textContent = `Ejemplo: $${value.toFixed(2)} = $${finalPrice.toFixed(2)} de $100.00 (ahorro: $${value.toFixed(2)})`;
            }
        }
    }

    // Listen for discount type changes
    document.querySelectorAll('input[name="tipo_descuento"]').forEach(radio => {
        radio.addEventListener('change', updateDiscountDisplay);
    });

    // Listen for discount value changes
    valorDescuento.addEventListener('input', updateDiscountDisplay);

    // Initialize display
    updateDiscountDisplay();
    updateFechaFinMin(); // Inicializar validación de fechas
    
    // ✅ INICIALIZAR CON FECHAS DE 2025 SI ESTÁN VACÍAS
    if (!fechaInicio.value) {
        fechaInicio.value = getCurrentDateTime();
    }
});
</script>
@endsection