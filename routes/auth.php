<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

// ===========================================================
//                AUTENTICACIÓN DE CLIENTES
// ===========================================================

// Invitados (no autenticados)
Route::middleware('guest:cliente')->group(function () {
    Route::get('/registro', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/registro', [RegisterController::class, 'register']);
    Route::get('/login-cliente', [LoginController::class, 'showLoginForm'])->name('login.cliente');
    Route::post('/login-cliente', [LoginController::class, 'login']);
});

// Logout (siempre accesible)
Route::post('/logout-cliente', [LoginController::class, 'logout'])->name('logout.cliente');

// ===========================================================
//                LOGIN Y LOGOUT DEL ADMIN
// ===========================================================

// Redirección genérica a login admin
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Autenticación de admin
Route::get('admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'authenticate'])->name('admin.authenticate');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout');