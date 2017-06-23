<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Croak\Iot\Exceptions\DataBaseException;
use Croak\Iot\Exceptions\DeviceException;
use Croak\Iot\Exceptions\MeasureException;
use Croak\Iot\Requests;
use Croak\Iot\Device;

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

    try{
       $info = Requests::getDevice($sn, $this->config);
       $response->getBody()->write($info." device found");
    }
    catch(DeviceException $e){
        $this->logger->addInfo($e->getMessage());
        return $e->getMessage();
    }

});

/** 
* put measure in the database thanks to a PUT request with /devices/sn route
* where sn should match a specific pattern set in the configuration file api-config.json
*/
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
    catch(DataBaseException $e){
        $this->logger->addInfo($e->getMessage());
        return $e->getMessage();
    }

    $success = "measure added correctly";
    $this->logger->addInfo($success);
    return $success;
});