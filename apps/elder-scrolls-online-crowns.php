<?php

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

return new SteamApp('Elder Scrolls Online Crowns', 'http://store.steampowered.com/app/539001/', function (SteamApp $app) {
    $client = new Client([
        'cookies' => true,
    ]);

    // Initial request
    $client->request('GET', $app->getUrl());

    // POST age
    $response = $client->request('POST', $app->getAgeCheckUrl(), [
        'form_params'     => [
            'ageDay'   => mt_rand(1, 28),
            'ageMonth' => 'June',
            'ageYear'  => mt_rand(1970, 1989),
        ],
        'allow_redirects' => true,
    ]);

    $content = $response->getBody()->getContents();

    $dom = new Crawler($content);
    $dropDown = $dom->filter('.game_area_purchase_game_dropdown_menu_items_container');

    $result = [];
    $dropDown->filter('.game_area_purchase_game_dropdown_menu_item_text')->each(function (Crawler $element) use (&$result) {
        $discountPrice = null;
        $text = $element->text();

        // Exclude discount price
        if ($element->filter('.discount_original_price')->count() > 0) {
            $discountPrice = $element->filter('.discount_original_price')->text();
            $text = preg_replace('/<span(.*)<\/span> /', '', $element->html());
        }

        list($name, $crowns, $price) = explode(' - ', $text);

        $result[] = new Price($crowns, $price, $discountPrice);
    });

    return $result;
});
