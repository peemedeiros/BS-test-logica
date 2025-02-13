<?php

namespace App\Models;

use App\Interfaces\IPaymentMethod;

class Pix implements IPaymentMethod
{
    const DISCOUNT_PERCENTAGE = 0.10;
    public function calculatesTotal(int $subTotal): int
    {
        $subTotal -= $subTotal * self::DISCOUNT_PERCENTAGE;

        return round($subTotal, mode: PHP_ROUND_HALF_DOWN);
    }
}