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
* get all devices
*/
$app->get('/devices', Croak\Iot\Controllers\GetController::class.':getDevices');

/** 
* get all users
*/
$app->get('/users', Croak\Iot\Controllers\GetController::class.':getUsers');

/** 
* get measures for the device named by it sn
*/
$app->get('/measures/{sn}', Croak\Iot\Controllers\GetController::class.':getMeasures');

/** 
* get all measures
*/
$app->get('/measures', Croak\Iot\Controllers\GetController::class.':getMeasures');


/** 
* post measure in the database thanks to a POST request if device sn exists
*
* if device does not exist, it will be automatically created **** THIS SHOULD BE MODIFIED*****
* we should avoid bombing by creating a lot of devices or measures
* The best would be to associate an authentification of the devices and authorise
* to access this post route if authentification is ok
*/
$app->post('/measure/{sn}', Croak\Iot\Controllers\PostController::class.':postMeasure');

/** 
* post device in the database thanks to a POST request 
*/
$app->post('/device/{sn}', Croak\Iot\Controllers\PostController::class.':postDevice');

/** 
* post device in the database thanks to a POST request 
*/
$app->post('/device', Croak\Iot\Controllers\PostController::class.':postDevice');

/** 
* post user in the database thanks to a POST request 
*/
$app->post('/user', Croak\Iot\Controllers\PostController::class.':postUser');