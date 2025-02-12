<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Product;

class CartRepository
{
    public function getCart(): Cart
    {
        return new Cart([], 0, 0);
    }
}