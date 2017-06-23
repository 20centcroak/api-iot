<?php

namespace Croak\Iot;
use Croak\Iot\Exceptions\DeviceException;

/**
 * Describes a Device thanks to a set of parameters
 */
class Device{

    /**
    *@var String    key names expected in json file
    */
    const SN_KEY="sn";
    const NAME_KEY="name";

    /**
    *@var String    serial number of device
    */
    private $sn;
     /**
    *@var String    name of device
    */
    private $name;

    /** 
    * private constructor : building the object
    * should be done by calling create()
    * @param String $sn       the serial number of the device
    */
    private function __construct($sn){
        $this->sn = $sn;
    }

    /** 
    * build the Device Object if the device sn is matching the specified pattern
    * The specified pattern is set in the configuratiuon file api-config.json
    * @param String $sn                     the serial number of the device
    * @param regex String $snPattern        the regex pattern imposed to the sn
    * @return Device                        a new Device Object
    * @throws DeviceException               if the device sn does not match the sn pattern
    */
    public static function create($sn, $snPattern)
    {
        if (!preg_match($snPattern, $sn)){
            throw new DeviceException(DeviceException::DEVICE_SN);
        }

        return new Device($sn);
    }

    /** 
    * update the device parameters
    * @param Json $json             the json String containing new parameters
    * @throws DeviceException       essential key or value are missing in the json file
    */
    public function update($json){
         $data = json_decode($json);
         

        if(!array_key_exists(Device::SN_KEY, $data)){
            throw new DeviceException(DeviceException::MISSING_KEY);
        }
        if(empty($data->{Device::SN_KEY})){
            throw new DeviceException(DeviceException::MISSING_VALUE);
        }

        $this->sn = $data->{Device::SN_KEY};
        $this->name = $data->{Device::NAME_KEY};
    }

    /** 
    * getter of variable SN
    * @return String the device sn
    */
    public function getSn(){
        return $this->sn;
    }

    /** 
    * getter of variable name
    * @return String the device name
    */
    public function getName(){
        return $this->name;
    }

}