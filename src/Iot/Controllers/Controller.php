<?php
namespace Croak\Iot\Controllers;

use \Psr\Http\Message\ResponseInterface as Response;

class Controller
{
    private $container;

    public function __construct($container){
        $this->container = $container;
    }

    public function debug($message){
        return $this->container->logger->debug($message);
    }

    public function serverError(Response $response, $message){
        $this->debug($message);
        $body = $response->getBody();
        $body->write($message);
        return $response->withStatus(500);
    }

    public function requestError(Response $response, $message){
        $this->debug($message);
        $body = $response->getBody();
        $body->write($message);
        return $response->withStatus(400);
    }

    public function success(Response $response, $message){
        $body = $response->getBody();
        $body->write($message);
        return $response->withStatus(200);
    }

    public function getConfig(){
        return $this->container->config;
    }


}