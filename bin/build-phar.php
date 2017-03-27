<?php

require_once __DIR__ . '/../vendor/autoload.php';

$pharFile = realpath(__DIR__ . '/../') . '/steam-discount-detector.phar';

// Dirty checks
if (is_dir(__DIR__ . '/../vendor/phpunit')) {
    echo 'Attention: dev-dependencies detected!', PHP_EOL;
}

if (file_exists($pharFile)) {
    echo 'Old file size: ', round(filesize($pharFile) / 1024, 2), ' kB', PHP_EOL;
    unlink($pharFile);
}

$phar = new Phar($pharFile);
$phar->buildFromDirectory(dirname(__DIR__), '/(apps|lib|vendor|detect\.php|data)/');
$phar->setStub($phar->createDefaultStub('detect.php'));

echo 'File size: ', round(filesize($pharFile) / 1024, 2), ' kB', PHP_EOL;
