<?php

use Croak\Iot\Init\Config;

//main parameters of the Slim app
$settings = require __DIR__ . '/settings.php';

//adding the settings to the app
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/dependencies.php';

//define routes
require __DIR__ . '/routes.php';
