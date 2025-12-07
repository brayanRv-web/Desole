@extends('cliente.layout.cliente')

@section('title', 'Dashboard - DÉSOLÉ')

@section('content')
<div class="dashboard-container">
    <!-- Header de Bienvenida Mejorado -->
    <div class="dashboard-header">
        <div class="welcome-card">
            <div class="welcome-info">
                <div class="user-greeting">
                    <h1>¡Hola, {{ $cliente->nombre }}! 👋</h1>
                    <p class="welcome-text">Bienvenido a tu panel de DÉSOLÉ</p>
                </div>
                <div class="last-visit">
                    <div class="visit-badge">
                        <small class="visit-label">Tu última visita</small>
                        <strong class="visit-date">
                            @if($cliente->ultima_visita)
                                {{ $cliente->ultima_visita->format('d/m/Y') }}
                            @else
                                Hoy
                            @endif
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <!-- Sección Principal - Información y Pedidos -->
        <div class="main-section">
            <!-- Información Personal (Estilo Portal Escolar) -->
            <div class="info-card">
                <div class="card-header-custom">
                    <h3><i class="fas fa-user-circle me-2"></i>Información Personal</h3>
                </div>
                <div class="card-body-custom">
                    <div class="personal-info-grid">
                        <div class="info-group">
                            <label class="info-label">Nombre Completo</label>
                            <p class="info-value">{{ $cliente->nombre }}</p>
                        </div>
                        
                        <div class="info-group">
                            <label class="info-label">Email</label>
                            <p class="info-value">{{ $cliente->email }}</p>
                        </div>
                        
                        <div class="info-group">
                            <label class="info-label">Teléfono</label>
                            <p class="info-value">{{ $cliente->telefono }}</p>
                        </div>
                        
                        <div class="info-group">
                            <label class="info-label">Dirección</label>
                            <p class="info-value">{{ $cliente->direccion ?: 'Sin dirección registrada' }}</p>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Pedidos Recientes -->
            <div class="orders-card">
                <div class="card-header-custom">
                    <h3><i class="fas fa-shopping-bag me-2"></i>Tus Pedidos Recientes</h3>
                </div>
                <div class="card-body-custom">
                    @if(isset($pedidosRecientes) && $pedidosRecientes->count() > 0)
                        <div class="orders-table">
                            <table class="custom-table">
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
                                        <td class="order-number">
                                            <strong>#{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                        </td>
                                        <td class="order-date">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="order-total">${{ number_format($pedido->total, 2) }}</td>
                                        <td class="order-status">
                                            <span data-pedido-id="{{ $pedido->id }}" class="status-badge status-{{ $pedido->estado ?? $pedido->status }}">
                                                {{ ucfirst($pedido->estado ?? $pedido->status) }}
                                            </span>
                                        </td>
                                        <td class="order-actions">
                                            <a href="{{ route('cliente.pedidos.show', $pedido->id) }}" class="btn-action">Ver Detalles</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag"></i>
                            <h4>Aún no tienes pedidos</h4>
                            <p>Realiza tu primer pedido y disfruta de nuestros productos</p>
                            <a href="{{ route('cliente.menu') }}" class="btn-primary">Hacer mi primer pedido</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar - Acciones y Promociones -->
        <div class="sidebar-section">
            <div class="actions-card">
                        <div class="card-header-custom">
                        <h3><i class="fas fa-bolt me-2"></i>Acciones Rápidas</h3>
                        </div>
                        <div class="actions-grid">
                            <a href="{{ route('cliente.menu') }}" class="action-btn secondary">
                                <i class="fas fa-utensils"></i>
                                <span>Ver Menú y Ordenar</span>
                            </a>
                            <a href="{{ route('cliente.pedidos.index') }}" class="action-btn secondary">
                                <i class="fas fa-history"></i>
                                <span>Historial de Pedidos</span>
                            </a>
                            <a href="{{ route('cliente.perfil') }}" class="action-btn secondary">
                                <i class="fas fa-user-edit"></i>
                                <span>Editar Mi Perfil</span>
                            </a>
                        </div>
                    </div> 
                    <!-- Promociones Activas -->
                    <div class="promotions-card">
                        <div class="card-header-custom">
                            <h3><i class="fas fa-tags me-2"></i>Promociones Activas</h3>
                        </div>
                        <div class="card-body-custom">
                            @if(isset($promociones) && $promociones->count() > 0)
                                <div class="promotions-list">
                                    @foreach($promociones as $promocion)
                                    <div class="promotion-item">
                                        <div class="promotion-header">
                                            <h4>{{ $promocion->nombre }}</h4>
                                            @if($promocion->descuento)
                                                <span class="discount-badge">{{ $promocion->descuento }}% OFF</span>
                                            @endif
                                        </div>
                                        <p class="promotion-desc">{{ $promocion->descripcion }}</p>
                                        <div class="promotion-footer">
                                            <small class="promotion-date">
                                                <i class="fas fa-calendar me-1"></i>
                                                @if($promocion->fecha_fin)
                                                    Válida hasta: {{ $promocion->fecha_fin->format('d/m/Y') }}
                                                @else
                                                    Promoción permanente
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state small">
                                    <i class="fas fa-tags"></i>
                                    <p>No hay promociones activas en este momento</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

