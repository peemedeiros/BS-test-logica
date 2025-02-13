<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Services\CartService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CartServiceTest extends TestCase
{
    private CartRepository|MockObject $cartRepository;
    private CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cartRepository = $this->createMock(CartRepository::class);
        $this->cartService = new CartService($this->cartRepository);
    }

    public function testCartReturnsCartFromRepository(): void
    {
        $expectedCart = new Cart([], 0, 0);

        $this->cartRepository
            ->expects($this->once())
            ->method('getCart')
            ->willReturn($expectedCart);

        $result = $this->cartService->cart();

        $this->assertSame($expectedCart, $result);
        $this->assertInstanceOf(Cart::class, $result);
    }

    public function testAddProductToCartWithEmptyCart(): void
    {
        $cart = new Cart([], 0, 0);

        $product = new Product(1, 'Shirt', 10000);

        $updatedCart = $this->cartService->addProductToCart($cart, $product);

        $this->assertCount(1, $updatedCart->products);
        $this->assertSame($product, $updatedCart->products[0]);
        $this->assertEquals(10000, $updatedCart->subTotalCents);
    }

    public function testAddProductToCartWithExistingProducts(): void
    {
        $cart = new Cart([], 0, 0);

        $product1 = new Product(1, 'Shirt', 10000);
        $product2 = new Product(2, 'Pants', 18000);

        $cart = $this->cartService->addProductToCart($cart, $product1);

        $updatedCart = $this->cartService->addProductToCart($cart, $product2);

        $this->assertCount(2, $updatedCart->products);
        $this->assertSame($product1, $updatedCart->products[0]);
        $this->assertSame($product2, $updatedCart->products[1]);
        $this->assertEquals(28000, $updatedCart->subTotalCents);
    }

    public function testAddProductToCartUpdatesSubtotalCorrectly(): void
    {
        $cart = new Cart([], 0, 0);

        $products = [
            $this->createProduct(1, 'shirt', 15000),
            $this->createProduct(2, 'pants', 18000),
            $this->createProduct(3, 'shoes', 20000)
        ];

        foreach ($products as $product) {
            $cart = $this->cartService->addProductToCart($cart, $product);
        }

        $this->assertEquals(53000, $cart->subTotalCents);
        $this->assertCount(3, $cart->products);
    }

    private function createProduct(int $id, string $name, int $priceCents): Product
    {
        return new Product($id, $name, $priceCents);
    }
}