<?php

namespace App\Contracts;

use App\Models\Pedido;

interface OrderServiceInterface
{
    public function create(array $data, array $cart): Pedido;
    public function cancel(Pedido $pedido): array;
    public function validateStock(array $items): bool;
    public function getActivePedidos(): array;
    public function getReadyPedidos(): array;
    public function validateCartNotEmpty(array $cart): bool;
}