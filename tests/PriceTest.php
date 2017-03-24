<?php

class PriceTest extends PHPUnit_Framework_TestCase
{
    public function testHasDiscountPrice()
    {
        $price = new Price('PHPUnit', 100, 80);
        $this->assertTrue($price->hasDiscount());
    }

    public function testHasNoDiscountPrice()
    {
        $price = new Price('PHPUnit', 100, null);
        $this->assertFalse($price->hasDiscount());
    }
}
