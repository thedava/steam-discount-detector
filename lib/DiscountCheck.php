<?php

use GuzzleHttp\Client;

class DiscountCheck
{
    /** @var SteamApp */
    protected $steamApp;

    /** @var Client */
    protected $client;

    /** @var callable */
    protected $callback;

    /**
     * @return SteamApp
     */
    public function getSteamApp()
    {
        return $this->steamApp;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    public function __construct(callable $discountCheck)
    {
        $this->callback = $discountCheck;
    }

    /**
     * @param SteamApp $steamApp
     * @param Client   $client
     *
     * @return mixed
     */
    public function invoke(SteamApp $steamApp, Client $client)
    {
        $this->steamApp = $steamApp;
        $this->client = $client;

        return call_user_func($this->callback, $this);
    }
}
