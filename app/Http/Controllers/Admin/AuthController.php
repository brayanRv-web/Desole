<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login del administrador
     */
    public function showLoginForm()
    {
        // Verificar si ya está autenticado como admin
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            if (isset($user->role) && $user->role === 'Empleado') {
                return redirect()->route('empleado.dashboard');
            }
            return redirect()->route('admin.dashboard');
        }

        // Mostrar formulario de login (no redirigir automáticamente a empleado si hay sesión web)
        return view('admin.login');
    }

    /**
     * Procesar login del administrador
     */
    public function authenticate(Request $request)
    {
        // Validar campos obligatorios
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        // Primero intentamos con el guard 'admin' (tabla admins)
        $admin = Admin::where('email', $credentials['email'])->first();
        if ($admin) {
            if (!$admin->is_active) {
                return back()->withErrors([
                    'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
                ])->onlyInput('email');
            }

            if (Auth::guard('admin')->attempt($credentials)) {
                $request->session()->regenerate();

                $user = Auth::guard('admin')->user();
                if (isset($user->role) && $user->role === 'Empleado') {
                    return redirect()->route('empleado.dashboard')
                        ->with('success', 'Inicio de sesión exitoso.');
                }

                return redirect()->route('admin.dashboard')
                    ->with('success', 'Inicio de sesión exitoso.');
            }
        }

        // Si no es admin o falló, intentar con la tabla users (empleados)
        $user = User::where('email', $credentials['email'])->first();
        if ($user) {
            // El role esperado en users es 'employee' (según esquema), y debe estar activo
            if (isset($user->role) && ($user->role === 'employee' || $user->role === 'Empleado')) {
                if (!$user->is_active) {
                    return back()->withErrors([
                        'email' => 'Tu cuenta está desactivada. Contacta al administrador.',
                    ])->onlyInput('email');
                }

                if (Auth::attempt($credentials)) {
                    $request->session()->regenerate();
                    return redirect()->route('empleado.dashboard')
                        ->with('success', 'Inicio de sesión exitoso.');
                }
            }
        }

        // Si falla la autenticación
        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesión del administrador
     */
    public function logout(Request $request)
    {
        // Cerrar sesión en ambos guards por seguridad
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        if (Auth::check()) {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Has cerrado sesión correctamente.');
    }
}