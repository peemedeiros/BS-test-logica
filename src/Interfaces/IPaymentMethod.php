<?php

namespace App\Interfaces;

interface IPaymentMethod
{
    public function calculatesTotal(int $subTotal): int;
}