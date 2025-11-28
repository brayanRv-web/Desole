{{-- resources/views/admin/crm/clientes.blade.php --}}
@extends('admin.layout')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-green-400 flex items-center gap-2">
                <i class="fas fa-users"></i> Todos los Clientes
            </h2>
            <p class="text-gray-400 mt-1">Gestiona y segmenta tu base de clientes</p>
        </div>
        <div class="flex gap-3">
            <input type="text" placeholder="Buscar cliente..." class="bg-gray-800 border border-gray-600 rounded-lg px-4 py-2 text-white w-64">
            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-filter mr-2"></i>Filtrar
            </button>
        </div>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gray-800 p-4 rounded border border-blue-500/40">
        <p class="text-gray-400 text-sm">Total</p>
        <p class="text-xl font-bold text-white">{{ $clientes->total() }}</p>
    </div>
    <div class="bg-gray-800 p-4 rounded border border-green-500/40">
        <p class="text-gray-400 text-sm">Registrados</p>
        <p class="text-xl font-bold text-white">{{ $clientes->where('tipo', 'registrado')->count() }}</p>
    </div>
    <div class="bg-gray-800 p-4 rounded border border-purple-500/40">
        <p class="text-gray-400 text-sm">WhatsApp</p>
        <p class="text-xl font-bold text-white">{{ $clientes->where('tipo', 'anonimo')->count() }}</p>
    </div>
    <div class="bg-gray-800 p-4 rounded border border-yellow-500/40">
        <p class="text-gray-400 text-sm">Frecuentes</p>
        <p class="text-xl font-bold text-white">{{ $clientes->where('total_pedidos', '>=', 5)->count() }}</p>
    </div>
</div>

<!-- Tabla de Clientes -->
<div class="bg-gray-800 rounded-xl border border-green-600/40 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-750">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Contacto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Estadísticas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($clientes as $cliente)
                <tr class="hover:bg-gray-750 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-green-600/20 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-green-400"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">{{ $cliente->nombre }}</div>
                                <div class="text-xs text-gray-400">
                                    @if($cliente->tipo == 'registrado')
                                        <span class="bg-green-500/20 text-green-400 px-2 py-1 rounded-full text-xs">Registrado</span>
                                    @else
                                        <span class="bg-gray-500/20 text-gray-400 px-2 py-1 rounded-full text-xs">WhatsApp</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-white">{{ $cliente->telefono ?? 'Sin teléfono' }}</div>
                        <div class="text-xs text-gray-400">{{ $cliente->email ?? 'Sin email' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-white">{{ $cliente->total_pedidos }} pedidos</div>
                        <div class="text-xs text-gray-400">${{ number_format($cliente->total_gastado, 2) }} total</div>
                        <div class="text-xs text-yellow-400">{{ $cliente->puntos_fidelidad }} puntos</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($cliente->ultima_visita && $cliente->ultima_visita->gt(now()->subMonth()))
                            <span class="bg-green-500/20 text-green-400 px-2 py-1 rounded-full text-xs">Activo</span>
                        @else
                            <span class="bg-red-500/20 text-red-400 px-2 py-1 rounded-full text-xs">Inactivo</span>
                        @endif
                        <div class="text-xs text-gray-400 mt-1">
                            {{ $cliente->ultima_visita ? $cliente->ultima_visita->diffForHumans() : 'Sin visitas' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.crm.clientes.ver', $cliente->id) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs transition">
                            <i class="fas fa-eye mr-1"></i>Ver
                        </a>
                        <button class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-lg text-xs ml-2 transition">
                            <i class="fas fa-edit mr-1"></i>Editar
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center">
                        <i class="fas fa-users text-4xl text-gray-600 mb-3"></i>
                        <p class="text-gray-400">No hay clientes registrados aún</p>
                        <p class="text-gray-500 text-sm mt-2">Los clientes aparecerán automáticamente al recibir pedidos</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    @if($clientes->hasPages())
    <div class="bg-gray-750 px-6 py-4 border-t border-gray-700">
        {{ $clientes->links() }}
    </div>
    @endif
</div>
@endsection