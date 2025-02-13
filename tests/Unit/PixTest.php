<?php

namespace Tests\Unit;

use App\Models\Pix;
use PHPUnit\Framework\TestCase;

class PixTest extends TestCase
{

    public function testCreditCardPaymentMethod(): void
    {
        $pix1 = new Pix();
        $pix2 = new Pix();
        $pix3 = new Pix();
        $pix4 = new Pix();
        $pix5 = new Pix();

        $pix1Result = $pix1->calculatesTotal(43900); //R$ 439,00
        $pix2Result = $pix2->calculatesTotal(22300); //R$ 223,00
        $pix3Result = $pix3->calculatesTotal(18999); //R$ 189,99
        $pix4Result = $pix4->calculatesTotal(34500); //R$ 345,00
        $pix5Result = $pix5->calculatesTotal(50000); //R$ 500,00

        $this->assertEquals(39510, $pix1Result); // R$ 395,10
        $this->assertEquals(20070, $pix2Result); // R$ 200,70
        $this->assertEquals(17099, $pix3Result); // R$ 170,99
        $this->assertEquals(31050, $pix4Result); // R$ 310,50
        $this->assertEquals(45000, $pix5Result); // R$ 450,00
    }


}