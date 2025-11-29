<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Reemplazar el middleware de CSRF por defecto con nuestro personalizado
        // que excluye la ruta del carrito API
        $middleware->replace(
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        );
        
        $middleware->alias([
            'auth' => \App\Middleware\Authenticate::class,
            'guest' => \App\Middleware\RedirectIfAuthenticated::class,
            'admin.auth' => \App\Http\Middleware\AdminAuth::class, 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();