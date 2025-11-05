@extends('layout')

@section('title', 'Dashboard - DÉSOLÉ')

@section('content')
<div class="container-fluid py-4">
    <!-- Header de Bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-1">¡Hola, {{ $cliente->nombre }}! 👋</h2>
                            <p class="mb-0">Bienvenido a tu panel de DÉSOLÉ</p>
                            @if($cliente->primera_visita)
                                <small>Miembro desde: {{ $cliente->primera_visita->format('d/m/Y') }}</small>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="bg-white bg-opacity-25 rounded p-3 d-inline-block">
                                <small class="d-block">Tu última visita</small>
                                <strong>{{ $cliente->ultima_visita->format('d/m/Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Columna Izquierda - Pedidos -->
        <div class="col-lg-8">
            <!-- Pedidos Recientes -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📦 Tus Pedidos Recientes</h5>
                </div>
                <div class="card-body">
                    @if($pedidosRecientes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th># Pedido</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedidosRecientes as $pedido)
                                    <tr>
                                        <td><strong>#{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                        <td>${{ number_format($pedido->total, 2) }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($pedido->estado == 'completado') bg-success
                                                @elseif($pedido->estado == 'preparacion') bg-warning
                                                @elseif($pedido->estado == 'pendiente') bg-info
                                                @else bg-secondary @endif">
                                                {{ ucfirst($pedido->estado) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-primary">Ver Detalles</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aún no tienes pedidos</h5>
                            <p class="text-muted">Realiza tu primer pedido y disfruta de nuestros productos</p>
                            <a href="{{ route('cliente.menu') }}" class="btn btn-primary">Hacer mi primer pedido</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna Derecha - Acciones y Promociones -->
        <div class="col-lg-4">
            <!-- Acciones Rápidas -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">🚀 Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('cliente.menu') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-utensils me-2"></i>Ver Menú y Ordenar
                        </a>
                        <a href="{{ route('cliente.pedidos') }}" class="btn btn-outline-primary">
                            <i class="fas fa-history me-2"></i>Historial de Pedidos
                        </a>
                        <a href="{{ route('cliente.perfil') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user me-2"></i>Mi Perfil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Promociones Activas -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">🎁 Promociones Exclusivas</h5>
                </div>
                <div class="card-body">
                    @if($promociones->count() > 0)
                        @foreach($promociones as $promocion)
                        <div class="promocion-item mb-3 p-3 border rounded">
                            <h6 class="text-primary mb-1">{{ $promocion->nombre }}</h6>
                            <p class="small text-muted mb-2">{{ $promocion->descripcion }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-success">
                                    <i class="fas fa-calendar me-1"></i>
                                    Válida hasta: {{ $promocion->fecha_fin->format('d/m/Y') }}
                                </small>
                                @if($promocion->descuento)
                                    <span class="badge bg-danger">{{ $promocion->descuento }}% OFF</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-tags fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No hay promociones activas en este momento</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Cliente -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📋 Tu Información</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Email:</strong>
                            <p class="mb-2">{{ $cliente->email }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Teléfono:</strong>
                            <p class="mb-2">{{ $cliente->telefono }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Dirección:</strong>
                            <p class="mb-2">{{ $cliente->direccion }}, {{ $cliente->colonia }}</p>
                        </div>
                        <div class="col-md-3">
                            <strong>Preferencias:</strong>
                            <p class="mb-2">{{ $cliente->preferencias ?: 'Sin preferencias registradas' }}</p>
                        </div>
                    </div>
                    @if($cliente->alergias)
                    <div class="row mt-2">
                        <div class="col-12">
                            <strong>Alergias:</strong>
                            <p class="mb-0 text-warning"><i class="fas fa-exclamation-triangle me-1"></i>{{ $cliente->alergias }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.promocion-item {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid #007bff !important;
}
.card.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
</style>
@endsection