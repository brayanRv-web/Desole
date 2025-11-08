<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Buscar usuario con rol de admin o empleado en la tabla users
        $admin = User::where('email', $request->email)
                    ->whereIn('role', ['admin', 'employee'])
                    ->first();

        
        if ($admin && Hash::check($request->password, $admin->password)) {
            if (!$admin->is_active) {
                return back()->withErrors([
                    'email' => 'Tu cuenta está desactivada.',
                ])->onlyInput('email');
            }

            //  LIMPIAR SESIÓN DE CLIENTE ANTES DE INICIAR ADMIN
            session()->forget(['user_id', 'cliente_id']); // Limpia sesión client

            //  Login manual - guardar en sesión SIN usar Auth
            session(['admin_id' => $admin->id]);
            session(['admin_name' => $admin->name]);
            session(['admin_email' => $admin->email]);
            session(['admin_role' => $admin->role]);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Bienvenido al panel de administración');
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // ✅ Limpiar sesión de admin manualmente
        $request->session()->forget(['admin_id', 'admin_name', 'admin_email', 'admin_role']);
        //$request->session()->invalidate();
        //$request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}