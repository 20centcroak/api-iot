<?php

use Croak\Iot\Controllers\RouteController;
use Croak\Iot\Controllers\PutController;
use Croak\Iot\Controllers\GetController;

/**
* home page : displays api-IoT guide
*/
$app->get('/', Croak\Iot\Controllers\RootController::class.':home');

/**
* install or repair the app thanks to a default set of configuration data
* and in creating the missing databases
*/
$app->get('/install', Croak\Iot\Controllers\RootController::class.':install');

/** 
* get device information for device with the given serial number (sn)
*/
$app->get('/devices/{sn}', Croak\Iot\Controllers\GetController::class.':getDevices');

/** 
* get measures for the device named by it sn
*/
$app->get('/measures/{sn}', Croak\Iot\Controllers\GetController::class.':getMeasures');

/** 
* get all measures
*/
$app->get('/measures', Croak\Iot\Controllers\GetController::class.':getMeasures');

/** 
* get measures for the device identified with the given serial number (sn)
*/
// $app->get('/devices/{sn}', \Croak\Iot\Controllers\GetController::class.':getDevice');

/** 
* put measure in the database thanks to a PUT request with /devices/sn route
* where sn should match a specific pattern set in the configuration file api-config.json
*
* if device does not exist, it will be automatically created **** THIS SHOULD BE MODIFIED*****
* we should avoid bombing by creating a lot of devices or measures
* configuration of the app should control the way the tables are accessed
* the use of tokens is recommended: see here https://www.slimframework.com/docs/features/csrf.html
*/
$app->post('/devices/{sn}/measures', Croak\Iot\Controllers\PostController::class.':postMeasure');