<?php

require_once __DIR__ . '/../vendor/autoload.php';

$parentDir = dirname(__DIR__);
$appsDir = realpath(__DIR__ . '/../apps');
$files = [];

foreach (glob($appsDir . '/*.php') as $file) {
    $files[] = substr($file, strlen($parentDir) + 1);
}

sort($files, SORT_ASC | SORT_LOCALE_STRING);

file_put_contents(__DIR__ . '/../data/apps.json', json_encode($files, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
