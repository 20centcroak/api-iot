<?php

namespace Croak\Iot;

use Croak\DbManagement\Exceptions\IotException;
use Croak\DbManagement\DbManagementObject;

/**
 * Describes a Device thanks to a set of parameters
 */
class Device extends DbManagementObject
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
    * getter of keys defining the DbManagementObject
    * These keys should be defined as a constant of the DbManagementObject in array KEYS
    * @return array of String 
    */
    public function getKeys(){
        return constant("self::KEYS");
    }

    /**
    * getter of Types associated with the keys defining the DbManagementObject
    * types are test function names like is_string, is_float, is_int, is_numeric, ...
    * These types should be defined as a constant of the DbManagementObject in array KEY_TYPES
    * @return array of String 
    */
    public function getTypes(){
        return constant("self::KEY_TYPES");
    }

    /**
    * getter of the array of boolean defining if the value assosiated with the key is required (true)
    * when populating the database 
    * These required keys should be defined as a constant of the DbManagementObject in array KEY_REQUIRED
    * @return array of boolean 
    */
    public function getRequiredKeys(){
        return constant("self::KEY_REQUIRED");
    }

    /**
    * getter of the array of boolean defining if the value assosiated with the key should be unique (true)
    * when populating the database 
    * These unique keys should be defined as a constant of the DbManagementObject in array KEY_UNIQUE
    * @return array of boolean 
    */
    public function getUniqueKeys(){
        return constant("self::KEY_UNIQUE");
    }
}
