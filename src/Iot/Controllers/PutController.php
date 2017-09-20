<?php
namespace Croak\Iot\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Croak\Iot\IoTRequests as IoTRequests;
use Croak\Iot\Exceptions\DeviceException;
use Croak\Iot\Exceptions\MeasureException;
use Croak\Iot\Databases\Exceptions\DataBaseException;

class PutController extends Controller
{
    public function putMeasure(Request $request, Response $response, $args){    
        $sn = (string)$args['sn'];
        $json = $request->getParsedBody();

        $config = $this->getConfig();

        try{
            IoTRequests::putMeasure($sn, $json, $config);
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

        $message = "measure added correctly";
        return $this->success($response, $message);
    }
}