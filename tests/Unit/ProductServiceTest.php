<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    private ProductRepository|MockObject $productRepository;
    private ProductService $productService;

    public function setUp(): void
    {
        parent::setUp();

        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->productService = new ProductService($this->productRepository);
    }

    public function testGetProductsReturnsArrayOfProducts(): void
    {
        // Arrange
        $expectedProducts = [
            new Product(1, 'Shit', 10000),
            new Product(2, 'Pants', 18000)
        ];

        // Configure o mock para retornar os produtos esperados
        $this->productRepository
            ->expects($this->once())
            ->method('all')
            ->willReturn($expectedProducts);

        // Act
        $result = $this->productService->getProducts();

        // Assert
        $this->assertSame($expectedProducts, $result);
        $this->assertIsArray($result);
    }

    public function testGetProductReturnsProductById(): void
    {
        // Arrange
        $productId = 1;
        $expectedProduct = new Product(1, 'Shit', 10000);

        // Configure o mock para retornar o produto esperado
        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($expectedProduct);

        // Act
        $result = $this->productService->getProduct($productId);

        // Assert
        $this->assertSame($expectedProduct, $result);
        $this->assertInstanceOf(Product::class, $result);
    }


}