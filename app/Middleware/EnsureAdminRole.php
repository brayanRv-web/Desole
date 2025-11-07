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
        $user = $request->user(); // ✅ Usa el usuario autenticado actual

        if (!$user) {
            return redirect()->route('admin.login');
        }

        // ✅ Roles en minúsculas (consistentes)
        $allowedRoles = $roles ? array_map('strtolower', explode(',', $roles)) : ['admin'];
        
        if (!in_array($user->role, $allowedRoles)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}