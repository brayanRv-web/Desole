<?php
// app/Middleware/EnsureAdminRole.php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next, string $roles = null): Response
    {
        // Intentar obtener usuario del guard admin o web
        $user = $request->user('admin') ?: $request->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        if (!$user->is_active) {
            if ($request->user('admin')) {
                auth('admin')->logout();
            } else {
                auth()->logout();
            }
            return redirect()->route('admin.login')
                ->with('error', 'Tu cuenta está desactivada. Contacta al administrador.');
        }

        // Si es un Admin de la tabla admins, tiene acceso total
        if ($request->user('admin')) {
            return $next($request);
        }

        // Para usuarios de la tabla users, verificar rol de empleado
        if ($user->isEmployee()) {
            // Si se especifican roles, verificarlos
            if ($roles) {
                $allowedRoles = array_map('strtolower', explode(',', $roles));
                if (!in_array('employee', $allowedRoles)) {
                    return redirect()->route('admin.dashboard')
                        ->with('error', 'No tienes permiso para acceder a esta sección.');
                }
            }
            return $next($request);
        }

        return redirect()->route('admin.dashboard')
            ->with('error', 'No tienes permiso para acceder a esta sección.');

        return $next($request);
    }
}