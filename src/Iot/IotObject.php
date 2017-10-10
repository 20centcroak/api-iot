<?php

namespace Croak\Iot;
use Croak\Iot\Exceptions\IotException;

/**
 * Describes an iotObject thanks to a set of parameters
 */
abstract class IotObject{

    /**
    *@var Array $values values of the object
    */
    private $values = array();

    /** 
    * building the object
    * if no params are available an empty object is created
    * if params are not as expected (missing key or value), an Exception is thrown
    * @param mixed $params        the decoded params string
    * @throws IotException if params are not correct
    */
    public function __construct($params){

        if (isset($params)){
            $this->checkRequired($params);
            $keys = $this->getKeys();
            foreach ($keys as $key => $val) {
                if (!isset($params[$val])){
                    $params[$val] = "";
                }
                $this->values[$val] = $params[$val];
            }
        }

         
    }

    /** 
    * build the  Object if the key/value of the params file are correct
    * @param params $params             the params string containing measure parameters
    * @return boolean true if check is ok, throws an Exception otherwise
    * @throws Exception when a parameter for the object is missing in the params string
    */
    private function checkRequired($params){

        $keys = $this->getKeys();
        $required = $this->getRequiredKeys();

        foreach($keys as $key=>$val) {
            
            if($required[$key] && !array_key_exists($val, $params)){
                throw new IotException(IotException::MISSING_KEY);
            }
        }
        foreach ($keys as $key=>$val) {
            if($required[$key] && !isset($params[$val])){
                throw new IotException(IotException::MISSING_VALUE);
            }
        }

        return true;
    }

    /**
    * getter of a measure parameter
    * @return mixed        measure parameter value
    */
    public function getValue($key){
        
        if(!array_key_exists($key, $this->values)){
            throw new IotException(IotException::UNEXISTING_KEY);
        }
        return $this->values[$val];
    }

    /**
    * getter of values array
    * @return array        values array
    */
    public function getValues(){
        return $this->values;
    }

    /**
    * getter of keys defining the IotObject
    * These keys should be defined as a constant of the IotObject
    * @return array of String 
    */
    public abstract function getKeys();

    /**
    * getter of Types associated with the keys defining the IotObject
    * types are test function names like is_string, is_float, is_int, is_numeric, ...
    * These types should be defined as a constant of the IotObject
    * @return array of String 
    */
    public abstract function getTypes();

    /**
    * getter of the array of boolean defining if the value assosiated with the key is required (true)
    * when populating the database 
    * These required keys should be defined as a constant of the IotObject
    * @return array of boolean 
    */
    public abstract function getRequiredKeys();

    /**
    * getter of the array of boolean defining if the value assosiated with the key should be unique (true)
    * when populating the database 
    * These unique keys should be defined as a constant of the IotObject
    * @return array of boolean 
    */
    public abstract function getUniqueKeys();
}