<?php
namespace Croak\Iot\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Croak\Iot\IoTRequests;
use Croak\Iot\Measure;
use Croak\Iot\Exceptions\DeviceException;
use Croak\Iot\Exceptions\MeasureException;
use Croak\Iot\Exceptions\DataBaseException;

/**
* Controller for routes based on "POST" requests
*/
class PostController extends Controller
{

    public function postMeasures(Request $request, Response $response, $args){
        return $this->post($request, $response, $args, "measure");
    }
    /**
    * add measure in the database
    * @param Psr\Http\Message\ServerRequestInterface $request
    * @param Psr\Http\Message\ResponseInterface $response
    * @param array args request arguments
    * @return a http response indicating if the measure has been correctly added or not
    */
    public function post(Request $request, Response $response, $args, $type){    
        
        $url = $request->getUri();

        $sn = (string)$args['sn'];
        $date = date("Y-m-d H:i:s");
        $arrayJson = $request->getParsedBody();  
        $arrayJson[Measure::KEYS["deviceSn"]] = $sn;
        $arrayJson[Measure::KEYS["date"]] = $date;

        try{
            $id = IoTRequests::postMeasure($sn, $arrayJson, $this->getConfig(), $this->getDataBase());
        }
        catch(DeviceException $e){
            return $this->requestError($response, $e->getMessage());
        }
        catch(MeasureException $e){
            return $this->requestError($response, $e->getMessage());
        }
        catch(DataBaseException $e){
            return $this->serverError($response, $e->getMessage());
        }

        $location = $url.'/'.$id;
        $message = "measure added successfully";
        return $this->createSuccess($response, $location, $message);
    }
}