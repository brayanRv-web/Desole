<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PromocionController;
use App\Http\Controllers\Admin\HorarioController;
use App\Http\Controllers\Admin\CRMController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\PublicController;

// Ruta principal usando el Controller
Route::get('/', [PublicController::class, 'welcome'])->name('home');

Route::get('/contacto', [PublicController::class, 'contacto'])->name('contacto');

//  NUEVA RUTA PARA RESEÑAS (FUERA del admin - clientes pueden enviar)
Route::post('/reseñas', [ResenaController::class, 'store'])->name('reseñas.store');

// DEFINIR LA RUTA 'login' QUE LARAVEL BUSCA
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Login del admin (rutas públicas)
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'authenticate'])->name('admin.authenticate');

// Ruta de logout (pública pero protegida por CSRF)
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// ✅ GRUPO ADMIN PRINCIPAL
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/', [AdminController::class, 'dashboard']); // Redirigir a dashboard

    //  NUEVO: RESEÑAS PARA EL ADMIN
    // Dentro del grupo admin - solo ver y eliminar
    Route::prefix('reseñas')->name('reseñas.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ResenaController::class, 'index'])->name('index');
        Route::delete('/{resena}', [\App\Http\Controllers\Admin\ResenaController::class, 'destroy'])->name('destroy');
    });
    // USUARIOS - Rutas completas del CRUD
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/status', [UserController::class, 'updateStatus'])->name('updateStatus');
    });

        // ✅ PRODUCTOS - ELIMINAR middleware:
        Route::prefix('productos')->name('productos.')->group(function () {
            Route::get('/', [ProductoController::class, 'index'])->name('index');
            Route::get('/create', [ProductoController::class, 'create'])->name('create');
            Route::post('/', [ProductoController::class, 'store'])->name('store');
            Route::get('/{producto}/edit', [ProductoController::class, 'edit'])->name('edit');
            Route::put('/{producto}', [ProductoController::class, 'update'])->name('update');
            
            Route::delete('/{producto}', [ProductoController::class, 'destroy'])
                ->name('destroy');
                // ->middleware(\App\Middleware\EnsureAdminRole::class.':admin'); // ❌ ELIMINAR

            Route::patch('/{producto}/estado', [ProductoController::class, 'updateEstado'])
                ->name('updateEstado');
        });

        // Promociones
        Route::prefix('promociones')->name('promociones.')->group(function () {
            Route::get('/', [PromocionController::class, 'index'])->name('index');
            Route::get('/create', [PromocionController::class, 'create'])->name('create');
            Route::post('/', [PromocionController::class, 'store'])->name('store');
            Route::get('/{promocione}/edit', [PromocionController::class, 'edit'])->name('edit');
            Route::match(['put', 'patch'], '/{promocione}', [PromocionController::class, 'update'])->name('update');
            Route::delete('/{promocione}', [PromocionController::class, 'destroy'])->name('destroy');
            Route::patch('/{promocione}/toggle-status', [PromocionController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Horarios
        Route::prefix('horarios')->name('horarios.')->group(function () {
            Route::get('/', [HorarioController::class, 'index'])->name('index');
            Route::get('/{horario}/edit', [HorarioController::class, 'edit'])->name('edit');
            Route::match(['put', 'patch'], '/{horario}', [HorarioController::class, 'update'])->name('update');
            // ->middleware(\App\Middleware\EnsureAdminRole::class.':admin'); // ❌ ELIMINAR
            
            Route::match(['put', 'patch'], '/', [HorarioController::class, 'updateMultiple'])->name('update-multiple');
            // ->middleware(\App\Middleware\EnsureAdminRole::class.':admin'); // ❌ ELIMINAR
            
            Route::patch('/{horario}/toggle-status', [HorarioController::class, 'toggleStatus'])->name('toggle-status');
            // ->middleware(\App\Middleware\EnsureAdminRole::class.':admin'); // ❌ ELIMINAR
        });

    // ✅ RUTAS CRM CORREGIDAS - FUERA del grupo anidado
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/', [CRMController::class, 'index'])->name('index');
        Route::get('/clientes', [CRMController::class, 'clientes'])->name('clientes');
        Route::get('/clientes/{id}', [CRMController::class, 'verCliente'])->name('clientes.ver');
    });                 

    // En routes/web.php - dentro del grupo admin
    Route::prefix('crm')->name('crm.')->group(function () {
    Route::get('/', [CRMController::class, 'index'])->name('index');
    Route::get('/clientes', [CRMController::class, 'clientes'])->name('clientes');
    Route::get('/clientes/{id}', [CRMController::class, 'verCliente'])->name('clientes.ver');
    
    // ✅ NUEVAS RUTAS DESBLOQUEADAS
    Route::get('/campanas', [CRMController::class, 'campanas'])->name('campanas');
    Route::get('/fidelidad', [CRMController::class, 'fidelidad'])->name('fidelidad');
});

    // Módulos en desarrollo (vistas estáticas)
    Route::view('/pedidos', 'admin.pedidos.index')->name('pedidos.index');
    Route::view('/reportes', 'admin.reportes.index')->name('reportes.index');
    Route::view('/configuracion', 'admin.configuracion.index')->name('configuracion.index');
});

// Panel Empleado (separate panel for employees)
Route::middleware(['auth', \App\Middleware\EnsureUserRole::class.':employee'])->prefix('empleado')->name('empleado.')->group(function () {
    // Pedidos - empleado: dashboard y listado
    Route::get('/', [\App\Http\Controllers\Empleado\PedidoController::class, 'dashboard'])->name('dashboard');
    Route::get('/pedidos', [\App\Http\Controllers\Empleado\PedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/{pedido}', [\App\Http\Controllers\Empleado\PedidoController::class, 'show'])->name('pedidos.show');

    // Productos - Empleado puede ver/editar y cambiar estado (no eliminar)
    Route::prefix('productos')->name('productos.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Empleado\ProductoController::class, 'index'])->name('index');
        Route::get('/{producto}/edit', [\App\Http\Controllers\Empleado\ProductoController::class, 'edit'])->name('edit');
        Route::put('/{producto}', [\App\Http\Controllers\Empleado\ProductoController::class, 'update'])->name('update');
        Route::patch('/{producto}/estado', [\App\Http\Controllers\Empleado\ProductoController::class, 'updateEstado'])->name('updateEstado');
    });
});

