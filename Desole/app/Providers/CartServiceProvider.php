<?php

namespace App\Providers;

use App\Contracts\CartServiceInterface;
use App\Services\CartService;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CartServiceInterface::class, CartService::class);
    }

    public function boot(): void
    {
        //
    }
}