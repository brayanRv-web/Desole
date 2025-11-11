<?php

namespace App\Contracts\Services;

interface ProductServiceInterface
{
    /**
     * Obtiene un producto por su ID
     */
    public function get(int $id): array;

    /**
     * Obtiene todos los productos con filtros opcionales
     */
    public function getAll(array $filters = []): array;

    /**
     * Crea un nuevo producto
     */
    public function create(array $data): array;

    /**
     * Actualiza un producto existente
     */
    public function update(int $id, array $data): array;

    /**
     * Elimina un producto
     */
    public function delete(int $id): bool;

    /**
     * Actualiza el estado de un producto
     */
    public function updateStatus(int $id, string $status): bool;

    /**
     * Actualiza el stock de un producto
     */
    public function updateStock(int $id, int $quantity): bool;
}