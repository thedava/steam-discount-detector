<?php

class SteamAppTest extends PHPUnit_Framework_TestCase
{
    protected function getSteamApp()
    {
        return new SteamApp('PHPUnit', 'phpunit.de', function (SteamApp $app) {
            return $app->getName() . ' - ' . $app->getUrl();
        });
    }

    public function testGetAgeCheckUrl()
    {
        $app = $this->getSteamApp();

        // GTA Online Cash Cards
        $app->setUrl('http://store.steampowered.com/app/376850/');
        $this->assertEquals('http://store.steampowered.com/agecheck/app/376850/', $app->getAgeCheckUrl());
    }

    public function testCheckApp()
    {
        $this->assertEquals('PHPUnit - phpunit.de', $this->getSteamApp()->checkApp());
    }

    public function testGetName()
    {
        $this->assertEquals('PHPUnit', $this->getSteamApp()->getName());
    }

    public function testGetUrl()
    {
        $this->assertEquals('phpunit.de', $this->getSteamApp()->getUrl());
    }
}
