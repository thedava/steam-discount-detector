<?php

class Price
{
    /**
     * Name of the product
     *
     * @var string
     */
    protected $name;

    /**
     * Regular price of the product
     *
     * @var float
     */
    protected $price;

    /**
     * Discount (current) price of the product
     *
     * @var float|null
     */
    protected $discountPrice;

    /**
     * Price constructor.
     *
     * @param string     $name
     * @param float      $price
     * @param float|null $discountPrice
     */
    public function __construct($name, $price, $discountPrice = null)
    {
        $this->name = $name;
        $this->price = $price;
        $this->discountPrice = $discountPrice;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return float|null
     */
    public function getDiscountPrice()
    {
        return $this->discountPrice;
    }

    /**
     * @return bool
     */
    public function hasDiscount()
    {
        return $this->getDiscountPrice() !== null;
    }

    /**
     * @return float|null
     */
    public function getCurrentPrice()
    {
        return ($this->hasDiscount())
            ? $this->getDiscountPrice()
            : $this->getPrice();
    }
}
