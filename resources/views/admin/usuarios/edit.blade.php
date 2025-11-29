@extends('admin.layout')

@section('content')
<div class="flex flex-col space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-purple-400 flex items-center gap-3">
                <i class="fas fa-edit text-2xl"></i>
                Editar Usuario: {{ $user->name }}
            </h2>
            <p class="text-gray-400 mt-2">Modifica la información del usuario</p>
        </div>
        
        <a href="{{ route('admin.usuarios.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-gray-500/25 transition-all duration-200 flex items-center gap-2 font-semibold group">
            <i class="fas fa-arrow-left group-hover:scale-110 transition-transform"></i>
            Volver a Usuarios
        </a>
    </div>

    <!-- Form Section -->
    <div class="bg-gray-800/50 rounded-2xl border border-purple-700/30 shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-purple-700/40 to-purple-800/20 border-b border-purple-600/30 px-6 py-4">
            <h3 class="text-lg font-semibold text-purple-300 flex items-center gap-2">
                <i class="fas fa-user-edit"></i>
                Información del Usuario
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $userType === 'panel_admin' ? 'bg-red-500/20 text-red-400 border border-red-500' : 'bg-blue-500/20 text-blue-400 border border-blue-500' }}">
                    <i class="fas {{ $userType === 'panel_admin' ? 'fa-shield-alt' : 'fa-user-tie' }} mr-1"></i>
                    {{ $userType === 'panel_admin' ? 'Administrador' : 'Empleado' }}
                </span>
            </h3>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.usuarios.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Información Básica -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-user mr-2 text-purple-400"></i>
                            Nombre Completo *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200 @error('name') border-red-500 @enderror"
                               placeholder="Ej: Juan Pérez García"
                               required>
                        @error('name')
                            <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-envelope mr-2 text-purple-400"></i>
                            Correo Electrónico *
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200 @error('email') border-red-500 @enderror"
                               placeholder="Ej: juan.perez@empresa.com"
                               required>
                        @error('email')
                            <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Contraseñas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nueva Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-400"></i>
                            Nueva Contraseña
                        </label>
                        <input type="password" id="password" name="password"
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200 @error('password') border-red-500 @enderror"
                               placeholder="Dejar en blanco para no cambiar">
                        @error('password')
                            <p class="text-red-400 text-sm mt-1 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-400"></i>
                            Confirmar Contraseña
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200"
                               placeholder="Repite la nueva contraseña">
                    </div>
                </div>

                <!-- Información de Contacto (solo para empleados) -->
                @if($userType === 'system_user')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Teléfono -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-phone mr-2 text-purple-400"></i>
                            Teléfono
                        </label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200"
                               placeholder="Ej: +1 234 567 8900">
                    </div>

                    <!-- Dirección -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-300 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-purple-400"></i>
                            Dirección
                        </label>
                        <textarea id="address" name="address" rows="2"
                                  class="w-full px-4 py-3 bg-gray-700/50 border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all duration-200 resize-none"
                                  placeholder="Ej: Av. Principal #123, Ciudad">{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>
                @endif



                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-700/50">
                    <button type="submit" 
                            class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-purple-500/25 transition-all duration-200 flex items-center justify-center gap-2 font-semibold group flex-1">
                        <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                        Actualizar Usuario
                    </button>
                    <a href="{{ route('admin.usuarios.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-xl shadow-lg hover:shadow-gray-500/25 transition-all duration-200 flex items-center justify-center gap-2 font-semibold">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection