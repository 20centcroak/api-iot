<?php
namespace Croak\Iot\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Croak\Iot\Init\Build as Build;
use Croak\Iot\Exceptions\BuildException as BuildException;
use Croak\Iot\Exceptions\InitException as InitException;
use  Croak\Iot\Databases\DbManagement as DbManagement;

/**
* controller for specific requests (installation, home page)
*/
class RootController extends Controller
{
    /**
    * displays home page
    * @param \Psr\Http\Message\ServerRequestInterface $request
    * @param \Psr\Http\Message\ResponseInterface $response
    * @return a http response with home page content
    */
    public function home(Request $request, Response $response){
        $this->debug("accessing home page");

        //l'accès à cette page devrait donner toute la documentation concernant l'API
        //elle sera sur un serveur à l'adresse api-iot.croak.fr par exemple
        //tandis que l'appli permettant de récupérer les données de la base sera à l'adresse
        //iot.croak.fr par exemple, avec une partie administration à admin-iot.craok.fr 
        //ou iot.croak.fr/admin
        //Cette partie appli sera donc développée de manière totalement indépendante avec un framework 
        //front end du type Angular qui enverra des requêtes à cette API et recevra des réponses JSON

        return $response;
    }

    /**
    * installs all environment for app : default config file, tables, ...
    * if installation has already be done, install can repair defects but won't reinstall or delete existing
    * @param \Psr\Http\Message\ServerRequestInterface $request
    * @param \Psr\Http\Message\ResponseInterface $response
    * @return a http response indicating if installation is successfull or not
    */
    public function install(Request $request, Response $response){

        $this->debug("accessing install page");        
        $message = "installation failed, ";
        $config = $this->getConfig();

        //build the app: create default config file        
        try{
            Build::createInitFile($config);
            $this->debug("init file has been accessed successfully");
        }            
        catch(InitException $e){
            return serverError($response,$message.$e->getMessage());
        }
        
        //build the app: create database and tables
        try{
            Build::build($config);
            $this->debug("tables has been accessed successfully");
        }
        catch(BuildException $be){
            return serverError($response, $message.$be->getMessage());
        }

        $message = "IoT API installation sucessfull";

        return $this->success($response, $message);
    }
}