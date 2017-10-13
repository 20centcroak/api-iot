<?php
namespace Croak\Iot\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Croak\Iot\IoTRequests;
use Croak\DbManagement\Exceptions\DbManagementException;
use Croak\DbManagement\Exceptions\DataBaseException;
use Croak\Iot\Exceptions\InitException;

/**
* Controller for routes based on "GET" requests
*/
class GetController extends Controller
{
    /**
    * Request device information
    * @param Psr\Http\Message\ServerRequestInterface $request
    * @param Psr\Http\Message\ResponseInterface $response
    * @param array args request arguments
    * @return a http response containing data about device as a json file or an error status if 
    * problems occur with database or if the device has not been found
    * @throws Croak\DbManagement\DbManagementException
    */
    public function getDevices(Request $request, Response $response, $args){
        return $this->get($request, $response, $args, "device");
    }

    /**
    * Request measure information
    * @param Psr\Http\Message\ServerRequestInterface $request
    * @param Psr\Http\Message\ResponseInterface $response
    * @param array args request arguments
    * @return a http response containing data about measure as a json file or an error status if 
    * problems occur with database
    * @throws Croak\DbManagement\DbManagementException
    */
    public function getMeasures(Request $request, Response $response, $args){
        return $this->get($request, $response, $args, "measure");
    }

     /**
    * Request user information
    * @param Psr\Http\Message\ServerRequestInterface $request
    * @param Psr\Http\Message\ResponseInterface $response
    * @param array args request arguments
    * @return a http response containing data about measure as a json file or an error status if 
    * problems occur with database
    * @throws Croak\DbManagement\DbManagementException
    */
    public function getUsers(Request $request, Response $response, $args){
        return $this->get($request, $response, $args, "user");
    }

    /**
    * Request information from database
    * Params to sort data can be applied thanks to the params associated with the request.
    * for example /measures/{sn}?sort=date&order=desc&type="temperature"
    * @param Psr\Http\Message\ServerRequestInterface $request
    * @param Psr\Http\Message\ResponseInterface $response
    * @param array args request arguments
    * @param String $type type of information  : has to be a key of the Croak/Iot/IoTRequests::TYPES constant
    * @return a http response containing data as a json file or an error status if 
    * problems occur with database or if the device has not been found
    */
    private function get(Request $request, Response $response, $args, $type){

        $params = $request->getQueryParams();

        if(array_key_exists("sn",$args)){
            $params["deviceSn"] = (string)$args['sn'];
        }

        try{
            $iotObjects = IoTRequests::get($this->getDataBase(), $this->getQueries(), $params, $type);

            $data = [];
            foreach($iotObjects as $key=>$val){
                $data[] = $val->getValues();
            }
            return $this->sendJson($response, $data);
        }
        catch(DbManagementException $e){
            return $this->requestError($response, $e->getMessage());
        }
        catch(DatabaseException $e){
            return $this->requestError($response, $e->getMessage());
        }
    }
}