<?php

namespace Croak\Iot;
use Croak\Iot\Exceptions\MeasureException;

/**
 * Describes a Measure thanks to a set of parameters
 * a measure is described by its :
 * -type (temperature, pressure, ...)
 * -unit (°C, bar, ...)
 * -value
 * these 3 parameters should be present in the params string
 * representing the measure
 * the mesaure should be associated with a device defined by its serial number
 */
class Measure{

    /**
    *@var Array  KEYS  key names expected in params file
    */
    const KEYS = array(
        "type"=>"type",
        "unit"=>"unit",
        "value"=>"value",
        "deviceSn"=>"id_device",
        "date"=>"created"
    );

    /**
    *@var Array  KEY_TYPES  key types associated with key names
    */
    const KEY_TYPES = array(
        "type"=>"is_string",
        "unit"=>"is_string",
        "value"=>"is_numeric",
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
        "deviceSn"=>false,
        "date"=>false
    );

    /**
    *@var Array $values values of the measure object
    */
    private $values = array();

    /** 
    * private constructor : building the object
    * should be done by calling create()
    * @param mixed $params        the decoded params string 
    */
    private function __construct($params){

        foreach (MEASURE::KEYS as $key=>$val) {
            $this->values[$val] = $params[$val];
        }
     
    }

    /** 
    * build the Measure Object if the key/value of the params file are correct
    * @param params $params             the params string containing measure parameters
    * @return a new Measure Object
    * @throws MeasureException when a parameter for the measure object is missing in the params string
    */
    public static function create($params){

        foreach (MEASURE::KEYS as $key=>$val) {
            
            if(!array_key_exists($val, $params)){
                throw new MeasureException(MeasureException::MISSING_KEY);
            }
        }
        foreach (MEASURE::KEYS as $key=>$val) {
            if(!isset($params[$val])){
                throw new MeasureException(MeasureException::MISSING_VALUE);
            }
        }

        return new Measure($params);
    }

    /**
    * getter of a measure parameter
    * @return mixed        measure parameter value
    */
    public function getValue($key){
        if(!array_key_exists($key, $this->values)){
            throw new MeasureException(MeasureException::UNEXISTING_KEY);
        }
        return $this->values[$val];
    }

    /**
    * getter of measure array
    * @return array        measure array
    */
    public function getValues(){
        return $this->values;
    }


}