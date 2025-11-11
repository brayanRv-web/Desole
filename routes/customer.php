<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Cliente\CarritoController;
use App\Http\Controllers\Cliente\PedidoController;

// ===========================================================
//                CLIENTE AUTENTICADO
// ===========================================================

Route::middleware(['auth:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    // Dashboard y Menú
    Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
    Route::get('/menu', [ClienteController::class, 'menu'])->name('menu');
    
    // Perfil
    Route::get('/perfil', [ClienteController::class, 'perfil'])->name('perfil');
    Route::post('/perfil/actualizar', [ClienteController::class, 'actualizarPerfil'])->name('perfil.update');

    // Carrito
    Route::prefix('carrito')->name('carrito.')->group(function () {
        Route::get('/', [CarritoController::class, 'index'])->name('index');
        Route::post('/add', [CarritoController::class, 'add'])->name('add');
        Route::put('/update', [CarritoController::class, 'update'])->name('update');
        Route::delete('/remove', [CarritoController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CarritoController::class, 'clear'])->name('clear');
        Route::get('/info', [CarritoController::class, 'getInfo'])->name('info');
        Route::get('/confirmar', [CarritoController::class, 'confirmar'])->name('confirmar');
        Route::post('/procesar', [CarritoController::class, 'procesar'])->name('procesar');
    });

    // Pedidos
    Route::prefix('pedidos')->name('pedidos.')->group(function () {
        Route::get('/', [PedidoController::class, 'index'])->name('index');
        Route::get('/{pedido}', [PedidoController::class, 'show'])->name('show');
        Route::post('/{pedido}/cancelar', [PedidoController::class, 'cancelar'])->name('cancelar');
    });
});