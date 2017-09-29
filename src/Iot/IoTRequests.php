<?php

namespace Croak\Iot;
use Croak\Iot\Databases\TableDevices;
use Croak\Iot\Device;
use Croak\Iot\Databases\TableMeasures;
use Croak\Iot\Measure;
use Croak\Iot\Databases\DbManagement;
use Croak\Iot\Databases\IotQueries;

/**
 * Manages the requests addressed by the routes. 
 */
class IoTRequests{

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
    public static function postMeasure($sn, $params, $config, DbManagement $db, IotQueries $queries){
      
        #TODO il faudra modifier cela : le device doit être créé indépendamment et on devra vérifier 
        #TODO qu'il existe avant d'ajouter une mesure
        $device = Device::create($sn, $config->getSnPattern());

        $tableDevice = new TableDevices($device); 
        $tableDevice->addDevice($db);

        $measure;
        $measure = Measure::create($params);
        $tableMeasures = new TableMeasures();
        $id = $tableMeasures->populate($db, $measure);

        $db->disconnect();
        return $id;
    }

    /**
     * Manages a request for delivering measures associated with a given device
     *
     * @param string $sn            the device sn
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @param $params array of parameters from the http request
     * @throws DeviceException      The device SN does not comply with SnPattern in api-config.params
     * @throws MeasureException     Error on the passed key/value  pair for measure
     * @throws DataBaseException    Error while connecting to the database
     */
     public static function getMeasures(DbManagement $db, IotQueries $queries, $params){

        $tableMeasures = new TableMeasures();
        $measures = $tableMeasures->getMeasures($db, $queries, $params);

        $db->disconnect();
        return $measures;
      }

    /**
     * Manages a request for information on device
     * if the device does not exist, a DeviceException occurs
     *
     * @param string $sn            the device sn
     * @param Config $config        the configuration of the app
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @throws DeviceException      The device does not comply with SnPattern in api-config.params or does not exist
     * @throws DataBaseException    Error while connecting to the database
     */
    public static function getDevice($sn, $config, $db, IotQueries $queries){

        $device = Device::create($sn, $config->getSnPattern());
        $tableDevice = new TableDevices($device);   
        $deviceExists = $tableDevice->updateDeviceInformation($db);
        $db->disconnect();

        if(!$deviceExists){
            $device = null;
        }
                
        return $device;        
    }

}