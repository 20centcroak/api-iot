<?php
namespace Croak\Iot\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Croak\Iot\Init\Build as Build;
use Croak\Iot\Exceptions\BuildException as BuildException;
use Croak\Iot\Exceptions\InitException as InitException;
use  Croak\Iot\Databases\DbManagement as DbManagement;

class RootController extends Controller
{
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

    public function install(Request $request, Response $response){

        //cette fonction ne doit être appelée qu'une seule fois pour permettre l'initialisation de l'API
        //sur le serveur. Si l'installation a déjà été réalisée, elle peut réparer les erreurs mais ne 
        //supprimera pas l'existant


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

    public function try(Request $request, Response $response){

        try{
            $db = DbManagement::connect();
            //$pdo = $db->getPdo();
        } catch(Exception $e) {
            echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
            die();
        }

        $query = "INSERT OR IGNORE INTO devices(sn, created) VALUES(:sn, :created)";
        $array = array(
                 'sn'			=> "894521",
                 'created'		=> date("Y-m-d H:i:s")
             );

        $db->query($query, $array);

        // $stmt = $pdo->prepare("INSERT OR IGNORE INTO devices(sn, created) VALUES(:sn, :created)");
        // $result = $stmt->execute($array);

    }

}