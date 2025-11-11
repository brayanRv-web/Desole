<?php

namespace App\Contracts;

use App\Models\Cliente;

interface ClienteServiceInterface
{
    public function updateProfile(Cliente $cliente, array $data): Cliente;
    public function register(array $data): Cliente;
    public function getOrderHistory(Cliente $cliente): array;
    public function getActivePromotions(): array;
}