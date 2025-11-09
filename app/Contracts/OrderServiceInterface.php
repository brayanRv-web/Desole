<?php

namespace App\Contracts;

use App\Models\Pedido;

interface OrderServiceInterface
{
    public function create(array $data, array $cart): Pedido;
    public function cancel(Pedido $pedido): array;
    public function getOrderDetails(Pedido $pedido): array;
    public function validateOrder(array $cart): bool;
}