<?php

namespace Tests\Feature;

use App\Main;
use App\Models\Cart;
use App\Models\CreditCard;
use App\Models\Pix;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;
use App\Services\CartService;
use App\Services\ProductService;
use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{
    private Main $main;
    private CartService $cartService;
    private ProductService $productService;
    private CartRepository $cartRepository;
    private ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Inicializa os repositórios
        $this->cartRepository = $this->createMock(CartRepository::class);
        $this->productRepository = $this->createMock(ProductRepository::class);

        // Inicializa os serviços com os repositórios
        $this->cartService = new CartService($this->cartRepository);
        $this->productService = new ProductService($this->productRepository);

        // Inicializa a classe principal
        $this->main = new Main($this->productService, $this->cartService);
    }

    public function testRunWithSpecificProducts(): void
    {
        // Arrange
        $cart = new Cart([], 0, 0);

        $product1 = new Product(1, 'Shirt', 10000);

        $product2 = new Product(2, 'Pants', 18000);

        // Configura o mock do CartRepository
        $this->cartRepository
            ->expects($this->once())
            ->method('getCart')
            ->willReturn($cart);

        // Configura o mock do ProductRepository
        $this->productRepository
            ->expects($this->exactly(2))
            ->method('findById')
            ->willReturnMap([
                [1, $product1],
                [2, $product2]
            ]);

        $paymentMethod = new Pix();
        $productsIds = [1, 2];

        // Act
        $result = $this->main->run($productsIds, $paymentMethod);

        // Assert
        $this->assertInstanceOf(Cart::class, $result);
        $this->assertCount(2, $result->products);
        $this->assertEquals(28000, $result->subTotalCents);
        $this->assertEquals(25200, $result->totalCents); // 10% de desconto do PIX
    }

    public function testRunWithAllProducts(): void
    {
        $cart = new Cart([], 0, 0);

        $products = [
            new Product(1, 'Shirt', 10000),
            new Product(2, 'Shoes', 20000),
            new Product(3, 'Pants', 15000)
        ];

        // Configura o mock do CartRepository
        $this->cartRepository
            ->expects($this->once())
            ->method('getCart')
            ->willReturn($cart);

        // Configura o mock do ProductRepository
        $this->productRepository
            ->expects($this->once())
            ->method('all')
            ->willReturn($products);

        $paymentMethod = new Pix();

        // Act
        $result = $this->main->run([], $paymentMethod);

        // Assert
        $this->assertInstanceOf(Cart::class, $result);
        $this->assertCount(3, $result->products);
        $this->assertEquals(45000, $result->subTotalCents);
        $this->assertEquals(40500, $result->totalCents);
    }

    public function testRunWithCreditCardPaymentMethodNoInstallments(): void
    {
        $cart = new Cart([], 0, 0);

        $product = new Product(1, 'Shirt', 20000);

        // Configura o mock do CartRepository
        $this->cartRepository
            ->expects($this->exactly(1))
            ->method('getCart')
            ->willReturn($cart);

        // Configura o mock do ProductRepository
        $this->productRepository
            ->expects($this->exactly(1))
            ->method('findById')
            ->willReturn($product);

        // Test Case 2: Cartão de Crédito à vista (10% de desconto)
        $creditCardOneInstallment = new CreditCard(1);
        $resultCreditCard = $this->main->run([1], $creditCardOneInstallment);

        // Assert Cartão à vista
        $this->assertEquals(20000, $resultCreditCard->subTotalCents);
        $this->assertEquals(18000, $resultCreditCard->totalCents);
    }

    public function testRunWithCreditCardPaymentMethod(): void
    {
        $cart = new Cart([], 0, 0);

        $product = new Product(1, 'Shirt', 20000);

        // Configura o mock do CartRepository
        $this->cartRepository
            ->expects($this->exactly(1))
            ->method('getCart')
            ->willReturn($cart);

        // Configura o mock do ProductRepository
        $this->productRepository
            ->expects($this->exactly(1))
            ->method('findById')
            ->willReturn($product);

        // Test Case 2: Cartão de Crédito à vista (10% de desconto)
        $creditCardOneInstallment = new CreditCard(3);
        $resultCreditCard = $this->main->run([1], $creditCardOneInstallment);

        // Assert Cartão à vista
        $this->assertEquals(20000, $resultCreditCard->subTotalCents);
        $this->assertEquals(20606, $resultCreditCard->totalCents);
    }
}