<style>
/* Contenedor Principal */

.dashboard-container {
    padding: 10px 20px 40px 20px; /* padding-top muy pequeño para pegarlo al navbar */
}

/* Header de Bienvenida */
.dashboard-header {
    margin-bottom: 1.5rem; /* antes 2rem -> un poco más cercano */
}

.welcome-card {
    background: linear-gradient(135deg, var(--color-primary) 0%, #4ebb5f 100%);
    border-radius: 15px;
    padding: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(101, 207, 114, 0.3);
}

.welcome-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.user-greeting h1 {
    margin: 0 0 0.5rem 0;
    font-size: 2rem;
    font-weight: 700;
}

.welcome-text {
    margin: 0 0 0.5rem 0;
    opacity: 0.9;
}

.member-since {
    opacity: 0.8;
    font-size: 0.9rem;
}

.last-visit {
    display: flex;
    align-items: center;
}

.visit-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 1rem;
    border-radius: 10px;
    text-align: center;
    backdrop-filter: blur(10px);
}

.visit-label {
    display: block;
    opacity: 0.8;
    margin-bottom: 0.25rem;
}

.visit-date {
    font-size: 1.1rem;
}

/* Layout Principal */
.dashboard-content {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
}

.main-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.sidebar-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Cards Estilo Uniforme */
.info-card, .orders-card, .actions-card, .promotions-card {
    background: var(--color-bg-alt);
    border-radius: 12px;
    border: 1px solid #333;
    overflow: hidden;
}

.card-header-custom {
    background: rgba(101, 207, 114, 0.1);
    padding: 1.5rem;
    border-bottom: 1px solid #333;
}

.card-header-custom h3 {
    margin: 0;
    color: var(--color-text);
    font-size: 1.2rem;
    font-weight: 600;
}

.card-body-custom {
    padding: 1.5rem;
}

/* Información Personal */
.personal-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.info-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-group.full-width {
    grid-column: 1 / -1;
}

.info-label {
    font-weight: 600;
    color: var(--color-primary);
    font-size: 0.9rem;
}

.info-label.warning {
    color: #ffc107;
}

.info-value {
    margin: 0;
    color: var(--color-text);
    font-size: 1rem;
}

.info-value.warning {
    color: #ffc107;
}

/* Tabla de Pedidos */
.orders-table {
    overflow-x: auto;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
}

.custom-table th {
    background: rgba(101, 207, 114, 0.05);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--color-primary);
    border-bottom: 1px solid #333;
}

.custom-table td {
    padding: 1rem;
    border-bottom: 1px solid #333;
    color: var(--color-text);
}

