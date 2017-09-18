<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Croak\Iot\Exceptions\DataBaseException;
use Croak\Iot\Exceptions\DeviceException;
use Croak\Iot\Exceptions\MeasureException;
use Croak\Iot\Requests;
use Croak\Iot\Device;

$app->get('/intall', function (Request $request, Response $response, $args) 
{
    $this->logger->debug("accessing install page");
    $response->getBody()->write("installation in progress !");

    //build the app: create database and tables
    try{
        try{
            Build::createInitFile($config);
        }
        catch(BuildException $be){
            $logger->debug($be->getMessage());
            return $be->getMessage();
        }
        catch(InitException $e){
            $logger->debug($e->getMessage());
            return $e->getMessage();
        }
        Build::build($config);
    }
    catch(BuildException $be){
        $logger->debug($be->getMessage());
        return $be->getMessage();
    }

    return $response;
});

$app->get('/', function (Request $request, Response $response, $args) 
{
    $this->logger->debug("accessing home page");
    $response->getBody()->write("Welcome !");
    return $response;
});

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
$app->put('/devices/{sn}', function ($request, $response, $args) 
{    
    $sn = (string)$args['sn'];
    $json = $request->getBody();

    try{
        Requests::putMeasure($sn, $json, $config);
    }
    catch(DeviceException $e){
        $this->logger->debug($e->getMessage());
        return $e->getMessage();
    }
    catch(MeasureException $e){
        $this->logger->debug($e->getMessage());
        return $e->getMessage();
    }
    catch(DataBaseException $e){
        $this->logger->debug($e->getMessage());
        return $e->getMessage();
    }

    $success = "measure added correctly";
    $this->logger->debug($success);
    return $success;
});