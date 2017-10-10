<?php

namespace Croak\Iot;

use Croak\Iot\Databases\IotTable;
use Croak\Iot\Device;
use Croak\Iot\Measure;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Databases\IotQueries;

/**
 * Manages the requests addressed by the routes. 
 */
class IoTRequests{

    const POST_TYPES=[
        "measure"=>"postMeasure",
        "device"=>"postDevice",
        "user"=>"postUser"
    ];

    const GET_TYPES=[
        "measure"=>"getMeasures",
        "device"=>"getDevices",
        "user"=>"getUsers"
    ];

    /**
     * Manages a new entry for measure associated with a given device
     * if the device does not exist, it will be created if it complies with the sn pattern
     *
     * @param string $sn            the device sn
     * @param params $params            a params object containing the measure parameters
     * @param Config $config        the configuration of the app
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     *
     * @throws DeviceException      The device SN does not comply with SnPattern in api-config.params
     * @throws MeasureException     Error on the passed key/value  pair for measure
     * @throws DataBaseException    Error while connecting to the database
     */
    public static function post(DbManagement $db, IotQueries $queries, $params, $type){
        $table = new IotTable();
        $function = IoTRequests::POST_TYPES[$type];

        return IoTRequests::$function($table, $db, $queries, $params);   
    }

    public static function postMeasure(IotTable $table, DbManagement $db, IotQueries $queries, $params){
      
        #TODO il faudra modifier cela : le device doit être créé indépendamment et on devra vérifier 
        #TODO qu'il existe avant d'ajouter une mesure
        #TODO on vérifiera en même temps si son sn respecte le pattern
        $deviceParams = [];
        $deviceParams[Device::KEYS["deviceSn"]] = $params[Measure::KEYS["deviceSn"]];
        $deviceParams[Device::KEYS["date"]] = $params[Measure::KEYS["date"]];
        $device = new Device($deviceParams);
        $table->populate($db, $device, $queries->addDevice());

        $measure;
        $measure = new Measure($params);
        $id = $table->populate($db, $measure, $queries->addMeasure());

        $db->disconnect();
        return $id;
    }


    /**
     * Manages a request for delivering iotObjects
     *
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @param $params array of parameters from the http request
     * @throws IotException     Error on the passed key/value  pair for measure
     * @throws DataBaseException    Error while connecting to the database
     */
     public static function get(DbManagement $db, IotQueries $queries, $params, $type){
        
            $table = new IotTable();
            $function = IoTRequests::GET_TYPES[$type];

            return IoTRequests::$function($table, $db, $queries, $params);            
    }

    /**
     * Manages a request for delivering measures
     *
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @param $params array of parameters from the http request
     * @throws IotException     Error on the passed key/value  pair for measure
     * @throws DataBaseException    Error while connecting to the database
     */
     private static function getMeasures(IotTable $table, DbManagement $db, IotQueries $queries, $params){

        $argMeasures = $table->getData($db, $queries->selectMeasures(), $params, new Measure(null));        
        
        $measures = [];
        foreach($argMeasures as $val){
            $measures[]=new Measure($val);
        }

        return $measures;
      }

    /**
     * Manages a request for information on device
     * if the device does not exist, a DeviceException occurs
     *
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @param $params               array of parameters from the http request
     * @throws IotException         Error on the passed key/value  pair for device or if the 
     *                              device sn does not comply with the configured sn pattern
     * @throws DataBaseException    Error while connecting to the database
     */
     private static function getDevices(IotTable $table, DbManagement $db, IotQueries $queries, $params){

        $argDevices = $table->getData($db, $queries->selectDevices(), $params, new Device(null));

        $devices = [];
        foreach($argDevices as $val){
            $dev=new Device($val);
            $devices[] = $dev;
        }

        return $devices;       
    }

}