<?php

require_once __DIR__ . '/vendor/autoload.php';

$files = [];
foreach ($argv as $i => $value) {
    if ($i !== 0 && is_string($value) && is_file($value)) {
        $files[] = $value;
    }
}

// Load all apps if there is no valid file given
if (empty($files)) {
    $files = glob(__DIR__ . '/apps/*.php');
}

// Collect outputs
$outputs = [];
foreach ($files as $file) {
    $outputs[$file] = [
        'basename' => basename($file),
    ];

    /** @var SteamApp $app */
    $app = include $file;

    if (!$app instanceof SteamApp) {
        $outputs[$file]['error'] = 'Not a valid app file!';
        continue;
    }

    $outputs[$file]['app'] = $app;
    $outputs[$file]['output'] = $app->getPricesAsString();
}

// Output
foreach ($outputs as $file => $output) {
    $headline = 'App: ' . $output['basename'];
    echo $headline, PHP_EOL, str_repeat('=', strlen($headline)), PHP_EOL, PHP_EOL;

    if (isset($output['error'])) {
        echo $output['error'], PHP_EOL;
        echo 'File: ', $file, PHP_EOL;
    } else {
        $app = $output['app'];
        echo 'Name: ', $app->getName(), PHP_EOL;
        echo 'Url: ', $app->getUrl(), PHP_EOL;

        echo PHP_EOL, 'Output:', PHP_EOL, str_repeat('-', 8), PHP_EOL;
        echo trim($output['output']), PHP_EOL;
        echo str_repeat('-', 8), PHP_EOL;
    }

    echo PHP_EOL, PHP_EOL, PHP_EOL;
}
