<?php $__env->startSection('title', 'Historial de Pedidos - DÉSOLÉ'); ?>

<?php $__env->startSection('content'); ?>
<div class="pedidos-container">
    <div class="pedidos-header">
        <h1>Historial de Pedidos</h1>
        <p class="subtitle">Visualiza y gestiona todos tus pedidos</p>
    </div>

    <div class="pedidos-content">
        <?php if(isset($pedidos) && $pedidos->count() > 0): ?>
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
                        <?php $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="order-number">
                                <strong>#<?php echo e(str_pad($pedido->id, 4, '0', STR_PAD_LEFT)); ?></strong>
                            </td>
                            <td class="order-date"><?php echo e($pedido->created_at->format('d/m/Y H:i')); ?></td>
                            <td class="order-total">$<?php echo e(number_format($pedido->total, 2)); ?></td>
                            <td class="order-status">
                                <span class="status-badge status-<?php echo e($pedido->status); ?>" data-pedido-id="<?php echo e($pedido->id); ?>">
                                    <?php echo e(ucfirst($pedido->estado ?? $pedido->status)); ?>

                                </span>
                            </td>
                            <td class="order-actions">
                                <a href="<?php echo e(route('cliente.pedidos.show', $pedido->id)); ?>" class="btn-action">
                                    Ver Detalles
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-shopping-bag"></i>
                <h4>No tienes pedidos aún</h4>
                <p>Realiza tu primer pedido y disfruta de nuestros productos</p>
                <a href="<?php echo e(route('cliente.menu')); ?>" class="btn-primary">Hacer mi primer pedido</a>
            </div>
        <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('cliente.layout.cliente', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\josxp\Documents\desole\Desole\resources\views/cliente/pedidos.blade.php ENDPATH**/ ?>