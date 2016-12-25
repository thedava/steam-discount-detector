<?php

require_once __DIR__ . '/vendor/autoload.php';


$client = new \GuzzleHttp\Client([
    'cookies' => true,
]);

// Initial request
$client->request('GET', 'http://store.steampowered.com/app/376850/');

// POST age
$response = $client->request('POST', 'http://store.steampowered.com/agecheck/app/376850/', [
    'form_params' => [
        'ageDay' => mt_rand(1, 28),
        'ageMonth' => 'June',
        'ageYear' => mt_rand(1970, 1989),
    ],
    'allow_redirects' => true,
]);

$content = $response->getBody()->getContents();

$dom = new Symfony\Component\DomCrawler\Crawler($content);
$dropDown = $dom->filter('.game_area_purchase_game_dropdown_menu_items_container');

$dropDown->filter('.game_area_purchase_game_dropdown_menu_item_text')->each(function (\Symfony\Component\DomCrawler\Crawler $element) {
    if ($element->filter('.discount_original_price')->count() > 0) {
        echo '[DISCOUNT] ', preg_replace('/<span(.*)<\/span> /', '', $element->html()), PHP_EOL;
    } else {
        echo '[No Discount] ', $element->html(), PHP_EOL;
    }
});
