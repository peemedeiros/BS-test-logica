<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\CreditCard;
use App\Models\Product;
use App\Repositories\CartRepository;
use App\Services\CartService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreditCardTest extends TestCase
{

    public function testCreditCardPaymentMethod(): void
    {
        $creditCard1 = new CreditCard();
        $creditCard2 = new CreditCard(5);
        $creditCard3 = new CreditCard(3);
        $creditCard4 = new CreditCard(7);
        $creditCard5 = new CreditCard(2);

        $creditCard1Result = $creditCard1->calculatesTotal(43900); //R$ 439,00
        $creditCard2Result = $creditCard2->calculatesTotal(22300); //R$ 223,00
        $creditCard3Result = $creditCard3->calculatesTotal(18999); //R$ 189,99
        $creditCard4Result = $creditCard4->calculatesTotal(34500); //R$ 345,00
        $creditCard5Result = $creditCard5->calculatesTotal(50000); //R$ 500,00

        $this->assertEquals(39510, $creditCard1Result); // R$ 395,10
        $this->assertEquals(23438, $creditCard2Result); // R$ 234,38
        $this->assertEquals(19575, $creditCard3Result); // R$ 195,75
        $this->assertEquals(36989, $creditCard4Result); // R$ 369,89
        $this->assertEquals(51005, $creditCard5Result); // R$ 510,05
    }


}