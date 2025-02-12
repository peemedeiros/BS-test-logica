<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Repositories\CartRepository;

class CartService
{
    public function __construct(private CartRepository $cartRepository)
    {
    }
    public function cart(): Cart
    {
        return $this->cartRepository->getCart();
    }

    public function addProductToCart(Cart $cart, Product $product): Cart
    {
        $cart->products[] = $product;

        $cart->subTotalCents = $this->calculateSubtotal($cart);

        return $cart;
    }

    private function calculateSubtotal(Cart $cart): int
    {
        $products = $cart->products;
        $subTotal = 0;
        foreach ($products as $product) {
            $subTotal += $product->priceCents;
        }
        return $subTotal;
    }
}