<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Rutas web normales
        Route::middleware('web')
            ->group(base_path('routes/web.php'));

        // Rutas del administrador
        Route::middleware('web')
            ->prefix('admin')
            ->group(base_path('routes/admin.php'));
    }
}
