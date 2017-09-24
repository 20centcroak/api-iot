<?php

namespace Croak\Iot;
use Croak\Iot\Databases\TableDevices;
use Croak\Iot\Device;
use Croak\Iot\Databases\TableMeasures;
use Croak\Iot\Measure;
use Croak\Iot\Databases\DbManagement;

/**
 * Manages the requests addressed by the routes. 
 */
class IoTRequests{

    /**
     * Manages a new entry for measure associated with a given device
     * if the device does not exist, it will be created if it complies with the sn pattern
     *
     * @param string $sn            the device sn
     * @param json $json            a json object containing the measure parameters
     * @param Config $config        the configuration of the app
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     *
     * @throws DeviceException      The device SN does not comply with SnPattern in api-config.json
     * @throws MeasureException     Error on the passed key/value  pair for measure
     * @throws DataBaseException    Error while connecting to the database
     */
    public static function putMeasure($sn, $json, $config, DbManagement $db){
      
        $device = Device::create($sn, $config->getSnPattern());

        $tableDevice = new TableDevices($device); 
        $tableDevice->addDevice($db);

        $measure;
        $measure = Measure::create($json);
        $tableMeasures = new TableMeasures($measure);
        $id = $tableMeasures->populate($db);

        $db->disconnect();
        return $id;
    }

    /**
     * Manages a request for delivering measures associated with a given device
     *
     * @param string $sn            the device sn
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     *
     * @throws DeviceException      The device SN does not comply with SnPattern in api-config.json
     * @throws MeasureException     Error on the passed key/value  pair for measure
     * @throws DataBaseException    Error while connecting to the database
     */
     public static function getMeasures($sn, $json, $config, DbManagement $db){  

          $measure;
          $measure = Measure::create($json);
          $tableMeasures = new TableMeasures($measure);
          $id = $tableMeasures->populate($db);
  
          $db->disconnect();
          return $id;
      }

    /**
     * Manages a request for information on device
     * if the device does not exist, a DeviceException occurs
     *
     * @param string $sn            the device sn
     * @param Config $config        the configuration of the app
     * @param Croak\Iot\Databases\DbManagement $db the database connector
     * @throws DeviceException      The device does not comply with SnPattern in api-config.json or does not exist
     * @throws DataBaseException    Error while connecting to the database
     */
    public static function getDevice($sn, $config, $db){

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