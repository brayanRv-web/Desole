<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PromocionController;
use App\Http\Controllers\Admin\HorarioController;
use App\Http\Controllers\Admin\CRMController;
use App\Http\Controllers\Admin\ResenaController as AdminResenaController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Cliente\ClienteController;
use App\Http\Controllers\Cliente\CarritoController;
use App\Http\Controllers\Cliente\PedidoController;
use App\Http\Controllers\Empleado\PedidoController as EmpleadoPedidoController;
use App\Http\Controllers\Empleado\ProductoController as EmpleadoProductoController;
use App\Http\Controllers\MenuController;

// ===========================================================
//                      RUTAS PÚBLICAS
// ===========================================================

// Página principal
Route::get('/', [PublicController::class, 'welcome'])->name('home');

// Página de contacto
Route::get('/contacto', [PublicController::class, 'contacto'])->name('contacto');

// Envío de reseñas públicas (clientes)
Route::post('/reseñas', [ResenaController::class, 'store'])->name('reseñas.store');

// ===========================================================
//                RUTAS PÚBLICAS DE MENÚ
// ===========================================================

// Menú público (accesible sin autenticación)
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/producto/{producto}', [MenuController::class, 'show'])->name('producto.show');

// ===========================================================
//                AUTENTICACIÓN DE CLIENTES
// ===========================================================

// Invitados (no autenticados)
Route::middleware('guest:cliente')->group(function () {
    Route::get('/registro', [App\Http\Controllers\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/registro', [App\Http\Controllers\RegisterController::class, 'register']);
    Route::get('/login-cliente', [App\Http\Controllers\LoginController::class, 'showLoginForm'])->name('login.cliente');
    Route::post('/login-cliente', [App\Http\Controllers\LoginController::class, 'login']);
});

// ✅ LOGOUT (siempre accesible)
Route::post('/logout-cliente', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout.cliente');

// ===========================================================
//                CLIENTE AUTENTICADO
// ===========================================================

Route::middleware(['auth:cliente'])->prefix('cliente')->name('cliente.')->group(function () {
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

// ===========================================================
//                LOGIN Y LOGOUT DEL ADMIN (SIN PROTECCIÓN)
// ===========================================================

// Redirección genérica a login admin
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Autenticación de admin (SIN middleware)
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'authenticate'])->name('admin.authenticate');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// ===========================================================
//                GRUPO PRINCIPAL DEL ADMIN (PROTEGIDO)
// ===========================================================

Route::prefix('admin')->name('admin.')->middleware(['admin.auth'])->group(function () { // ✅ AGREGAR MIDDLEWARE AQUÍ

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // ==================== RESEÑAS ====================
    Route::prefix('reseñas')->name('reseñas.')->group(function () {
        Route::get('/', [AdminResenaController::class, 'index'])->name('index');
        Route::delete('/{resena}', [AdminResenaController::class, 'destroy'])->name('destroy');
    });

    // ==================== USUARIOS ====================
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/status', [UserController::class, 'updateStatus'])->name('updateStatus');
    });

    // ==================== PRODUCTOS ====================
    Route::prefix('productos')->name('productos.')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])->name('index');
        Route::get('/create', [ProductoController::class, 'create'])->name('create');
        Route::post('/', [ProductoController::class, 'store'])->name('store');
        Route::get('/{producto}/edit', [ProductoController::class, 'edit'])->name('edit');
        Route::put('/{producto}', [ProductoController::class, 'update'])->name('update');
        Route::delete('/{producto}', [ProductoController::class, 'destroy'])->name('destroy');
        Route::patch('/{producto}/estado', [ProductoController::class, 'updateEstado'])->name('updateEstado');
        Route::patch('/{producto}/stock', [ProductoController::class, 'updateStock'])->name('updateStock');
    });

    // ==================== PROMOCIONES ====================
    Route::prefix('promociones')->name('promociones.')->group(function () {
        Route::get('/', [PromocionController::class, 'index'])->name('index');
        Route::get('/create', [PromocionController::class, 'create'])->name('create');
        Route::post('/', [PromocionController::class, 'store'])->name('store');
        Route::get('/{promocione}/edit', [PromocionController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{promocione}', [PromocionController::class, 'update'])->name('update');
        Route::delete('/{promocione}', [PromocionController::class, 'destroy'])->name('destroy');
        Route::patch('/{promocione}/toggle-status', [PromocionController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ==================== HORARIOS ====================
    Route::prefix('horarios')->name('horarios.')->group(function () {
        Route::get('/', [HorarioController::class, 'index'])->name('index');
        Route::get('/{horario}/edit', [HorarioController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{horario}', [HorarioController::class, 'update'])->name('update');
        Route::match(['put', 'patch'], '/', [HorarioController::class, 'updateMultiple'])->name('update-multiple');
        Route::patch('/{horario}/toggle-status', [HorarioController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ==================== CRM ====================
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/', [CRMController::class, 'index'])->name('index');
        Route::get('/clientes', [CRMController::class, 'clientes'])->name('clientes');
        Route::get('/clientes/{id}', [CRMController::class, 'verCliente'])->name('clientes.ver');
        Route::get('/campanas', [CRMController::class, 'campanas'])->name('campanas');
        Route::get('/fidelidad', [CRMController::class, 'fidelidad'])->name('fidelidad');
    });

    // ==================== MÓDULOS EN DESARROLLO ====================
    Route::view('/pedidos', 'admin.pedidos.index')->name('pedidos.index');
    Route::view('/reportes', 'admin.reportes.index')->name('reportes.index');
    Route::view('/configuracion', 'admin.configuracion.index')->name('configuracion.index');
});


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