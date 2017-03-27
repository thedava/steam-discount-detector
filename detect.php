<?php

require_once __DIR__ . '/vendor/autoload.php';
$appsDir = __DIR__ . '/apps';

// No arguments given
if (count($argv) == 1 || (isset($argv[1]) && in_array($argv[1], ['help', 'list', 'apps']))) {
    $apps = json_decode(file_get_contents(__DIR__ . '/data/apps.json'), true);

    echo 'Usage: php ', basename(__FILE__), ' <app file 1> <app file 2> <app file ...>', PHP_EOL;
    echo '       php ', basename(__FILE__), ' help|all', PHP_EOL, PHP_EOL;

    echo 'Available app files: ', PHP_EOL;
    foreach ($apps as $app) {
        echo ' - ', $app, PHP_EOL;
    }
    echo PHP_EOL;
    exit(0);
}

// Load all apps
if (isset($argv[1]) && $argv[1] == 'all') {
    $files = glob($appsDir . '/*.php');

} else {
    $files = [];
    foreach ($argv as $i => $value) {
        if ($i !== 0 && is_string($value)) {
            foreach (['', __DIR__ . '/', $appsDir . '/',] as $dir) {
                if (is_file($dir . $value)) {
                    $files[] = $value;
                    break;
                }
            }
        }
    }
}

if (empty($files)) {
    echo 'No valid files given!', PHP_EOL;
    exit(1);
}

SteamApp::printAppOutput($files);
