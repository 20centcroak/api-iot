<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
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

    $device = new Device($sn);
    $device->getID();

    $response->getBody()->write("Hi ".$sn." !");

    return $response;
});


$app->put('/devices/{sn}', function ($request, $response, $args) 
{
    
    $id = (string)$args['sn'];
    $this->logger->addInfo("put infos for ".$id);    
    
    // $filename = "../register/".$id."/temp.txt";

    // if(!file_exists($filename)) 
    // {
    //     $response->getBody()->write("unknown device");
    //     return $response;
    // }

    // $temp = fopen($filename,"a") or die("Unable to open file!");

    // $this->logger->addInfo("file open successfully"); 

    $dat = $request->getBody();
    $this->logger->addInfo("dat = ".$dat);
    $data = json_decode($dat);

    $mesure = $data->{'temp'};
    $this->logger->addInfo("mesure = ".$mesure); 



    // fwrite($temp,$mesure."\n");
    // fclose($temp);

});