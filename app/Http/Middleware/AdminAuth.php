<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Si no hay sesión de admin, redirigir
        if (!session('admin_id')) {
            return redirect()->route('admin.login')
                ->with('error', 'Por favor inicia sesión como administrador');
        }

        // Buscar admin REAL en tabla admins
        $admin = Admin::where('id', session('admin_id'))
                      ->where('is_active', true)
                      ->first();

        if (!$admin) {
            // Limpiar sesión corrupta
            session()->forget(['admin_id', 'admin_name', 'admin_email']);
            
            return redirect()->route('admin.login')
                ->with('error', 'Tu cuenta de administrador está desactivada o no existe');
        }

        return $next($request);
    }
}
