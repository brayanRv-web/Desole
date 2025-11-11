<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Empleado\PedidoController as EmpleadoPedidoController;
use App\Http\Controllers\Empleado\ProductoController as EmpleadoProductoController;

// ===========================================================
//                PANEL DE EMPLEADO
// ===========================================================

Route::middleware(['auth', \App\Http\Middleware\EnsureUserRole::class . ':employee'])
    ->prefix('empleado')
    ->name('empleado.')
    ->group(function () {
        // Dashboard
        Route::get('/', [EmpleadoPedidoController::class, 'dashboard'])->name('dashboard');

        // Pedidos
        Route::get('/pedidos', [EmpleadoPedidoController::class, 'index'])->name('pedidos.index');
        Route::get('/pedidos/{pedido}', [EmpleadoPedidoController::class, 'show'])->name('pedidos.show');
        Route::patch('/pedidos/{pedido}/status', [EmpleadoPedidoController::class, 'updateStatus'])->name('pedidos.updateStatus');

        // Productos
        Route::prefix('productos')->name('productos.')->group(function () {
            Route::get('/', [EmpleadoProductoController::class, 'index'])->name('index');
            Route::get('/{producto}/edit', [EmpleadoProductoController::class, 'edit'])->name('edit');
            Route::put('/{producto}', [EmpleadoProductoController::class, 'update'])->name('update');
            Route::patch('/{producto}/estado', [EmpleadoProductoController::class, 'updateEstado'])->name('updateEstado');
        });
    });