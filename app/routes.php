<?php


use Croak\Iot\Controllers\RouteController;
use Croak\Iot\Controllers\PutController;

$app->get('/', \Croak\Iot\Controllers\RootController::class.':home');
$app->get('/install', \Croak\Iot\Controllers\RootController::class.':install');
$app->get('/try', \Croak\Iot\Controllers\RootController::class.':try');


$app->get('/devices/{sn}', function (Request $request, Response $response, $args) 
{
	$sn = (string)$args['sn'];
    $this->logger->debug("get profile for ".$sn);

    try{
       $info = Requests::getDevice($sn, $this->config);
       $response->getBody()->write($sn." device found, created on ".$info);
    }
    catch(DeviceException $e){
        $this->logger->debug($e->getMessage());
        return $e->getMessage();
    }

});

/** 
* put measure in the database thanks to a PUT request with /devices/sn route
* where sn should match a specific pattern set in the configuration file api-config.json
*/
$app->put('/devices/{sn}', \Croak\Iot\Controllers\PutController::class.':putMeasure');