.order-number {
    font-weight: 600;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-completado { background: #198754; color: white; }
.status-preparacion { background: #ffc107; color: black; }
.status-pendiente { background: #0dcaf0; color: black; }
.status- { background: #6c757d; color: white; }

.btn-action {
    background: transparent;
    border: 1px solid var(--color-primary);
    color: var(--color-primary);
    padding: 0.5rem 1rem;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.8rem;
    transition: var(--transition);
}

.btn-action:hover {
    background: var(--color-primary);
    color: var(--color-bg);
}

/* Estados Vacíos */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: var(--color-muted);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h4 {
    margin: 0 0 1rem 0;
    color: var(--color-muted);
}

.empty-state.small {
    padding: 2rem 1rem;
}

.empty-state.small i {
    font-size: 2rem;
}

/* Acciones Rápidas */
.actions-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    transition: var(--transition);
    border: 1px solid transparent;
}

.action-btn.primary {
    background: var(--color-primary);
    color: var(--color-bg);
    font-weight: 600;
}

.action-btn.secondary {
    background: transparent;
    border-color: var(--color-primary);
    color: var(--color-primary);
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(101, 207, 114, 0.3);
}

/* Promociones */
.promotions-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.promotion-item {
    background: rgba(101, 207, 114, 0.05);
    border: 1px solid rgba(101, 207, 114, 0.2);
    border-radius: 8px;
    padding: 1.25rem;
    transition: var(--transition);
}

.promotion-item:hover {
    border-color: var(--color-primary);
    transform: translateY(-2px);
}

.promotion-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.promotion-header h4 {
    margin: 0;
    color: var(--color-primary);
    font-size: 1rem;
}

.discount-badge {
    background: #dc3545;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: 600;
}

.promotion-desc {
    margin: 0 0 1rem 0;
    color: var(--color-text);
    font-size: 0.9rem;
    line-height: 1.4;
}

.promotion-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.promotion-date {
    color: var(--color-muted);
}

/* Botón Principal */
.btn-primary {
    background: var(--color-primary);
    color: var(--color-bg);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    display: inline-block;
}

.btn-primary:hover {
    background: #5ac968;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(101, 207, 114, 0.3);
}

/* Responsive */
@media (max-width: 1024px) {
    .dashboard-content {
        grid-template-columns: 1fr;
    }
    
    .personal-info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 100px 15px 30px 15px;
    }
    
    .welcome-info {
        flex-direction: column;
        text-align: center;
    }
    
    .user-greeting h1 {
        font-size: 1.5rem;
    }
    
    .card-header-custom,
    .card-body-custom {
        padding: 1rem;
    }
    
    .custom-table th,
    .custom-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.9rem;
    }
}


/* Botón de acción en tabla de pedidos */
.order-actions .btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.35rem 0.75rem;
    font-size: 0.8rem;
    border-radius: 6px;
    border: 1px solid var(--color-primary);
    color: var(--color-primary);
    text-decoration: none;
    transition: var(--transition);
    white-space: nowrap; /* evita que se rompa el texto */
}

.order-actions .btn-action:hover {
    background: var(--color-primary);
    color: var(--color-bg);
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(101, 207, 114, 0.3);
}

/* Asegurar que la celda se ajuste al contenido */
.custom-table td.order-actions {
    white-space: nowrap;
    width: 1%;
}
</style>
@push('scripts')
<script>
    (function(){
        const pollInterval = 8000; // ms

        async function pollRecent() {
            const els = Array.from(document.querySelectorAll('[data-pedido-id]'));
            if (!els.length) return;

            for (const el of els) {
                const id = el.getAttribute('data-pedido-id');
                try {
                    const res = await fetch('/cliente/pedidos/' + id, { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) continue;
                    const j = await res.json();
                    if (j && j.success && j.data && j.data.pedido) {
                        const estado = (j.data.pedido.estado || j.data.pedido.status || '').toString();
                        if (estado && estado.toLowerCase() !== el.textContent.trim().toLowerCase()) {
                            const formatted = estado.charAt(0).toUpperCase() + estado.slice(1);
                            el.textContent = formatted;
                        }
                    }
                } catch (e) {
                    console.debug('pollRecent error', e);
                }
            }
        }

        setInterval(pollRecent, pollInterval);
        setTimeout(pollRecent, 1200);
    })();
</script>
@endpush

@endsection