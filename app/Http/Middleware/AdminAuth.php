<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_id')) {
            return redirect()->route('admin.login')
                ->with('error', 'Por favor inicia sesión como administrador');
        }

        // Verificar si el usuario existe, está activo y es empleado
        $admin = \App\Models\User::where('id', session('admin_id'))
                                ->where('role', 'employee')
                                ->where('is_active', true)
                                ->first();
        if (!$admin || !$admin->is_active) {
            session()->forget(['admin_id', 'admin_name', 'admin_email', 'admin_role']);
            return redirect()->route('admin.login')
                ->with('error', 'Tu cuenta de administrador está desactivada o no existe');
        }
        
        return $next($request);
    }
}