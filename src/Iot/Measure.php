<?php

namespace Croak\Iot;
use Croak\DbManagement\Exceptions\IotException;
use Croak\DbManagement\DbManagementObject;

/**
 * Describes a Measure thanks to a set of parameters
 * a measure is described by its :
 * -type (temperature, pressure, ...)
 * -unit (°C, bar, ...)
 * -value
 * these 3 parameters should be present in the params string
 * representing the measure
 * the mesaure should be associated with a Measure defined by its serial number
 */
class Measure extends DbManagementObject{

    /**
    *@var Array  KEYS  key names expected in params file
    */
    const KEYS = array(
        "type"=>"type",
        "unit"=>"unit",
        "value"=>"value",
        "flag"=>"flag",
        "deviceSn"=>"id_device",
        "date"=>"created"
    );

    /**
    *@var Array  KEY_TYPES  key types associated with key names
    */
    const KEY_TYPES = array(
        "type"=>"is_string",
        "unit"=>"is_string",
        "value"=>"is_float",
        "flag"=>"is_string",
        "deviceSn"=>"is_string",
        "date"=>"is_string"
    );

    /**
    *@var Array  KEY_REQUIRED  indicates which keys are required
    */
    const KEY_REQUIRED = array(
        "type"=>true,
        "unit"=>true,
        "value"=>true,
        "flag"=>false,
        "deviceSn"=>true,
        "date"=>true
    );

    /**
    *@var Array  KEY_UNIQUE  indicates which keys should be unique
    */
    const KEY_UNIQUE = array(
        "type"=>false,
        "unit"=>false,
        "value"=>false,
        "flag"=>false,
        "deviceSn"=>false,
        "date"=>false
    );
 
    /**
    * getter of keys defining the IotObject
    * These keys should be defined as a constant of the IotObject
    * @return array of String 
    */
    public function getKeys(){
        return Measure::KEYS;
    }
    
    /**
    * getter of Types associated with the keys defining the IotObject
    * types are test function names like is_string, is_float, is_int, is_numeric, ...
    * @return array of String 
    */
    public function getTypes(){
        return Measure::KEY_TYPES;
    }

    /**
    * getter of the array of boolean defining if the value assosiated with the key is required (true)
    * when populating the database 
    * @return array of boolean 
    */
    public function getRequiredKeys(){
        return Measure::KEY_REQUIRED;
    }

    /**
    * getter of the array of boolean defining if the value assosiated with the key should be unique (true)
    * when populating the database 
    * @return array of boolean 
    */
    public function getUniqueKeys(){
        return Measure::KEY_UNIQUE;
    }
}