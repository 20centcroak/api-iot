<?php

use Croak\Iot\Init\Config;
use Slim\App;

//main parameters of the Slim app
$settings = require 'settings.php';

//adding the settings to the app
$app = new App($settings);

// Set up dependencies
require 'dependencies.php';

//define routes
require 'routes.php';
