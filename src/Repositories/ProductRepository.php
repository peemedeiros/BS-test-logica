<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    private array $products;
    public function __construct()
    {
        $this->products = json_decode(file_get_contents(__DIR__ . '/../../products_mock.json'), true);
    }

    public function all(): array
    {
        return array_map(fn($product) => Product::fromArray($product), $this->products['products']);
    }

    public function findById(int $id): Product
    {
        return Product::fromArray($this->products['products'][$id]);
    }
}