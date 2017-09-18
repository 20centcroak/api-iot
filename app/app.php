<?php

use Croak\Iot\Init\Config;

//main parameters of the Slim app
$params['displayErrorDetails'] = true;
$params['addContentLengthHeader'] = false;

//adding the settings to the app
$app = new \Slim\App(["settings" => $params]);

//definition of a Monolog logger for the app
$logger = new \Monolog\Logger('my_logger');
$file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log",Monolog\Logger::DEBUG); //debug mode
$logger->pushHandler($file_handler);

//adding resources to the Pimple container
$container = $app->getContainer();
$container['logger'] = $logger;

$container['config'] = function($c) {
    $config = new Config();
    $config->readConfigFile();
    return $config;
};