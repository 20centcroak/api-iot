<?php

namespace Croak\Iot;

use Croak\Iot\Exceptions\IotException;

/**
 * Describes a Device thanks to a set of parameters
 */
class Device extends IotObject
{
/**
    *@var Array  KEYS  key names expected in params file
    */
    const KEYS = array(
        "deviceSn"=>"sn",
        "date"=>"created",
        "name"=>"name",
        "type"=>"type",
        "version"=>"version",
        "idUser"=>"id_user"
    );

    /**
    *@var Array  KEY_TYPES  key types associated with key names
    */
    const KEY_TYPES = array(
        "deviceSn"=>"is_string",
        "date"=>"is_string",
        "name"=>"is_string",
        "type"=>"is_string",
        "version"=>"is_string",
        "idUser"=>"is_int"
    );

    /**
    *@var Array  KEY_REQUIRED  indicates which keys are required
    */
    const KEY_REQUIRED = array(
        "deviceSn"=>true,
        "date"=>true,
        "name"=>false,
        "type"=>false,
        "version"=>false,
        "idUser"=>false
    );

    /**
    *@var Array  KEY_UNIQUE  indicates which keys should be unique
    */
    const KEY_UNIQUE = array(
        "deviceSn"=>true,
        "date"=>false,
        "name"=>false,
        "type"=>false,
        "version"=>false,
        "idUser"=>false
    );

    /**
    * getter of keys defining the IotObject
    * These keys should be defined as a constant of the IotObject
    * @return array of String 
    */
    public function getKeys(){
        return Device::KEYS;
    }
    
    /**
    * getter of Types associated with the keys defining the IotObject
    * types are test function names like is_string, is_float, is_int, is_numeric, ...
    * @return array of String 
    */
    public function getTypes(){
        return Device::KEY_TYPES;
    }

    /**
    * getter of the array of boolean defining if the value assosiated with the key is required (true)
    * when populating the database 
    * @return array of boolean 
    */
    public function getRequiredKeys(){
        return Device::KEY_REQUIRED;
    }

    /**
    * getter of the array of boolean defining if the value assosiated with the key should be unique (true)
    * when populating the database 
    * @return array of boolean 
    */
    public function getUniqueKeys(){
        return Device::KEY_UNIQUE;
    }
}
