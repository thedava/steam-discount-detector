<?php

use Symfony\Component\DomCrawler\Crawler;

return new SteamApp('Elder Scrolls Online Crowns', 'http://store.steampowered.com/app/539001/', new DiscountCheck(function (DiscountCheck $check) {
    $client = $check->getClient();
    $app = $check->getSteamApp();

    // Initial request
    $client->request('GET', $app->getUrl());

    $response = $app->performAgeCheck($client);
    $content = $response->getBody()->getContents();

    $dom = new Crawler($content);
    $dropDown = $dom->filter('.game_area_purchase_game_dropdown_menu_items_container');

    $result = [];
    $dropDown->filter('.game_area_purchase_game_dropdown_menu_item_text')->each(function (Crawler $element) use (&$result) {
        $discountPrice = null;
        $originalPrice = null;
        $text = $element->text();

        // Exclude discount price
        if ($element->filter('.discount_original_price')->count() > 0) {
            $originalPrice = $element->filter('.discount_original_price')->text();
            $text = preg_replace('/<span(.*)<\/span> /', '', $element->html());
        }

        list(, $crowns, $price) = explode(' - ', $text);

        // Map prices
        if ($originalPrice !== null) {
            $discountPrice = $price;
            $price = $originalPrice;
        }

        $result[] = new Price($crowns, $price, $discountPrice);
    });

    return $result;
}));
