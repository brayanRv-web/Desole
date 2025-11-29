<?php
//RedirectIfAuthenticated
namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Determinar a dónde redirigir según el guard
                switch ($guard) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'cliente':
                        return redirect()->route('cliente.dashboard');
                    default:
                        return redirect('/');
                }
            }
        }

        return $next($request);
    }
}