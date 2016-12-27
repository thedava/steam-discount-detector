<?php

class SteamApp
{
    /**
     * The url of the steamApp
     *
     * @var string
     */
    protected $url;

    /**
     * The name of the app
     *
     * @var string
     */
    protected $name;

    /**
     * @var callable
     */
    protected $check;

    /**
     * @var int|Price[]
     */
    protected $prices = -1;

    /**
     * SteamApp constructor.
     *
     * @param string   $name
     * @param string   $url
     * @param callable $check
     */
    public function __construct($name, $url, callable $check)
    {
        $this->name = $name;
        $this->url = $url;
        $this->check = $check;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns the ageCheck url
     *
     * @return string
     */
    public function getAgeCheckUrl()
    {
        return str_replace('.com/app/', '.com/agecheck/app/', $this->getUrl());
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return callable
     */
    public function getCheck()
    {
        return $this->check;
    }

    /**
     * @param callable $check
     *
     * @return $this
     */
    public function setCheck($check)
    {
        $this->check = $check;
        return $this;
    }

    /**
     * @return Price[]
     */
    public function getPrices()
    {
        if ($this->prices === -1) {
            $this->prices = $this->checkApp();
        }

        return $this->prices;
    }

    /**
     * @return string
     */
    public function getPricesAsString()
    {
        $result = [];
        foreach ($this->getPrices() as $price) {
            $text = ($price->hasDiscount()) ? '[DISCOUNT]' : '[No Discount]';
            $text .= ' ' . $price->getName() . ' => ' . $price->getCurrentPrice();

            if ($price->hasDiscount()) {
                $text .= ' (Original: ' . $price->getPrice() . ')';
            }

            $result[] = $text;
        }

        return implode(PHP_EOL, $result);
    }

    /**
     * Checks if the app has a discount
     *
     * @return Price[]
     */
    public function checkApp()
    {
        return call_user_func($this->check, $this);
    }
}
