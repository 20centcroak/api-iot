<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Croak\Iot\Device;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Exceptions\DataBaseException;
use Croak\Iot\Exceptions\MeasureException;
use Croak\Iot\Measure;

$app->get('/', function (Request $request, Response $response, $args) 
{
    $this->logger->addInfo("accessing home page");
    $response->getBody()->write("Welcome !");
    return $response;
});

$app->get('/devices/{sn}', function (Request $request, Response $response, $args) 
{
	$id = (string)$args['sn'];
    $this->logger->addInfo("get profile for ".$id);

    $device = new Device($sn, $id);
    $device->getID();

    $response->getBody()->write("Hi ".$id." !");

    return $response;
});

$app->put('/devices/{sn}', function ($request, $response, $args) 
{    
    $id = (string)$args['sn'];
    $this->logger->addInfo("put infos for ".$id);    

    $json = $request->getBody();
    $this->logger->addInfo("dat = ".$dat);

    $measure;
    try{
        $measure = Measure::create($json, $id);
        $measure->populate();
    }
    catch(MeasureException $e){
        $this->logger->addInfo($e->getMessage());
        return $e->getMessage();
    }

    $success = "measure added correctly";
    $this->logger->addInfo($success);
    return $success;

});