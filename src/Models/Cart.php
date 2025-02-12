<?php

namespace App\Models;

class Cart
{
    public function __construct(
        public array $products,
        public int $subTotalCents,
        public int $totalCents,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['products'],
            $data['subTotalCents'],
            $data['totalCents'],
        );
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}