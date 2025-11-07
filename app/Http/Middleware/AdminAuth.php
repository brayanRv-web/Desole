<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_id')) {
            return redirect()->route('admin.login')->with('error', 'Por favor inicia sesión como administrador');
        }
        
        return $next($request);
    }
}