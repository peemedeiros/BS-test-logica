<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductService
{
    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getProducts(): array
    {
        return $this->productRepository->all();
    }

    public function getProduct(int $id): Product
    {
        return $this->productRepository->findById($id);
    }
}