<?php

namespace App;

use App\Interfaces\IPaymentMethod;
use App\Models\Pix;
use App\Services\CartService;
use App\Services\ProductService;

class Main
{
    public function __construct(
        private ProductService $productService,
        private CartService $cartService,
    )
    {
    }

    public function run(
        array $productsIdsToAddInTheCart = [],
        IPaymentMethod $paymentMethod = new Pix()
    )
    {
        $cart = $this->cartService->cart();

        if (!empty($productsIdsToAddInTheCart)) {
            foreach ($productsIdsToAddInTheCart as $productId) {
                $cart = $this->cartService->addProductToCart($cart, $this->productService->getProduct($productId));
            }
        } else {
            foreach ($this->productService->getProducts() as $product) {
                $cart = $this->cartService->addProductToCart($cart, $product);
            }
        }


        $cart->totalCents = $paymentMethod->calculatesTotal($cart->subTotalCents);

        return $cart;
    }
}