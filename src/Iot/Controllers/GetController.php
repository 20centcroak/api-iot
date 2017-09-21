<?php
namespace Croak\Iot\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Croak\Iot\IoTRequests as IoTRequests;
use Croak\Iot\Exceptions\DeviceException as DeviceException;
use Croak\Iot\Exceptions\DataBaseException as DataBaseException;
use Croak\Iot\Device as Device;

/**
* Controller for routes based on "GET" requests
*/
class GetController extends Controller
{
    /**
    * Request device information based on device serial number ($args['sn'])
    * @param \Psr\Http\Message\ServerRequestInterface $request
    * @param \Psr\Http\Message\ResponseInterface $response
    * @param array args request arguments
    * @return a http response containing data about device as a json file or an error status if 
    * problems occur with database or if the device has not been found
    */
    public function getDevice(Request $request, Response $response, $args){
        $sn = (string)$args['sn'];
        $this->debug("get profile for $sn");

        try{
            $device = IoTRequests::getDevice($sn, $this->getConfig());
            if($device==null){
                return $this->requestError($response, "device sn does not exist");
            }
            $data = $device->getAllData();
            return $this->sendJson($response, $data);
        }
        catch(DeviceException $e){
            return $this->requestError($response, $e->getMessage());
        }
        catch(DatabaseException $e){
            return $this->requestError($response, $e->getMessage());
        }

    }
}