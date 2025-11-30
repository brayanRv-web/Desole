<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #16a34a; /* green-600 */
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #16a34a;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .summary-cards {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .summary-cards td {
            width: 33.33%;
            padding: 15px;
            background-color: #f3f4f6; /* gray-100 */
            border: 1px solid #e5e7eb; /* gray-200 */
            text-align: center;
        }
        .summary-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280; /* gray-500 */
            margin-bottom: 5px;
            display: block;
        }
        .summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #111;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #16a34a;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th {
            background-color: #1f2937; /* gray-800 */
            color: #fff;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        table.data-table td {
            border-bottom: 1px solid #e5e7eb;
            padding: 8px;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f9fafb; /* gray-50 */
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DÉSOLÉ</h1>
        <p>Reporte de Ventas</p>
        <p>Periodo: <?php echo e($startDate->format('d/m/Y')); ?> - <?php echo e($endDate->format('d/m/Y')); ?></p>
    </div>

    <table class="summary-cards">
        <tr>
            <td>
                <span class="summary-label">Ingresos Totales</span>
                <span class="summary-value">$<?php echo e(number_format($totalVentas, 2)); ?></span>
            </td>
            <td>
                <span class="summary-label">Pedidos Completados</span>
                <span class="summary-value"><?php echo e($totalPedidos); ?></span>
            </td>
            <td>
                <span class="summary-label">Ticket Promedio</span>
                <span class="summary-value">$<?php echo e(number_format($ticketPromedio, 2)); ?></span>
            </td>
        </tr>
    </table>

    <div class="section-title">Top Productos Vendidos</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th style="text-align: center;">Cantidad</th>
                <th style="text-align: right;">Total Generado</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $topProductos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($prod['nombre']); ?></td>
                    <td style="text-align: center;"><?php echo e($prod['cantidad']); ?></td>
                    <td style="text-align: right;">$<?php echo e(number_format($prod['total'], 2)); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="3" style="text-align: center; color: #666;">Sin datos en este periodo</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Generado el <?php echo e(date('d/m/Y H:i')); ?> por el sistema administrativo de Désolé.
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Desole\resources\views/admin/reportes/pdf.blade.php ENDPATH**/ ?>