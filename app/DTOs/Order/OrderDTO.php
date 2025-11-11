<?php

namespace App\DTOs\Order;

class OrderDTO
{
    public function __construct(
        public readonly array $items,
        public readonly float $total,
        public readonly int $clientId,
        public readonly string $status,
        public readonly ?string $notes = null,
        public readonly ?int $id = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            items: $data['items'],
            total: $data['total'],
            clientId: $data['client_id'],
            status: $data['status'],
            notes: $data['notes'] ?? null,
            id: $data['id'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'items' => $this->items,
            'total' => $this->total,
            'client_id' => $this->clientId,
            'status' => $this->status,
            'notes' => $this->notes,
        ];
    }
}