<?php

use Croak\Iot\Init\Config;
use \Croak\DbManagementSqlite\DbManagementSqlite;
use Croak\Iot\Databases\SqliteIotQueries;
use Monolog\Logger;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

//adding user config parameters to the container
//call to readConfigFile() may result in an \Croak\Iot\Exception\InitException
//which should be caught when using this service
$container['config'] = function() {
    $config = new Config(__DIR__."/..");
    return $config;
};

//adding database service in the container
$container['database'] = function() {
    return new DbManagementSqlite();
};

//adding database service in the container
$container['queries'] = function() {
    return new SqliteIotQueries();
};