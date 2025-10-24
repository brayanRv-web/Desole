<?php
// app/Middleware/EnsureAdminRole.php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware(\App\Middleware\EnsureAdminRole::class.':Administrador,Empleado')
     */
    public function handle(Request $request, Closure $next, string $roles = null): Response
    {
        $user = $request->user('admin');

        if (!$user) {
            // Not authenticated as admin — let auth middleware handle redirect
            return redirect()->route('admin.login');
        }

        // If no roles provided, require Administrador by default
        $allowed = $roles ? array_map('trim', explode(',', $roles)) : ['Administrador'];

        if (!in_array($user->role ?? 'Administrador', $allowed, true)) {
            // Option: redirect to dashboard with message
            return redirect()->route('admin.dashboard')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
