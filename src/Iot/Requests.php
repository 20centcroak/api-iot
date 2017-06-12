<?php

namespace Croak\Iot;
use Croak\Iot\Databases\TableDevices;
use Croak\Iot\Exceptions\DeviceException;
use Croak\Iot\Device;
use Croak\Iot\Databases\TableMeasures;
use Croak\Iot\Exceptions\MeasureException;
use Croak\Iot\Measure;

/**
 * Manages the requests addressed by the routes. 
 */
class Requests{

    /**
     * Manages a new entry for measure associated with a given device
     * if the device does not exist, it will be created if it complies with the sn pattern
     *
     * @param string $sn            the device sn
     * @param json $json            a json object containing the measure parameters
     * @param Config $config        the configuration of the app
     *
     * @throws DeviceException      The device SN does not comply with SnPattern in api-config.json
     * @throws MeasureException     Error on the passed key/value  pair for measure
     */
    public static function putMeasure($sn, $json, $config){

        $device;        
        $device = Device::create($sn, $config->getSnPattern());
        $tableDevice = new TableDevices($device);        

        $measure;
        $measure = Measure::create($json, $sn);
        $tableMeasures = new TableMeasures($measure);
        $tableMeasures->populate();
    }

}