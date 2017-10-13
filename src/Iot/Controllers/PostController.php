<?php
namespace Croak\Iot\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Croak\Iot\IoTRequests;
use Croak\Iot\Measure;
use Croak\Iot\Device;
use Croak\Iot\User;
use Croak\DbManagement\Exceptions\DbManagementException;
use Croak\DbManagement\Exceptions\DataBaseException;

/**
* Controller for routes based on "POST" requests
*/
class PostController extends Controller
{
    /**
    * add measure in the database
    * @param Psr\Http\Message\ServerRequestInterface $request
    * @param Psr\Http\Message\ResponseInterface $response
    * @param array args request arguments
    * @return a http response indicating if the measure has been correctly added or not
    */
    public function postMeasure(Request $request, Response $response, $args){    
        
        $url = $request->getUri();
        $params = $request->getParsedBody();
        $date = date("Y-m-d H:i:s");
        $params[Measure::KEYS["date"]] = $date;  
        
        if (isset($args['sn'])){
            $params[Measure::KEYS["deviceSn"]] = (string)$args['sn'];
        }        

        try{
            $id = IoTRequests::post($this->getDataBase(), $this->getQueries(), $params, "measure");
        }
        catch(DbManagementException $e){
            return $this->requestError($response, $e->getMessage());
        }
        catch(DataBaseException $e){
            return $this->serverError($response, $e->getMessage());
        }

        $location = $url.'/'.$id;
        $message = "measure added successfully";
        return $this->createSuccess($response, $location, $message);
    }

    /**
    * add device in the database
    * @param Psr\Http\Message\ServerRequestInterface $request
    * @param Psr\Http\Message\ResponseInterface $response
    * @param array args request arguments
    * @return a http response indicating if the measure has been correctly added or not
    */
    public function postDevice(Request $request, Response $response, $args){    
        
        $url = $request->getUri();
        $date = date("Y-m-d H:i:s");
        $params = $request->getParsedBody();
        if (isset($args['sn'])){
            $params[Device::KEYS["deviceSn"]] = (string)$args['sn'];
        }   
        $params[Device::KEYS["date"]] = $date;

        try{
            $id = IoTRequests::post($this->getDataBase(), $this->getQueries(), $params, "device");
        }
        catch(DbManagementException $e){
            return $this->requestError($response, $e->getMessage());
        }
        catch(DataBaseException $e){
            return $this->serverError($response, $e->getMessage());
        }

        $location = $url.'/'.$id;
        $message = "device added successfully";
        return $this->createSuccess($response, $location, $message);
    }

    /**
    * add user in the database
    * @param Psr\Http\Message\ServerRequestInterface $request
    * @param Psr\Http\Message\ResponseInterface $response
    * @param array args request arguments
    * @return a http response indicating if the measure has been correctly added or not
    */
    public function postUser(Request $request, Response $response, $args){    
        
        $url = $request->getUri();
        $date = date("Y-m-d H:i:s");
        $params = $request->getParsedBody();  
        $params[User::KEYS["date"]] = $date;

        try{
            $id = IoTRequests::post($this->getDataBase(), $this->getQueries(), $params, "user");
        }
        catch(DbManagementException $e){
            return $this->requestError($response, $e->getMessage());
        }
        catch(DataBaseException $e){
            return $this->serverError($response, $e->getMessage());
        }

        $location = $url.'/'.$id;
        $message = "user added successfully";
        return $this->createSuccess($response, $location, $message);
    }
}