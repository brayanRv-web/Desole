<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PromocionController;
use App\Http\Controllers\Admin\HorarioController;

Route::get('/', function () {
    return view('public.welcome');
});

// ✅ DEFINIR LA RUTA 'login' QUE LARAVEL BUSCA
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Login del admin (rutas públicas)
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'authenticate'])->name('admin.authenticate');

// Ruta de logout (pública pero protegida por CSRF)
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Grupo de rutas PROTEGIDAS del admin
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/', [AdminController::class, 'dashboard']); // Redirigir a dashboard

    // ✅ USUARIOS - Rutas completas del CRUD
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/status', [UserController::class, 'updateStatus'])->name('updateStatus');
    });

    // Productos
    Route::prefix('productos')->name('productos.')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])->name('index');
        Route::get('/create', [ProductoController::class, 'create'])->name('create');
        Route::post('/', [ProductoController::class, 'store'])->name('store');
        Route::get('/{producto}/edit', [ProductoController::class, 'edit'])->name('edit');
        Route::put('/{producto}', [ProductoController::class, 'update'])->name('update');
        Route::delete('/{producto}', [ProductoController::class, 'destroy'])->name('destroy');
        Route::patch('/{producto}/estado', [ProductoController::class, 'updateEstado'])->name('updateEstado');
    });

    // Promociones - Rutas completas del CRUD
    Route::prefix('promociones')->name('promociones.')->group(function () {
        Route::get('/', [PromocionController::class, 'index'])->name('index');
        Route::get('/create', [PromocionController::class, 'create'])->name('create');
        Route::post('/', [PromocionController::class, 'store'])->name('store');
        Route::get('/{promocione}/edit', [PromocionController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{promocione}', [PromocionController::class, 'update'])->name('update'); // ✅ ACEPTA AMBOS
        Route::delete('/{promocione}', [PromocionController::class, 'destroy'])->name('destroy');
        Route::patch('/{promocione}/toggle-status', [PromocionController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Horarios - Rutas completas del CRUD
Route::prefix('horarios')->name('horarios.')->group(function () {
    Route::get('/', [HorarioController::class, 'index'])->name('index');
    Route::get('/{horario}/edit', [HorarioController::class, 'edit'])->name('edit');
    Route::match(['put', 'patch'], '/{horario}', [HorarioController::class, 'update'])->name('update'); // ✅ acepta PUT y PATCH
    Route::match(['put', 'patch'], '/', [HorarioController::class, 'updateMultiple'])->name('update-multiple'); // ✅ igual aquí
    Route::patch('/{horario}/toggle-status', [HorarioController::class, 'toggleStatus'])->name('toggle-status');
});

    // Módulos en desarrollo (vistas estáticas)
    Route::view('/pedidos', 'admin.pedidos.index')->name('pedidos.index');
    Route::view('/reportes', 'admin.reportes.index')->name('reportes.index');
});