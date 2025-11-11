<?php

namespace App\DTOs\Cart;

class CartItemDTO
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
        public readonly float $price,
        public readonly string $name,
        public readonly ?string $notes = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['product_id'],
            quantity: $data['quantity'],
            price: $data['price'],
            name: $data['name'],
            notes: $data['notes'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'name' => $this->name,
            'notes' => $this->notes,
        ];
    }
}