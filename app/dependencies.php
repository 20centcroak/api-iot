<?php

use Croak\Iot\Init\Config as Config;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

//adding user config parameters to the container
$container['config'] = function() {
    $config = new Config();
    $config->readConfigFile();
    return $config;
};