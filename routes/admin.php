<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductoController;

// Login y Logout
Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('login', [AuthController::class, 'authenticate'])->name('admin.authenticate');
Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('productos', [ProductoController::class, 'index'])->name('admin.productos.index');
    Route::get('productos/create', [ProductoController::class, 'create'])->name('admin.productos.create');
    Route::post('productos', [ProductoController::class, 'store'])->name('admin.productos.store');

    // Opcional: editar, actualizar, eliminar
    Route::get('productos/{producto}/edit', [ProductoController::class, 'edit'])->name('admin.productos.edit');
    Route::put('productos/{producto}', [ProductoController::class, 'update'])->name('admin.productos.update');
    Route::delete('productos/{producto}', [ProductoController::class, 'destroy'])->name('admin.productos.destroy');
});
