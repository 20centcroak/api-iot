<?php
namespace Croak\Iot\Controllers;

use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Container;

/**
* base Controller for all controlers*/
class Controller
{
    /**
    *@var \Slim\Container 
    */
    private $container;

    /**
    * instantiates the slim container
    * @param \Slim\Container $container the slim container
    */
    public function __construct(Container $container){
        $this->container = $container;
    }

    /**
    * logs messages in the logger with a debug level
    * @param String $message message to log
    */
    public function debug($message){
        return $this->container->logger->debug($message);
    }

    /**
    * returns a server error due to an issue with the server or the app
    * @param \Psr\Http\Message\ResponseInterface $response the response interface
    * @param String $message a message to add in the body
    */
    public function serverError(Response $response, $message){
        $this->debug($message);
        $body = $response->getBody();
        $body->write($message);
        return $response->withStatus(500);
    }

    /**
    * re
    */
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

    public function sendJson(Response $response, $data){
        return $response->withJson($data, 201);
    }

    public function getConfig(){
        return $this->container->config;
    }


}