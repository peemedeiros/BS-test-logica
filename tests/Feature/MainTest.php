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

        $this->cartRepository = $this->createMock(CartRepository::class);
        $this->productRepository = $this->createMock(ProductRepository::class);

        $this->cartService = new CartService($this->cartRepository);
        $this->productService = new ProductService($this->productRepository);

        $this->main = new Main($this->productService, $this->cartService);
    }

    public function testRunWithSpecificProducts(): void
    {
        $cart = new Cart([], 0, 0);

        $products = [
            new Product(1, 'Shirt', 10000),
            new Product(2, 'Pants', 18000),
        ];

        $this->cartRepository
            ->expects($this->once())
            ->method('getCart')
            ->willReturn($cart);

        $this->productRepository
            ->expects($this->once())
            ->method('all')
            ->willReturn($products);

        $paymentMethod = new Pix();

        $result = $this->main->run([], $paymentMethod);

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

        $this->cartRepository
            ->expects($this->once())
            ->method('getCart')
            ->willReturn($cart);

        $this->productRepository
            ->expects($this->once())
            ->method('all')
            ->willReturn($products);

        $paymentMethod = new Pix();

        $result = $this->main->run([], $paymentMethod);

        $this->assertInstanceOf(Cart::class, $result);
        $this->assertCount(3, $result->products);
        $this->assertEquals(45000, $result->subTotalCents);
        $this->assertEquals(40500, $result->totalCents);
    }

    public function testRunWithCreditCardPaymentMethodNoInstallments(): void
    {
        $cart = new Cart([], 0, 0);

        $products = [
            new Product(1, 'Shirt', 10000),
            new Product(2, 'Shoes', 20000),
            new Product(3, 'Pants', 15000)
        ];

        $this->cartRepository
            ->expects($this->exactly(1))
            ->method('getCart')
            ->willReturn($cart);

        $this->productRepository
            ->expects($this->once())
            ->method('all')
            ->willReturn($products);

        $creditCardOneInstallment = new CreditCard(1);
        $resultCreditCard = $this->main->run([], $creditCardOneInstallment);

        $this->assertEquals(45000, $resultCreditCard->subTotalCents);
        $this->assertEquals(40500, $resultCreditCard->totalCents);
    }

    public function testRunWithCreditCardPaymentMethod(): void
    {
        $cart1 = new Cart([], 0, 0);
        $cart2 = new Cart([], 0, 0);
        $cart3 = new Cart([], 0, 0);

        $products = [
            new Product(1, 'Shirt', 12000),
            new Product(2, 'Pants', 18000),
            new Product(3, 'Shoes', 20000),
            new Product(4, 'Hat', 5000),
        ];

        $this->cartRepository
            ->expects($this->exactly(3))
            ->method('getCart')
            ->willReturnOnConsecutiveCalls($cart1, $cart2, $cart3);

        $this->productRepository
            ->expects($this->exactly(3))
            ->method('all')
            ->willReturn($products);

        // Parcelado em 2x
        $creditCardTwoInstallment = new CreditCard(2);
        $resultCreditCard = $this->main->run([], $creditCardTwoInstallment);

        // Parcelado em 3x
        $creditCardThreeInstallment = new CreditCard(3);
        $resultCreditCard2 = $this->main->run([], $creditCardThreeInstallment);

        // Parcelado em 6x
        $creditCardSixInstallment = new CreditCard(6);
        $resultCreditCard3 = $this->main->run([], $creditCardSixInstallment);

        $this->assertEquals(55000, $resultCreditCard->subTotalCents);
        $this->assertEquals(56105, $resultCreditCard->totalCents);

        $this->assertEquals(55000, $resultCreditCard2->subTotalCents);
        $this->assertEquals(56667, $resultCreditCard2->totalCents);

        $this->assertEquals(55000, $resultCreditCard3->subTotalCents);
        $this->assertEquals(58384, $resultCreditCard3->totalCents);
    }
}