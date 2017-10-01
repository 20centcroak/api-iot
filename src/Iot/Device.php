<?php

namespace Croak\Iot;

use Croak\Iot\Exceptions\DeviceException;

/**
 * Describes a Device thanks to a set of parameters
 */
class Device
{
/**
    *@var Array  KEYS  key names expected in params file
    */
    const KEYS = array(
        "deviceSn"=>"sn",
        "date"=>"created",
        "idUser"=>"idUser"
    );

    /**
    *@var Array  KEY_TYPES  key types associated with key names
    */
    const KEY_TYPES = array(
        "deviceSn"=>"is_string",
        "date"=>"is_string",
        "idUser"=>"is_int"
    );

    /**
    *@var Array  KEY_REQUIRED  indicates which keys are required
    */
    const KEY_REQUIRED = array(
        "deviceSn"=>true,
        "date"=>true,
        "idUser"=>false
    );

    /**
    *@var Array  KEY_UNIQUE  indicates which keys should be unique
    */
    const KEY_UNIQUE = array(
        "deviceSn"=>true,
        "date"=>false,
        "idUser"=>false
    );

    /**
    *@var Array $values values of the device object
    */
    private $values = array();

    /** 
    * private constructor : building the object
    * should be done by calling create()
    * @param mixed $params        the decoded params string 
    */
    private function __construct($params){
        foreach (Device::KEYS as $key=>$val) {
            $this->values[$val] = $params[$val];
        }            
    }
        
    /** 
    * build the Device Object if the key/value of the params file are correct
    * @param params $params             the params string containing device parameters
    * @return a new Device Object
    * @throws DeviceException when a parameter for the device object is missing in the params string
    */
    public static function create($params){

        foreach (Device::KEYS as $key=>$val) {            
            if(!array_key_exists($val, $params)){
                throw new DeviceException(DeviceException::MISSING_KEY);
            }
        }
        foreach (Device::KEYS as $key=>$val) {
            if(!isset($params[$val])){
                throw new MeasureException(DeviceException::MISSING_VALUE);
            }
            if($key==="deviceSn" & !preg_match($snPattern, $params[$val])) {
                throw new DeviceException(DeviceException::DEVICE_SN);
            }
        }

        return new Device($params);
    }

    /**
    * getter of a device parameter
    * @return mixed        device parameter value
    */
    public function getValue($key){
        if(!array_key_exists($key, $this->values)){
            throw new DeviceException(DeviceException::UNEXISTING_KEY);
        }
        return $this->values[$val];
    }

    /**
    * getter of device array
    * @return array        device array
    */
    public function getValues(){
        return $this->values;
    }

}
