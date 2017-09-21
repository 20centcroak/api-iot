<?php
namespace Croak\Iot\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Croak\Iot\IoTRequests;
use Croak\Iot\Exceptions\DeviceException;
use Croak\Iot\Exceptions\MeasureException;
use Croak\Iot\Exceptions\DataBaseException;

/**
* Controller for routes based on "POST" requests
*/
class PostController extends Controller
{
    /**
    * add measure in the database
    * @param \Psr\Http\Message\ServerRequestInterface $request
    * @param \Psr\Http\Message\ResponseInterface $response
    * @param array args request arguments
    * @return a http response indicating if the measure has been correctly added or not
    */
    public function postMeasure(Request $request, Response $response, $args){    
        $sn = (string)$args['sn'];
        $json = $request->getParsedBody();
        $url = $request->getUri();

        try{
            $id = IoTRequests::putMeasure($sn, $json, $this->getConfig());
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