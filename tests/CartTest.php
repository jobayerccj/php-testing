<?php

namespace Tests;

use App\Cart;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testGetNetPrice()
    {
        $cart = new Cart();
        $cart->price = 100;
        $this->assertEquals(120, $cart->getNetPrice());
    }

    public function testGetNetPriceWithDifferentTax()
    {
        $cart = new Cart();
        $cart->price = 100;
        Cart::$tax = 1.5;
        $this->assertEquals(150, $cart->getNetPrice());
    }
}
