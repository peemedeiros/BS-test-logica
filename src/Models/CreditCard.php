<?php

namespace App\Models;

use App\Interfaces\IPaymentMethod;

class CreditCard implements IPaymentMethod
{
    const DISCOUNT_PERCENTAGE = 0.10;
    const TAX_PERCENTAGE = 0.01;
    public function __construct(private int $installmentsAmount = 1)
    {
    }

    public function calculatesTotal(int $subTotal): int
    {
        if ($this->installmentsAmount === 1) {
            $subTotal -= $subTotal * self::DISCOUNT_PERCENTAGE;
            return $subTotal;
        }

        return round($subTotal * (1 + self::TAX_PERCENTAGE) ** $this->installmentsAmount);
    }
}