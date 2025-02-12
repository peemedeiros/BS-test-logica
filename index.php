<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Main;

$cart = (new Main(
    new \App\Services\ProductService(
        new \App\Repositories\ProductRepository()
    ),
    new \App\Services\CartService(
        new \App\Repositories\CartRepository()
    )
))->run(paymentMethod: new \App\Models\CreditCard(3));

var_dump($cart);
