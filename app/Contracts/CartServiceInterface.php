<?php

namespace App\Contracts;

interface CartServiceInterface
{
    public function add(int $productoId, int $cantidad): array;
    public function update(int $productoId, int $cantidad): array;
    public function remove(int $productoId): array;
    public function clear(): void;
    public function getCart(): array;
    public function count(): int;
    public function calculateTotal(?array $cart = null): float;
}