<?php

namespace App\Contracts\Services;

interface OrderServiceInterface
{
    /**
     * Crea un nuevo pedido
     */
    public function create(array $data): array;

    /**
     * Obtiene un pedido por su ID
     */
    public function get(int $id): array;

    /**
     * Obtiene todos los pedidos con filtros opcionales
     */
    public function getAll(array $filters = []): array;

    /**
     * Actualiza el estado de un pedido
     */
    public function updateStatus(int $id, string $status): array;

    /**
     * Cancela un pedido
     */
    public function cancel(int $id): array;
}