<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Croak\Iot\Exceptions\DeviceException;
use Croak\Iot\Exceptions\MeasureException;
use Croak\Iot\Requests;

$app->get('/', function (Request $request, Response $response, $args) 
{
    $this->logger->addInfo("accessing home page");
    $response->getBody()->write("Welcome !");
    return $response;
});

$app->get('/devices/{sn}', function (Request $request, Response $response, $args) 
{
	$sn = (string)$args['sn'];
    $this->logger->addInfo("get profile for ".$sn);

    $device = new Device($sn, $sn);
    $device->getsn();

    $response->getBody()->write("Hi ".$sn." !");

    return $response;
});

$app->put('/devices/{sn}', function ($request, $response, $args) 
{    
    $sn = (string)$args['sn'];
    $json = $request->getBody();

    try{
        Requests::putMeasure($sn, $json, $config);
    }
    catch(DeviceException $e){
        $this->logger->addInfo($e->getMessage());
        return $e->getMessage();
    }
    catch(MeasureException $e){
        $this->logger->addInfo($e->getMessage());
        return $e->getMessage();
    }

    $success = "measure added correctly";
    $this->logger->addInfo($success);
    return $success;
});