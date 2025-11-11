<?php

namespace App\Contracts\Services;

interface CartServiceInterface
{
    /**
     * Agrega un producto al carrito
     */
    public function add(array $data): array;

    /**
     * Actualiza la cantidad de un producto en el carrito
     */
    public function update(array $data): array;

    /**
     * Elimina un producto del carrito
     */
    public function remove(string $productId): bool;

    /**
     * Vacía el carrito
     */
    public function clear(): bool;

    /**
     * Obtiene el contenido del carrito
     */
    public function getContent(): array;

    /**
     * Obtiene información resumida del carrito
     */
    public function getInfo(): array;

    /**
     * Procesa el carrito para crear un pedido
     */
    public function checkout(array $data): array;
}