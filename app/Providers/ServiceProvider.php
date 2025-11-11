<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Services\{
    CartServiceInterface,
    OrderServiceInterface,
    ProductServiceInterface
};
use App\Services\Cart\CartService;
use App\Services\Order\OrderService;
use App\Services\Product\ProductService;

class ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar CartService
        $this->app->bind(CartServiceInterface::class, CartService::class);

        // Registrar OrderService
        $this->app->bind(OrderServiceInterface::class, OrderService::class);

        // Registrar ProductService
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
    }

    public function boot(): void
    {
        //
    }
}