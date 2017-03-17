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

SteamApp::printAppOutput($files);
