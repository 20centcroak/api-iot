<?php

use Croak\Iot\Controllers\RouteController;
use Croak\Iot\Controllers\PutController;
use Croak\Iot\Controllers\GetController;

/**
* home page : displays api-IoT guide
*/
$app->get('/', \Croak\Iot\Controllers\RootController::class.':home');

/**
* install or repair the app thanks to a default set of configuration data
* and in creating the missing databases
*/
$app->get('/install', \Croak\Iot\Controllers\RootController::class.':install');

/** 
* get device information for device with the given serial number (sn)
*/
$app->get('/devices/{sn}', \Croak\Controllers\GetController::class.':getDevice');


/** 
* put measure in the database thanks to a PUT request with /devices/sn route
* where sn should match a specific pattern set in the configuration file api-config.json
*/
$app->put('/devices/{sn}', \Croak\Iot\Controllers\PutController::class.':putMeasure');