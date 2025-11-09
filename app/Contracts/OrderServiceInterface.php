<?php

namespace App\Contracts;

use App\Models\Pedido;
use Illuminate\Support\Collection;

interface OrderServiceInterface
{
    public function create(array $data, array $cart): Pedido;
    public function cancel(Pedido $pedido): array;
    public function getOrderDetails(Pedido $pedido): array;
    public function validateOrder(array $cart): bool;
    public function validateStock(array $items): array;
    public function getActivePedidos(): Collection;
    public function getReadyPedidos(): Collection;
    public function validateCartNotEmpty(array $cart): void;
}