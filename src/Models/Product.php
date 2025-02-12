<?php

namespace App\Models;

class Product
{
    public function __construct(
        public string $id,
        public string $name,
        public int $priceCents,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['priceCents'],
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}