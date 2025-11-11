<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Pedido;
use App\Models\Cliente;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar bindings de servicios
        $this->app->bind(\App\Contracts\Services\CartServiceInterface::class, \App\Services\Cart\CartService::class);
        $this->app->bind(\App\Contracts\Services\OrderServiceInterface::class, \App\Services\Order\OrderService::class);
        $this->app->bind(\App\Contracts\Services\ProductServiceInterface::class, \App\Services\Product\ProductService::class);
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

        // Listener simple: cuando se crea un pedido actualiza estadísticas del cliente
        Pedido::created(function ($pedido) {
            try {
                if ($pedido->cliente_id) {
                    $cliente = Cliente::find($pedido->cliente_id);
                    if ($cliente) {
                        $cliente->total_pedidos = ($cliente->total_pedidos ?? 0) + 1;
                        $cliente->total_gastado = ($cliente->total_gastado ?? 0) + ($pedido->total ?? 0);
                        // Puntos: 1 punto por cada $10 gastados (ejemplo)
                        $incrementoPuntos = floor(($pedido->total ?? 0) / 10);
                        $cliente->puntos_fidelidad = ($cliente->puntos_fidelidad ?? 0) + $incrementoPuntos;

                        // Actualizar nivel
                        if ($cliente->puntos_fidelidad >= 300) {
                            $cliente->nivel_fidelidad = 3;
                        } elseif ($cliente->puntos_fidelidad >= 100) {
                            $cliente->nivel_fidelidad = 2;
                        } else {
                            $cliente->nivel_fidelidad = 1;
                        }

                        $cliente->save();
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error actualizando estadísticas cliente tras pedido: ' . $e->getMessage());
            }
        });
    }
}
