<?php

define('APP_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$server = $app->make('HttpServer');

$server->start();
