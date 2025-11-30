<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PromocionController;
use App\Http\Controllers\Admin\HorarioController;
use App\Http\Controllers\Admin\CRMController;
use App\Http\Controllers\Admin\PedidoController as AdminPedidoController;
use App\Http\Controllers\Admin\ReportesController;
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

// Envío de reseñas públicas
Route::post('/reseñas', [ResenaController::class, 'store'])->name('reseñas.store');

// ===========================================================
//                RUTAS PÚBLICAS DE MENÚ
// ===========================================================

Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/producto/{producto}', [MenuController::class, 'show'])->name('producto.show');

// ===========================================================
//                AUTENTICACIÓN DE CLIENTES
// ===========================================================

Route::middleware('guest:cliente')->group(function () {
    Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/registro', [RegisterController::class, 'register']);
    Route::get('/login-cliente', [LoginController::class, 'showLoginForm'])->name('login.cliente');
    Route::post('/login-cliente', [LoginController::class, 'login']);
});

Route::post('/logout-cliente', [LoginController::class, 'logout'])->name('logout.cliente');

// ===========================================================
//                CLIENTE AUTENTICADO
// ===========================================================

Route::middleware(['auth:cliente'])
    ->prefix('cliente')
    ->name('cliente.')
    ->group(function () {

        // Dashboard y menú
        Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
        Route::get('/menu', [ClienteController::class, 'menu'])->name('menu');

        // Perfil
        Route::get('/perfil', [ClienteController::class, 'perfil'])->name('perfil');
        Route::post('/perfil/actualizar', [ClienteController::class, 'actualizarPerfil'])->name('perfil.update');

        // ============================
        //         CARRITO
        // ============================
        // Mostrar carrito (ruta: /cliente/carrito)
        Route::get('/carrito', [CarritoController::class, 'index'])
            ->name('carrito');

        // Checkout (Selección de método de pago)
        Route::get('/carrito/checkout', [CarritoController::class, 'checkout'])
            ->name('carrito.checkout');

        // Finalizar compra (POST) -> usa el método `finalizar` del controlador
        Route::post('/carrito/finalizar', [CarritoController::class, 'finalizar'])
            ->name('carrito.finalizar');

        // API: Finalizar compra (JSON) - Endpoint específico para AJAX
        Route::post('/carrito/api/finalizar', [CarritoController::class, 'finalizar'])
            ->name('carrito.api.finalizar');


        // ============================
        //         PEDIDOS
        // ============================
        Route::prefix('pedidos')->name('pedidos.')->group(function () {
            Route::get('/', [PedidoController::class, 'index'])->name('index');
            Route::get('/{pedido}', [PedidoController::class, 'show'])->name('show');
            Route::post('/{pedido}/cancelar', [PedidoController::class, 'cancelar'])->name('cancelar');
            Route::post('/{pedido}/ocultar', [PedidoController::class, 'ocultar'])->name('ocultar');
        });
    });

// ===========================================================
//        API: FINALIZAR COMPRA (SIN CSRF)
// ===========================================================
// Ruta API completamente separada, sin aplicar el middleware global CSRF
Route::post('cliente/carrito/api/finalizar', [CarritoController::class, 'finalizar'])
    ->middleware('auth:cliente')
    ->name('api.carrito.finalizar');

// Agregar promoción al carrito (AJAX) - Fuera del grupo para debug/acceso directo
Route::post('cliente/carrito/agregar-promocion', [CarritoController::class, 'agregarPromocion'])
    ->middleware('auth:cliente')
    ->name('cliente.carrito.agregar-promocion');

// ===========================================================
//                LOGIN Y LOGOUT DEL ADMIN
// ===========================================================

Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'authenticate'])->name('admin.authenticate');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// ===========================================================
//                PANEL DE ADMINISTRADOR
// ===========================================================

Route::prefix('admin')->name('admin.')->middleware(['admin.auth'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Reseñas
    Route::prefix('reseñas')->name('reseñas.')->group(function () {
        Route::get('/', [AdminResenaController::class, 'index'])->name('index');
        Route::delete('/{resena}', [AdminResenaController::class, 'destroy'])->name('destroy');
    });

    // Usuarios
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
        Route::patch('/{producto}/stock', [ProductoController::class, 'updateStock'])->name('updateStock');
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
        Route::match(['put', 'patch'], '/', [HorarioController::class, 'updateMultiple'])->name('update-multiple');
        Route::patch('/{horario}/toggle-status', [HorarioController::class, 'toggleStatus'])->name('toggle-status');
    });

    // CRM
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::get('/', [CRMController::class, 'index'])->name('index');
        Route::get('/clientes', [CRMController::class, 'clientes'])->name('clientes');
        Route::get('/clientes/{id}', [CRMController::class, 'verCliente'])->name('clientes.ver');
        Route::get('/campanas', [CRMController::class, 'campanas'])->name('campanas');
        Route::get('/fidelidad', [CRMController::class, 'fidelidad'])->name('fidelidad');
    });

    // Módulos pendientes
    Route::get('/pedidos', [AdminPedidoController::class, 'index'])->name('pedidos.index');
    Route::get('/pedidos/check-new', [AdminPedidoController::class, 'checkNewOrders'])->name('pedidos.check');
    Route::get('/pedidos/{pedido}', [AdminPedidoController::class, 'show'])->name('pedidos.show');
    Route::post('/pedidos/{pedido}/estado', [AdminPedidoController::class, 'updateEstado'])->name('pedidos.updateEstado');
    Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/pdf', [ReportesController::class, 'downloadPdf'])->name('reportes.pdf');
    Route::view('/configuracion', 'admin.configuracion.index')->name('configuracion.index');
});

// ===========================================================
//                PANEL DE EMPLEADO
// ===========================================================

Route::middleware(['auth', \App\Http\Middleware\EnsureUserRole::class . ':employee'])
    ->prefix('empleado')
    ->name('empleado.')
    ->group(function () {
        Route::get('/', [EmpleadoPedidoController::class, 'dashboard'])->name('dashboard');

        Route::get('/pedidos', [EmpleadoPedidoController::class, 'index'])->name('pedidos.index');
        Route::get('/pedidos/{pedido}', [EmpleadoPedidoController::class, 'show'])->name('pedidos.show');
        Route::patch('/pedidos/{pedido}/status', [EmpleadoPedidoController::class, 'updateStatus'])->name('pedidos.updateStatus');

        Route::prefix('productos')->name('productos.')->group(function () {
            Route::get('/', [EmpleadoProductoController::class, 'index'])->name('index');
            Route::get('/{producto}/edit', [EmpleadoProductoController::class, 'edit'])->name('edit');
            Route::put('/{producto}', [EmpleadoProductoController::class, 'update'])->name('update');
            Route::patch('/{producto}/estado', [EmpleadoProductoController::class, 'updateEstado'])->name('updateEstado');
        });
    });
