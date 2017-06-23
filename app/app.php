<?php

use Croak\Iot\Init\Config;

$app = new \Slim\App(["settings" => $params]);

$container = $app->getContainer();
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};
$container['config'] = function($c) {
    $config = new Config();
    $config->readConfigFile();
    return $config;
};