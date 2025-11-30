@extends('cliente.layout.cliente')

@section('title', 'Historial de Pedidos - DÉSOLÉ')

@section('content')
<div class="pedidos-container">
    <div class="pedidos-header">
        <h1>Historial de Pedidos</h1>
        <p class="subtitle">Visualiza y gestiona todos tus pedidos</p>
    </div>

    <div class="pedidos-content">
        @if(isset($pedidos) && $pedidos->count() > 0)
            <div class="pedidos-table">
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
                        @foreach($pedidos as $pedido)
                        <tr>
                            <td class="order-number">
                                <strong>#{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</strong>
                            </td>
                            <td class="order-date">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                            <td class="order-total">${{ number_format($pedido->total, 2) }}</td>
                            <td class="order-status">
                                <span class="status-badge status-{{ $pedido->status }}" data-pedido-id="{{ $pedido->id }}">
                                    {{ ucfirst($pedido->estado ?? $pedido->status) }}
                                </span>
                            </td>
                            <td class="order-actions">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('cliente.pedidos.show', $pedido->id) }}" class="btn-action">
                                        Ver Detalles
                                    </a>
                                    @if(in_array($pedido->estado ?? $pedido->status, ['entregado', 'completado', 'cancelado']))
                                        <button type="button" class="btn-action btn-delete" onclick="confirmarOcultar({{ $pedido->id }})">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form id="form-ocultar-{{ $pedido->id }}" action="{{ route('cliente.pedidos.ocultar', $pedido->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-shopping-bag"></i>
                <h4>No tienes pedidos aún</h4>
                <p>Realiza tu primer pedido y disfruta de nuestros productos</p>
                <a href="{{ route('cliente.menu') }}" class="btn-primary">Hacer mi primer pedido</a>
            </div>
        @endif
    </div>
</div>

<style>
.pedidos-container {
    padding: 100px 20px 40px 20px;
    min-height: 100vh;
    background: var(--color-bg);
}

.pedidos-header {
    margin-bottom: 2rem;
    text-align: center;
}

.pedidos-header h1 {
    margin: 0 0 0.5rem 0;
    color: var(--color-text);
}

.subtitle {
    color: var(--color-muted);
    margin: 0;
}

.pedidos-content {
    max-width: 1200px;
    margin: 0 auto;
    background: var(--color-bg-alt);
    border-radius: 12px;
    border: 1px solid #333;
    overflow: hidden;
}

.pedidos-table {
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
.status-cancelado { background: #dc3545; color: white; }
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

.btn-delete {
    border-color: #dc3545;
    color: #dc3545;
}

.btn-delete:hover {
    background: #dc3545;
    color: white;
}

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
@media (max-width: 768px) {
    .pedidos-container {
        padding: 100px 15px 30px 15px;
    }
    
    .custom-table th,
    .custom-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.9rem;
    }
}
</style>
@endsection

@push('scripts')
<script>
    function confirmarOcultar(id) {
        Swal.fire({
            title: '¿Ocultar pedido?',
            text: "Dejará de ser visible en tu lista.",
            showCancelButton: true,
            confirmButtonColor: '#3f3f46',
            cancelButtonColor: 'transparent',
            confirmButtonText: 'Ocultar',
            cancelButtonText: 'Cancelar',
            background: '#18181b',
            color: '#e4e4e7',
            width: '20em',
            padding: '1.5em',
            reverseButtons: true,
            customClass: {
                confirmButton: 'border border-zinc-600',
                cancelButton: 'text-gray-400 hover:text-white'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-ocultar-' + id).submit();
            }
        })
    }

    // Auto-update Status Logic
    document.addEventListener('DOMContentLoaded', function() {
        const pollInterval = 6000;

        async function pollAll() {
            const els = Array.from(document.querySelectorAll('[data-pedido-id]'));
            if (!els.length) return;

            for (const el of els) {
                const id = el.getAttribute('data-pedido-id');
                try {
                    const res = await fetch("{{ url('/cliente/pedidos') }}/" + id, { 
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } 
                    });
                    
                    if (!res.ok) continue;
                    
                    const j = await res.json();
                    if (j && j.success && j.data && j.data.pedido) {
                        const rawStatus = (j.data.pedido.estado || j.data.pedido.status || '').toString().toLowerCase();
                        const currentText = el.textContent.trim().toLowerCase();
                        
                        if (rawStatus && rawStatus !== currentText) {
                            // Update Text
                            el.textContent = rawStatus.charAt(0).toUpperCase() + rawStatus.slice(1);
                            
                            // Update Class
                            // Remove old status classes
                            el.classList.forEach(cls => {
                                if (cls.startsWith('status-') && cls !== 'status-badge') {
                                    el.classList.remove(cls);
                                }
                            });
                            // Add new status class
                            el.classList.add('status-' + rawStatus);

                            // Notification
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                background: '#18181b',
                                color: '#fff'
                            });
                            Toast.fire({
                                icon: 'info',
                                title: 'Pedido #' + id + ' actualizado',
                                text: 'Nuevo estado: ' + el.textContent
                            });
                        }
                    }
                } catch (e) {
                    console.debug('pollAll error', e);
                }
            }
        }

        setInterval(pollAll, pollInterval);
    });
</script>
@endpush
