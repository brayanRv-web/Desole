<?php
// app/Middleware/EnsureUserRole.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware(\App\Middleware\EnsureUserRole::class.':employee')
     */
    public function handle(Request $request, Closure $next, string $roles = null): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        $allowed = $roles ? array_map('trim', explode(',', $roles)) : [];
        $allowed = array_map('strtolower', $allowed);

        $userRole = strtolower((string) ($user->role ?? ''));

        if (!in_array($userRole, $allowed, true)) {
            return redirect()->route('admin.dashboard')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
