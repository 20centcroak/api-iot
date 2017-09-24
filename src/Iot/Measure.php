<?php

namespace Croak\Iot;
use Croak\Iot\Exceptions\MeasureException;

/**
 * Describes a Measure thanks to a set of parameters
 * a measure is described by its :
 * -type (temperature, pressure, ...)
 * -unit (°C, bar, ...)
 * -value
 * these 3 parameters should be present in the json string
 * representing the measure
 * the mesaure should be associated with a device defined by its serial number
 */
class Measure{

    /**
    *@var Array  KEYS  key names expected in json file
    */
    const KEYS = array(
        "type"=>"type",
        "unit"=>"unit",
        "value"=>"value",
        "deviceSn"=>"id_device",
        "date"=>"created"
    );

    /**
    *@var Array $values values of the measure object
    */
    private $values = array();

    /** 
    * private constructor : building the object
    * should be done by calling create()
    * @param mixed $json        the decoded json string 
    */
    private function __construct($json){

        foreach (MEASURE::KEYS as $key=>$val) {
            $this->values[$val] = $json[$val];
        }
     
    }

    /** 
    * build the Measure Object if the key/value of the json file are correct
    * @param Json $json             the json string containing measure parameters
    * @return a new Measure Object
    * @throws MeasureException when a parameter for the measure object is missing in the json string
    */
    public static function create($json){  

        foreach (MEASURE::KEYS as $key=>$val) {
            if(!array_key_exists($val, $json)){
                throw new MeasureException(MeasureException::MISSING_KEY);
            }
        }
        foreach (MEASURE::KEYS as $key=>$val) {
            if(!isset($json[$val])){
                throw new MeasureException(MeasureException::MISSING_VALUE);
            }
        }

        return new Measure($json);
    }

    /**
    * getter of a measure parameter
    * @return mixed        measure parameter value
    */
    public function getValue($key){
        if(!array_key_exists($key, $this->values)){
            throw new MeasureException(MeasureException::UNEXISTING_KEY);
        }
        return $this->values[$key];
    }

    /**
    * getter of measure array
    * @return array        measure array
    */
    public function getValues(){
        return $this->values;
    }


}