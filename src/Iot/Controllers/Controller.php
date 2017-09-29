<?php
namespace Croak\Iot\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Databases\IotQueries;
use Slim\Container;

/**
* base Controller for all controlers
*/
class Controller
{
    /**
    *@var Slim\Container 
    */
    private $container;

    /**
    * instantiates the slim container
    * @param Slim\Container $container the slim container
    */
    public function __construct(Container $container){
        $this->container = $container;
    }

    /**
    * logs messages in the logger with a debug level
    * @param String $message message to log
    */
    public function debug($message){
        $this->container->logger->debug($message);
    }

    /**
    * returns a server error due to an issue with the server or the app
    * @param Psr\Http\Message\ResponseInterface $response the response interface
    * @param String $message a message to add in the body
    * @return Psr\Http\Message\ResponseInterface
    */
    public function serverError(Response $response, $message){
        $this->debug($message);
        $body = $response->getBody();
        $body->write($message);
        return $response->withStatus(500);
    }

    /**
    * returns a http response with a 400 status. The request was not accepted
    * @param Psr\Http\Message\ResponseInterface $response the response interface
    * @param String $message a message to add in the body
    * @return Psr\Http\Message\ResponseInterface
    */
    public function requestError(Response $response, $message){
        $this->debug($message);
        $body = $response->getBody();
        $body->write($message);
        return $response->withStatus(400);
    }

    /**
    * returns a http response with a 200 status. The request was accepted
    * @param Psr\Http\Message\ResponseInterface $response the response interface
    * @param String $message a message to add in the body
    * @return Psr\Http\Message\ResponseInterface
    */
    public function success(Response $response, $message){
        $body = $response->getBody();
        $body->write($message);
        return $response->withStatus(200);
    }

    /**
    * returns a http response with a 201 status. The request has been created
    * @param Psr\Http\Message\ResponseInterface $response the response interface
    * @param String $address the address location to return in body
    * @return Psr\Http\Message\ResponseInterface
    */
    public function createSuccess(Response $response, $address, $message){
        $body = $response->getBody();
        $body->write($message);
        return $response->withStatus(201)->withHeader('Location',$address);
    }

    /**
    * returns a response with a json file containing the query answer.
    * @param Psr\Http\Message\ResponseInterface $response the response interface
    * @param array $data an array containing the data to be sent via json
    * @return Psr\Http\Message\ResponseInterface
    */
    public function sendJson(Response $response, $data){
        return $response->withJson($data, 201);
    }

    /**
    * getter for the app config
    * @return Croak\Iot\Init\Config
    * @throws Croak\Iot\Exception\InitException
    */
    public function getConfig(){
        return $this->container->config;
    }

    /**
    * getter for the database
    * @return Croak\Iot\DataBases\Config
    * @throws Croak\Iot\Exception\DataBaseException
    */
    public function getDataBase(){
        $db = $this->container->database;
        $url = $this->container->config->getDbUrl();
        $db->connect($url);
        return $db;
    }

    /**
    * getter for the Iot queries
    * @return Croak\Iot\Databases\Queries
    */
    public function getQueries(){
        return $this->container->queries;
    }

}